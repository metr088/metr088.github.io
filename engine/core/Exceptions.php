<?php

class Exceptions {
	var $action;
	var $severity;
	var $message;
	var $filename;
	var $line;
	var $ob_level;
	var $levels = array(
						E_ERROR				=>	'Ошибка функций',
						E_WARNING			=>	'Обычное предупреждение',
						E_PARSE				=>	'Ошибка синтаксического анализатора',
						E_NOTICE			=>	'Замечание',
						E_CORE_ERROR		=>	'Ошибка обработчика',
						E_CORE_WARNING		=>	'Предупреждение обработчика',
						E_COMPILE_ERROR		=>	'Ошибка компилятора',
						E_COMPILE_WARNING	=>	'Предупреждение компилятора',
						E_USER_ERROR		=>	'Ошибка пользователя',
						E_USER_WARNING		=>	'Предупреждение пользователя',
						E_USER_NOTICE		=>	'Уведомление пользователя',
						E_STRICT			=>	'Уведомление о времени выполнения'
					);
	public function __construct()
	{
		$this->ob_level = ob_get_level();
	}

	function show_404($page = ''){
		$heading = "Страница не найдена";
		$message = "Запрошенной страницы не существует.";

		echo $this->show_error($heading, $message, 'error_404', 404);
		exit;
	}
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);

		$message = (is_array($message)) ? array($message) : $message;

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(DIR .'/view/error/'.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	function show_php_error($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

		$filepath = str_replace("\\", "/", $filepath);

		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(DIR .'/view/error/error_php.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}


}