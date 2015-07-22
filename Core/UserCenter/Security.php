<?php

class Core_UserCenter_Security extends Phalcon\Mvc\User\Plugin
{

	private function _getAcl()
	{
		$this->persistent->destroy();
		if (!isset($this->persistent->acl))
		{
			$acl = new Phalcon\Acl\Adapter\Memory();
			$acl->setDefaultAction(Phalcon\Acl::DENY);
			//Register roles
			$roles = array(
				Core_UserCenter_Enum::ADMIN  => new Phalcon\Acl\Role(Core_UserCenter_Enum::ADMIN),
				Core_UserCenter_Enum::USERS  => new Phalcon\Acl\Role(Core_UserCenter_Enum::USERS),
				Core_UserCenter_Enum::GUESTS => new Phalcon\Acl\Role(Core_UserCenter_Enum::GUESTS)
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			//Private area resources
			$privateResources = array(
				'index'		=> array('index'),
				'stock'		=> array('manage'),
				'auth'      => array('logout'),
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			//Public area resources
			$publicResources = array(
				'auth'      => array('login'),
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($publicResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('*', $resource, $action);
				}
			}
			//Grant acess to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow(Core_UserCenter_Enum::USERS, $resource, $action);
					$acl->allow(Core_UserCenter_Enum::ADMIN, $resource, $action);
				}
			}
			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}
		return $this->persistent->acl;
	}

	public function beforeExecuteRoute(Phalcon\Events\Event $event, Phalcon\Mvc\Dispatcher $dispatcher)
    {
        // Получаем активные контроллер и действие от диспетчера
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        // Получаем список ACL
        $acl = $this->_getAcl();

        if ($acl->isAllowed(Core_UserCenter_Enum::GUESTS, $controller, $action)) return TRUE;

        // Проверяем, установлена ли в сессии переменная "auth" для определения активной роли.
        $auth = $this->session->has('auth');

        if (!$auth)
        {
        	return $this->_forwardToLogin();
        }

        $role = $this->session->get('auth')['type'];
        // Проверяем, имеет ли данная роль доступ к контроллеру (ресурсу)
        $allowed = $acl->isAllowed($role, $controller, $action);

        if ($allowed != Phalcon\Acl::ALLOW)
        {
            throw new Core_UserCenter_Exception_AccessDenied($controller, $action, $role);
        }
    }

    private function _forwardToLogin()
    {
		// Если доступа нет, перенаправляем его на контроллер "index".
		$this->dispatcher->forward(
			array(
				'controller' => 'auth',
				'action' => 'login'
			)
		);

		// Возвращая "false" мы приказываем диспетчеру прекратить текущую операцию
		return FALSE;
    }

}