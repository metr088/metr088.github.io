<?

class Request{

  protected $_never_allowed_str = array(
    'document.cookie' => '[removed]',
    'document.write'  => '[removed]',
    '.parentNode'   => '[removed]',
    '.innerHTML'    => '[removed]',
    '-moz-binding'    => '[removed]',
    '<!--'        => '&lt;!--',
    '-->'       => '--&gt;',
    '<![CDATA['     => '&lt;![CDATA[',
    '<comment>'     => '&lt;comment&gt;'
  );

  protected $_never_allowed_regex = array(
    'javascript\s*:',
    '(document|(document\.)?window)\.(location|on\w*)',
    'expression\s*(\(|&\#40;)', // CSS and IE
    'vbscript\s*:', // IE, surprise!
    'wscript\s*:', // IE
    'jscript\s*:', // IE
    'vbs\s*:', // IE
    'Redirect\s+30\d:',
    "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
  );

public function post($str = NULL){
if(is_array($str)){
    $data = array();
    foreach ($str as $field) {
        @$data[$field] = $_POST[$field] != '' ? $_POST[$field]:NULL;
    }
   return $data;

  }else{
if(isset($str)){
return @$_POST[$str] != '' ? $_POST[$str]:NULL;
}else{
return @$_POST;
   }
  }
 }

public function get($str = NULL){
if(is_array($str)){
    $data = array();
    foreach ($str as $field) {
        @$data[$field] = $_GET[$field] != '' ? $_GET[$field]:NULL;
    }
   return $data;

  }else{	
if(isset($str)){
return @$_GET[$str] != '' ? $_GET[$str]:NULL;
}else{
return @$_GET;
   }
  }
 }
 public function req($str = NULL){
if(is_array($str)){
    $data = array();
    foreach ($str as $field) {
        @$data[$field] = $_REQUEST[$field] != '' ? $_REQUEST[$field]:NULL;
    }
   return $data;

  }else{
if(isset($str)){
return @$_REQUEST[$str] != '' ? $_REQUEST[$str]:NULL;
}else{
return @$_REQUEST;
   }
  }
 }

 public function toArray($str = []){
 return json_encode($str, JSON_UNESCAPED_UNICODE);
 }

  public function toJson($str = null){
 return json_decode($str, true);
 }


  public function xss_clean($str, $is_image = FALSE)
  {
    if (is_array($str))
    {
      while (list($key) = each($str))
      {
        $str[$key] = $this->xss_clean($str[$key]);
      }

      return $str;
    }

    $str = $this->remove_invisible_characters($str);

    do
    {
      $str = rawurldecode($str);
    }
    while (preg_match('/%[0-9a-f]{2,}/i', $str));

    $str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);

    $str = $this->remove_invisible_characters($str);

    $str = str_replace("\t", ' ', $str);
    $converted_string = $str;

    $str = $this->_do_never_allowed($str);

    if ($is_image === TRUE)
    {
      $str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
    }
    else
    {
      $str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
    }


    $words = array(
      'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
      'vbs', 'script', 'base64', 'applet', 'alert', 'document',
      'write', 'cookie', 'window', 'confirm', 'prompt', 'eval'
    );

    foreach ($words as $word)
    {
      $word = implode('\s*', str_split($word)).'\s*';
      $str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
    }

    do
    {
      $original = $str;

      if (preg_match('/<a/i', $str))
      {
        $str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
      }

      if (preg_match('/<img/i', $str))
      {
        $str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
      }

      if (preg_match('/script|xss/i', $str))
      {
        $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
      }
    }
    while($original !== $str);
    unset($original);

    $pattern = '#'
      .'<((?<slash>/*\s*)(?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)' // tag start and name, followed by a non-tag character
      .'[^\s\042\047a-z0-9>/=]*' // a valid attribute character immediately after the tag would count as a separator
      // optional attributes
      .'(?<attributes>(?:[\s\042\047/=]*' // non-attribute characters, excluding > (tag close) for obvious reasons
      .'[^\s\042\047>/=]+' // attribute characters
      // optional attribute-value
        .'(?:\s*=' // attribute-value separator
          .'(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*))' // single, double or non-quoted value
        .')?' // end optional attribute-value group
      .')*)' // end optional attributes group
      .'[^>]*)(?<closeTag>\>)?#isS';

    do
    {
      $old_str = $str;
      $str = preg_replace_callback($pattern, array($this, '_sanitize_naughty_html'), $str);
    }
    while ($old_str !== $str);
    unset($old_str);

    $str = preg_replace(
      '#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
      '\\1\\2&#40;\\3&#41;',
      $str
    );

    $str = $this->_do_never_allowed($str);

    if ($is_image === TRUE)
    {
      return ($str === $converted_string);
    }
    return $str;
  }


  protected function _compact_exploded_words($matches)
  {
    return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
  }


  protected function _sanitize_naughty_html($matches)
  {
    static $naughty_tags    = array(
      'alert', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
      'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
      'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
      'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'
    );

    static $evil_attributes = array(
      'on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'
    );

    // First, escape unclosed tags
    if (empty($matches['closeTag']))
    {
      return '&lt;'.$matches[1];
    }
    // Is the element that we caught naughty? If so, escape it
    elseif (in_array(strtolower($matches['tagName']), $naughty_tags, TRUE))
    {
      return '&lt;'.$matches[1].'&gt;';
    }
    // For other tags, see if their attributes are "evil" and strip those
    elseif (isset($matches['attributes']))
    {
      // We'll store the already fitlered attributes here
      $attributes = array();

      // Attribute-catching pattern
      $attributes_pattern = '#'
        .'(?<name>[^\s\042\047>/=]+)' // attribute characters
        // optional attribute-value
        .'(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' // attribute-value separator
        .'#i';

      // Blacklist pattern for evil attribute names
      $is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';

      // Each iteration filters a single attribute
      do
      {
        // Strip any non-alpha characters that may preceed an attribute.
        // Browsers often parse these incorrectly and that has been a
        // of numerous XSS issues we've had.
        $matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

        if ( ! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE))
        {
          // No (valid) attribute found? Discard everything else inside the tag
          break;
        }

        if (
          // Is it indeed an "evil" attribute?
          preg_match($is_evil_pattern, $attribute['name'][0])
          // Or does it have an equals sign, but no value and not quoted? Strip that too!
          OR (trim($attribute['value'][0]) === '')
        )
        {
          $attributes[] = 'xss=removed';
        }
        else
        {
          $attributes[] = $attribute[0][0];
        }

        $matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
      }
      while ($matches['attributes'] !== '');

      $attributes = empty($attributes)
        ? ''
        : ' '.implode(' ', $attributes);
      return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
    }

    return $matches[0];
  }


  protected function _js_link_removal($match)
  {
    return str_replace(
      $match[1],
      preg_replace(
        '#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
        '',
        $this->_filter_attributes($match[1])
      ),
      $match[0]
    );
  }

  protected function _js_img_removal($match)
  {
    return str_replace(
      $match[1],
      preg_replace(
        '#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
        '',
        $this->_filter_attributes($match[1])
      ),
      $match[0]
    );
  }

  protected function _convert_attribute($match)
  {
    return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
  }

  protected function _filter_attributes($str)
  {
    $out = '';

    if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
    {
      foreach ($matches[0] as $match)
      {
        $out .= preg_replace("#/\*.*?\*/#s", '', $match);
      }
    }

    return $out;
  }


  protected function _do_never_allowed($str)
  {
    $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

    foreach ($this->_never_allowed_regex as $regex)
    {
      $str = preg_replace('#'.$regex.'#is', '[removed]', $str);
    }

    return $str;
  }

  protected function remove_invisible_characters($str, $url_encoded = TRUE)
  {
    $non_displayables = array();
    
    // every control character except newline (dec 10)
    // carriage return (dec 13), and horizontal tab (dec 09)
    
    if ($url_encoded)
    {
      $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
      $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
    }
    
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

    do
    {
      $str = preg_replace($non_displayables, '', $str, -1, $count);
    }
    while ($count);

    return $str;
  }



  public function valid_ip($ip, $which = '')
  {
    $which = strtolower($which);

    if (is_callable('filter_var'))
    {
      switch ($which) {
        case 'ipv4':
          $flag = FILTER_FLAG_IPV4;
          break;
        case 'ipv6':
          $flag = FILTER_FLAG_IPV6;
          break;
        default:
          $flag = '';
          break;
      }

      return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
    }

    if ($which !== 'ipv6' && $which !== 'ipv4')
    {
      if (strpos($ip, ':') !== FALSE)
      {
        $which = 'ipv6';
      }
      elseif (strpos($ip, '.') !== FALSE)
      {
        $which = 'ipv4';
      }
      else
      {
        return FALSE;
      }
    }

    $func = '_valid_'.$which;
    return $this->$func($ip);
  }

  protected function _valid_ipv4($ip)
  {
    $ip_segments = explode('.', $ip);

    // Always 4 segments needed
    if (count($ip_segments) !== 4)
    {
      return FALSE;
    }
    // IP can not start with 0
    if ($ip_segments[0][0] == '0')
    {
      return FALSE;
    }

    // Check each segment
    foreach ($ip_segments as $segment)
    {
      // IP segments must be digits and can not be
      // longer than 3 digits or greater then 255
      if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
      {
        return FALSE;
      }
    }

    return TRUE;
  }

  protected function _valid_ipv6($str)
  {
    // 8 groups, separated by :
    // 0-ffff per group
    // one set of consecutive 0 groups can be collapsed to ::

    $groups = 8;
    $collapsed = FALSE;

    $chunks = array_filter(
      preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
    );

    // Rule out easy nonsense
    if (current($chunks) == ':' OR end($chunks) == ':')
    {
      return FALSE;
    }

    // PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
    if (strpos(end($chunks), '.') !== FALSE)
    {
      $ipv4 = array_pop($chunks);

      if ( ! $this->_valid_ipv4($ipv4))
      {
        return FALSE;
      }

      $groups--;
    }

    while ($seg = array_pop($chunks))
    {
      if ($seg[0] == ':')
      {
        if (--$groups == 0)
        {
          return FALSE; // too many groups
        }

        if (strlen($seg) > 2)
        {
          return FALSE; // long separator
        }

        if ($seg == '::')
        {
          if ($collapsed)
          {
            return FALSE; // multiple collapsed
          }

          $collapsed = TRUE;
        }
      }
      elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4)
      {
        return FALSE; // invalid segment
      }
    }

    return $collapsed OR $groups == 1;
  }

}

?>