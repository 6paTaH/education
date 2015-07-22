<?php

class AuthController extends Core_Controller_Abstract
{

	private function _registerSession($user)
	{
		$this->session->set('auth', array(
			//@todo придумать что действтивельно нужно храненить в сессии
			'type' => $user->type
		));
	}

	private function _removeSession()
	{
		$this->session->remove('auth');

	}

	public function logoutAction()
	{
		$this->_removeSession();
	}

    public function loginAction()
    {
    	$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_LAYOUT);

    	if (!$this->request->isPost())
    	{
			return ;
    	}

    	// Получение переменных методом POST
    	$login = $this->request->getPost('login');
    	$password = $this->request->getPost('password');

    	// Поиск пользователя в базе данных
    	$user = Core_UserCenter_Table::findFirst(
    		array(
    			"conditions" => "login = '$login' AND password = '$password'"
    		)
    	);
    	if ($user) {

    		$this->_registerSession($user);

	    	$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

    		return $this->dispatcher->forward(array(
    			'controller' => 'index',
    			'action' => 'index'
    		));
    	}
    }

}