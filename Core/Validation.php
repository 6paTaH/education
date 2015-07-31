<?php

class Core_Validation extends Phalcon\Validation
{

	/**
	 * Выполняется после валидации
	 *
	 * @param array $data
	 * @param object $entity
	 * @param Phalcon\Validation\Message\Group $messages
	 */
	public function afterValidation($data, $entity, $messages)
	{
		if (count($messages))
		{

		}
// 		var_dump(count($messages));
// 		die();
	}

}