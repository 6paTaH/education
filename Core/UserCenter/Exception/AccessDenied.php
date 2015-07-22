<?php
class Core_UserCenter_Exception_AccessDenied extends Core_Exception
{
	public function __construct($controller, $action, $role)
	{
		parent::__construct("For the role '$role' access denied to controller '$controller' in action '$action'");
	}
}