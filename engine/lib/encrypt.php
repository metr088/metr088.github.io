<?php 
class encrypt {
	var $encryption_key	= '';
	var $_hash_type	= 'sha1';
	var $_mcrypt_exists = FALSE;
	var $_mcrypt_cipher;
	var $_mcrypt_mode;
	protected $config;


	public function __construct()
	{
		$this->config = load_class('Config', 'core')->item();
		$this->_mcrypt_exists = ( ! function_exists('mcrypt_encrypt')) ? FALSE : TRUE;
	}

	function get_key($key = ''){
		if ($key == ''){
			if ($this->encryption_key != ''){

				return $this->encryption_key;
			}

			$key = $this->config['encryption_key'];
            
			if ($key == FALSE)
			{
				show_error('In order to use the encryption class requires that you set an encryption key in your config file.');
			}
		}
		return md5($key);
	}

	function set_key($key = '')
	{
		$this->encryption_key = $key;
	}

	function encode($string, $key = '')
	{
		$key = $this->get_key($key);

		if ($this->_mcrypt_exists === TRUE)
		{
			$enc = $this->mcrypt_encode($string, $key);
		}
		else
		{
			$enc = $this->_xor_encode($string, $key);
		}

		return base64_encode($enc);
	}

	function decode($string, $key = '')
	{
		$key = $this->get_key($key);

		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string))
		{
			return FALSE;
		}

		$dec = base64_decode($string);

		if ($this->_mcrypt_exists === TRUE)
		{
			if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE)
			{
				return FALSE;
			}
		}
		else
		{
			$dec = $this->_xor_decode($dec, $key);
		}

		return $dec;
	}

	function encode_from_legacy($string, $legacy_mode = MCRYPT_MODE_ECB, $key = '')
	{
		if ($this->_mcrypt_exists === FALSE)
		{
			return FALSE;
		}
		$current_mode = $this->_get_mode();
		$this->set_mode($legacy_mode);

		$key = $this->get_key($key);

		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string))
		{
			return FALSE;
		}

		$dec = base64_decode($string);

		if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE)
		{
			return FALSE;
		}

		$dec = $this->_xor_decode($dec, $key);

		// set the mcrypt mode back to what it should be, typically MCRYPT_MODE_CBC
		$this->set_mode($current_mode);

		// and re-encode
		return base64_encode($this->mcrypt_encode($dec, $key));
	}

	function _xor_encode($string, $key)
	{
		$rand = '';
		while (strlen($rand) < 32)
		{
			$rand .= mt_rand(0, mt_getrandmax());
		}

		$rand = $this->hash($rand);

		$enc = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
		}

		return $this->_xor_merge($enc, $key);
	}

	function _xor_decode($string, $key)
	{
		$string = $this->_xor_merge($string, $key);

		$dec = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
		}

		return $dec;
	}

	function _xor_merge($string, $key)
	{
		$hash = $this->hash($key);
		$str = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
		}

		return $str;
	}

	function mcrypt_encode($data, $key)
	{
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());
		$init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
		return $this->_add_cipher_noise($init_vect.mcrypt_encrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), $key);
	}

	function mcrypt_decode($data, $key)
	{
		$data = $this->_remove_cipher_noise($data, $key);
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());

		if ($init_size > strlen($data))
		{
			return FALSE;
		}

		$init_vect = substr($data, 0, $init_size);
		$data = substr($data, $init_size);
		return rtrim(mcrypt_decrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), "\0");
	}

	function _add_cipher_noise($data, $key)
	{
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
		{
			if ($j >= $keylen)
			{
				$j = 0;
			}

			$str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
		}

		return $str;
	}

	function _remove_cipher_noise($data, $key)
	{
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
		{
			if ($j >= $keylen)
			{
				$j = 0;
			}

			$temp = ord($data[$i]) - ord($keyhash[$j]);

			if ($temp < 0)
			{
				$temp = $temp + 256;
			}

			$str .= chr($temp);
		}

		return $str;
	}

	function set_cipher($cipher)
	{
		$this->_mcrypt_cipher = $cipher;
	}

	function set_mode($mode)
	{
		$this->_mcrypt_mode = $mode;
	}

	function _get_cipher()
	{
		if ($this->_mcrypt_cipher == '')
		{
			$this->_mcrypt_cipher = MCRYPT_RIJNDAEL_256;
		}

		return $this->_mcrypt_cipher;
	}

	function _get_mode()
	{
		if ($this->_mcrypt_mode == '')
		{
			$this->_mcrypt_mode = MCRYPT_MODE_CBC;
		}

		return $this->_mcrypt_mode;
	}

	function set_hash($type = 'sha1')
	{
		$this->_hash_type = ($type != 'sha1' AND $type != 'md5') ? 'sha1' : $type;
	}

	function hash($str)
	{
		return ($this->_hash_type == 'sha1') ? $this->sha1($str) : md5($str);
	}

	function sha1($str)
	{
		if ( ! function_exists('sha1'))
		{
			if ( ! function_exists('mhash'))
			{
				require_once(DIR.'/'. PATHER .'/class/Sha1.php');
				$SH = new CI_SHA;
				return $SH->generate($str);
			}
			else
			{
				return bin2hex(mhash(MHASH_SHA1, $str));
			}
		}
		else
		{
			return sha1($str);
		}
	}

}
?>