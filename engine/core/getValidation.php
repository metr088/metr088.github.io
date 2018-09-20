<?php

class getValidation {

	protected $CI;
	protected $_field_data			= array();
	protected $_config_rules		= array();
	protected $_error_array			= array();
	protected $_error_messages		= array();
	protected $_error_prefix		= '<p>';
	protected $_error_suffix		= '</p>';
	protected $error_string			= '';
	protected $_safe_form_data		= FALSE;
	protected $lang	= array();
	public $test = '';

	public function __construct($rules = array())
	{
		$this->CI = get_instance();
		$this->_config_rules = $rules;
        $this->lang['required'] = 'Поле {field} обязательно.';
        $this->lang['isset'] = 'Поле {field} должно быть заполнено.';
        $this->lang['valid_email'] = 'Поле {field} должно содержать правильный E-mail адрес.';
        $this->lang['valid_emails'] = 'Поле {field} должно содержать правильные E-mail адреса.';
        $this->lang['valid_url'] = 'Поле {field} должно содержать правильный URL.';
        $this->lang['valid_ip'] = 'Поле {field} должно содержать правильный IP.';
        $this->lang['min_length'] = 'Длина поля {field} должна быть по крайней мере {param} символов.';
        $this->lang['max_length'] = 'Длина поля {field} не может превышать {param} символов.';
        $this->lang['exact_length'] = 'Длина поля {field} должна быть равной {param} символов.';
        $this->lang['alpha'] = 'Поле {field} может состоять только из букв.';
        $this->lang['alpha_numeric'] = 'Поле {field} может состоять только из букв и цифр.';
        $this->lang['alpha_numeric_spaces'] = 'Поле {field} может состоять только из букв, цифр или пробелов.';
        $this->lang['alpha_dash'] = 'Поле {field} может состоять только из букв, цифр, знаков подчеркивания и тире.';
        $this->lang['numeric'] = 'Поле {field} может состоять только из цифр.';
        $this->lang['is_numeric'] = 'Поле {field} может состоять только из цифровых значений.';
        $this->lang['integer'] = 'Поле {field} должно содержать целочисленное значение.';
        $this->lang['regex_match'] = 'Поле {field} заполнено неправильно.';
        $this->lang['matches'] = 'Поле {field} не соответствует параметру {param}.';
        $this->lang['differs'] = 'Поле {field} должно отличаться от параметра {param}.';
        $this->lang['is_unique'] = 'Поле {field} должно быть уникальным.';
        $this->lang['is_natural'] = 'Поле {field} должно содержать только цифры.';
        $this->lang['is_natural_no_zero'] = 'Поле {field} должно содержать только цифры и быть больше нуля.';
        $this->lang['decimal'] = 'Поле {field} должно содержать десятичное значение.';
        $this->lang['less_than'] = 'Поле {field} должно содержать значение меньше {param}.';
        $this->lang['less_than_equal_to'] = 'Поле {field} должно содержать значение меньше или равно {param}.';
        $this->lang['greater_than'] = 'Поле {field} должно содержать значение больше {param}.';
        $this->lang['greater_than_equal_to'] = 'Поле {field} должно содержать значение больше или равно {param}.';
        $this->lang['error_message_not_set'] = 'Для поля {field} не установлено сообщение об ошибке.';
        $this->lang['in_list'] = 'Поле {field} должно содержать указанные значения.';
	}

	public function set_rules($field, $label = '', $rules = '')
	{
		if (count($_GET) == 0)
		{
			return $this;
		}

		if (is_array($field))
		{
			foreach ($field as $row)
			{
				if ( ! isset($row['field']) OR ! isset($row['rules']))
				{
					continue;
				}
				$label = ( ! isset($row['label'])) ? $row['field'] : $row['label'];
				$this->set_rules($row['field'], $label, $row['rules']);
			}
			return $this;
		}
		if ( ! is_string($field) OR  ! is_string($rules) OR $field == '')
		{
			return $this;
		}
		$label = ($label == '') ? $field : $label;
		if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches))
		{
			$x = explode('[', $field);
			$indexes[] = current($x);

			for ($i = 0; $i < count($matches['0']); $i++)
			{
				if ($matches['1'][$i] != '')
				{
					$indexes[] = $matches['1'][$i];
				}
			}

			$is_array = TRUE;
		}
		else
		{
			$indexes	= array();
			$is_array	= FALSE;
		}

		$this->_field_data[$field] = array(
			'field'				=> $field,
			'label'				=> $label,
			'rules'				=> $rules,
			'is_array'			=> $is_array,
			'keys'				=> $indexes,
			'postdata'			=> NULL,
			'error'				=> ''
		);

		return $this;
	}


	public function set_message($lang, $val = '')
	{
		if ( ! is_array($lang))
		{
			$lang = array($lang => $val);
		}

		$this->_error_messages = array_merge($this->_error_messages, $lang);

		return $this;
	}

	public function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
	{
		$this->_error_prefix = $prefix;
		$this->_error_suffix = $suffix;

		return $this;
	}

	public function error($field = '', $prefix = '', $suffix = '')
	{
		if ( ! isset($this->_field_data[$field]['error']) OR $this->_field_data[$field]['error'] == '')
		{
			return '';
		}

		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		return $prefix.$this->_field_data[$field]['error'].$suffix;
	}


	public function error_string($prefix = '', $suffix = '')
	{
		if (count($this->_error_array) === 0)
		{
			return '';
		}

		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		$str = '';
		foreach ($this->_error_array as $val)
		{
			if ($val != '')
			{
				$str .= $prefix.$val.$suffix."\n";
			}
		}

		return $str;
	}

	public function run($group = '')
	{
		if (count($_GET) == 0)
		{
			return FALSE;
		}

		if (count($this->_field_data) == 0)
		{
			if (count($this->_config_rules) == 0)
			{
				return FALSE;
			}

			$uri = ($group == '') ? trim($this->CI->uri->ruri_string(), '/') : $group;

			if ($uri != '' AND isset($this->_config_rules[$uri]))
			{
				$this->set_rules($this->_config_rules[$uri]);
			}
			else
			{
				$this->set_rules($this->_config_rules);
			}
			if (count($this->_field_data) == 0)
			{
				return FALSE;
			}
		}

        $lang = $this->lang;

		foreach ($this->_field_data as $field => $row)
		{

			if ($row['is_array'] == TRUE)
			{
				$this->_field_data[$field]['postdata'] = $this->_reduce_array($_GET, $row['keys']);
			}
			else
			{
				if (isset($_GET[$field]) AND $_GET[$field] != "")
				{
					$this->_field_data[$field]['postdata'] = $_GET[$field];
				}
			}

			$this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);
		}

		$total_errors = count($this->_error_array);

		if ($total_errors > 0)
		{
			$this->_safe_form_data = TRUE;
		}

		$this->_reset_GET_array();

		if ($total_errors == 0)
		{
			return TRUE;
		}
		return FALSE;
	}

	protected function _reduce_array($array, $keys, $i = 0)
	{
		if (is_array($array))
		{
			if (isset($keys[$i]))
			{
				if (isset($array[$keys[$i]]))
				{
					$array = $this->_reduce_array($array[$keys[$i]], $keys, ($i+1));
				}
				else
				{
					return NULL;
				}
			}
			else
			{
				return $array;
			}
		}

		return $array;
	}

	protected function _reset_GET_array()
	{
		foreach ($this->_field_data as $field => $row)
		{
			if ( ! is_null($row['postdata']))
			{
				if ($row['is_array'] == FALSE)
				{
					if (isset($_GET[$row['field']]))
					{
						$_GET[$row['field']] = $this->prep_for_form($row['postdata']);
					}
				}
				else
				{
					$post_ref =& $_GET;
					if (count($row['keys']) == 1)
					{
						$post_ref =& $post_ref[current($row['keys'])];
					}
					else
					{
						foreach ($row['keys'] as $val)
						{
							$post_ref =& $post_ref[$val];
						}
					}

					if (is_array($row['postdata']))
					{
						$array = array();
						foreach ($row['postdata'] as $k => $v)
						{
							$array[$k] = $this->prep_for_form($v);
						}

						$post_ref = $array;
					}
					else
					{
						$post_ref = $this->prep_for_form($row['postdata']);
					}
				}
			}
		}
	}

	protected function _execute($row, $rules, $postdata = NULL, $cycles = 0)
	{


		if (is_array($postdata))
		{
			foreach ($postdata as $key => $val)
			{
				$this->_execute($row, $rules, $val, $cycles);
				$cycles++;
			}

			return;
		}

		$callback = FALSE;
		if ( ! in_array('required', $rules) AND is_null($postdata))
		{
			if (preg_match("/(callback_\w+(\[.*?\])?)/", implode(' ', $rules), $match))
			{
				$callback = TRUE;
				$rules = (array('1' => $match[1]));
			}
			else
			{
				return;
			}
		}

		if (is_null($postdata) AND $callback == FALSE)
		{
			if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
			{

				$type = (in_array('required', $rules)) ? 'required' : 'isset';

				if ( ! isset($this->_error_messages[$type]))
				{
					if (FALSE === ($line = $this->lang[$type]))
					{
						$line = 'The field was not set';
					}
				}
				else
				{
					$line = $this->_error_messages[$type];
				}
				$message = sprintf($line, $this->_translate_fieldname($row['label']));
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
                    $message = str_replace("{field}", $row['label'], $message);
                    $this->_error_array[$row['field']] = $message;
				}
			}

			return;
		}

		foreach ($rules As $rule)
		{
			$_in_array = FALSE;

			if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
			{
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}

				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
				$postdata = $this->_field_data[$row['field']]['postdata'];
			}

			$callback = FALSE;
			if (substr($rule, 0, 9) == 'callback_')
			{
				$rule = substr($rule, 9);
				$callback = TRUE;
			}
			$param = FALSE;
			if (preg_match("/(.*?)\[(.*)\]/", $rule, $match))
			{
				$rule	= $match[1];
				$param	= $match[2];
			}

			if ($callback === TRUE)
			{
				if ( ! method_exists($this->CI, $rule))
				{
					continue;
				}

				$result = $this->CI->$rule($postdata, $param);

				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}

				if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
				{
					continue;
				}
			}
			else
			{
				if ( ! method_exists($this, $rule))
				{

					if (function_exists($rule))
					{
						$result = $rule($postdata);

						if ($_in_array == TRUE)
						{
							$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
						}
						else
						{
							$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
						}
					}

					continue;
				}

				$result = $this->$rule($postdata, $param);

				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}
			}

			if ($result === FALSE)
			{
				if ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = $this->lang[$rule]))
					{
						$line = 'Unable to access an error message corresponding to your field name.';
					}
				}
				else
				{
					$line = $this->_error_messages[$rule];
				}

				if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
				{
					$param = $this->_translate_fieldname($this->_field_data[$param]['label']);
				}

				$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
                    $message = str_replace("{field}", $row['label'], $message);
                    $message = str_replace("{param}", $param, $message);
                    $this->_error_array[$row['field']] = $message;

				}
				return;
			}
		}
	}


	protected function _translate_fieldname($fieldname)
	{

		if (substr($fieldname, 0, 5) == 'lang:')
		{
			// Grab the variable
			$line = substr($fieldname, 5);

			if (FALSE === ($fieldname = $this->lang[$line]))
			{
				return $line;
			}
		}

		return $fieldname;
	}


	public function set_value($field = '', $default = '')
	{
		if ( ! isset($this->_field_data[$field]))
		{
			return $default;
		}

		if (is_array($this->_field_data[$field]['postdata']))
		{
			return array_shift($this->_field_data[$field]['postdata']);
		}

		return $this->_field_data[$field]['postdata'];
	}

	public function set_select($field = '', $value = '', $default = FALSE)
	{
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
		{
			if ($default === TRUE AND count($this->_field_data) === 0)
			{
				return ' selected="selected"';
			}
			return '';
		}

		$field = $this->_field_data[$field]['postdata'];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' selected="selected"';
	}

	public function set_radio($field = '', $value = '', $default = FALSE)
	{
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
		{
			if ($default === TRUE AND count($this->_field_data) === 0)
			{
				return ' checked="checked"';
			}
			return '';
		}

		$field = $this->_field_data[$field]['postdata'];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' checked="checked"';
	}

	public function set_checkbox($field = '', $value = '', $default = FALSE)
	{
		if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
		{
			if ($default === TRUE AND count($this->_field_data) === 0)
			{
				return ' checked="checked"';
			}
			return '';
		}

		$field = $this->_field_data[$field]['postdata'];

		if (is_array($field))
		{
			if ( ! in_array($value, $field))
			{
				return '';
			}
		}
		else
		{
			if (($field == '' OR $value == '') OR ($field != $value))
			{
				return '';
			}
		}

		return ' checked="checked"';
	}

	public function required($str)
	{
		if ( ! is_array($str))
		{
			return (trim($str) == '') ? FALSE : TRUE;
		}
		else
		{
			return ( ! empty($str));
		}
	}

	public function regex_match($str, $regex)
	{
		if ( ! preg_match($regex, $str))
		{
			return FALSE;
		}

		return  TRUE;
	}

	public function matches($str, $field)
	{
		if ( ! isset($_GET[$field]))
		{
			return FALSE;
		}

		$field = $_GET[$field];

		return ($str !== $field) ? FALSE : TRUE;
	}
	
	public function is_unique($str, $field)
	{
		list($table, $field)=explode('.', $field);

        $query = $this->CI->db->count($table, [$field => $str]);

		return $query === 0;
    }

	public function min_length($str, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) < $val) ? FALSE : TRUE;
		}

		return (strlen($str) < $val) ? FALSE : TRUE;
	}

	public function max_length($str, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) > $val) ? FALSE : TRUE;
		}

		return (strlen($str) > $val) ? FALSE : TRUE;
	}

	public function exact_length($str, $val)
	{
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) != $val) ? FALSE : TRUE;
		}

		return (strlen($str) != $val) ? FALSE : TRUE;
	}

	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

	public function valid_emails($str)
	{
		if (strpos($str, ',') === FALSE)
		{
			return $this->valid_email(trim($str));
		}

		foreach (explode(',', $str) as $email)
		{
			if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function valid_ip($ip, $which = '')
	{
		return $this->CI->input->valid_ip($ip, $which);
	}

	public function alpha($str)
	{
		return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
	}

	public function alpha_numeric($str)
	{
		return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
	}

	public function alpha_dash($str)
	{
		return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}

	public function numeric($str)
	{
		return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

	}

	public function is_numeric($str)
	{
		return ( ! is_numeric($str)) ? FALSE : TRUE;
	}

	public function integer($str)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
	}

	public function decimal($str)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
	}

	public function greater_than($str, $min)
	{
		if ( ! is_numeric($str))
		{
			return FALSE;
		}
		return $str > $min;
	}

	public function less_than($str, $max)
	{
		if ( ! is_numeric($str))
		{
			return FALSE;
		}
		return $str < $max;
	}

	public function greater_than_equal_to($str, $min)
	{
		return is_numeric($str) ? ($str >= $min) : FALSE;
	}


	public function in_list($value, $list)
	{
		return in_array($value, explode(',', $list), TRUE);
	}

	public function is_natural($str)
	{
		return (bool) preg_match( '/^[0-9]+$/', $str);
	}

	public function is_natural_no_zero($str)
	{
		if ( ! preg_match( '/^[0-9]+$/', $str))
		{
			return FALSE;
		}

		if ($str == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	public function valid_base64($str)
	{
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}

	public function prep_for_form($data = '')
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$data[$key] = $this->prep_for_form($val);
			}

			return $data;
		}

		if ($this->_safe_form_data == FALSE OR $data === '')
		{
			return $data;
		}

		return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
	}

	public function prep_url($str = '')
	{
		if ($str == 'http://' OR $str == '')
		{
			return '';
		}

		if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
		{
			$str = 'http://'.$str;
		}

		return $str;
	}

	public function xss_clean($str)
	{
		return $this->CI->input->xss_clean($str);
	}

	public function encode_php_tags($str)
	{
		return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	}

}