<?php

class Core_Exception_Null extends Core_Exception
{

    use Core_Singleton;

	/**
	 * @param object $var
	 * @param string $strMessage
	 * @throws self
	 */
	public function validate($var, $strMessage = 'Object is NULL.', $code = 501)
	{
		if (is_null($var) || !$var)
		{
			throw new self($strMessage, $code);
		}
	}

}