<?php

namespace ProGest\Twig;

class CallFuncExtension extends \Twig\Extension\AbstractExtension
{
	public function callfunc(string $func, $params = null)
	{
		if (strpos($func, '::') !== false)
			$func = explode('::', $func);

		if (!is_callable($func))
			throw new \Exception("Error! Function is not callable.");

		if ($params == null)
			$params = array();

		if (!is_array($params))
			$params = array($params);

		$result = call_user_func_array($func, $params);

		return $result;
	}

	public function getFunctions()
	{
		return array(
			new \Twig\TwigFunction('callfunc', [$this, 'callfunc']),
		);
	}

	public function getName()
	{
		return 'callfunc_extension';
	}
}