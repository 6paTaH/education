<?php

class AuthController extends Core_Controller_Abstract
{

	private function _registerSession($user)
	{
		$this->session->set('auth_type', array(
			'type' => $user->type,
		));
		$this->session->set('auth_hash', array(
			'hash' => $user->hash
		));
	}

	private function _removeAllSession()
	{
		$this->_removeTypeSession();
		$this->_removeHashSession();
	}

	private function _removeTypeSession()
	{
		$this->session->remove('auth_type');
	}

	private function _removeHashSession()
	{
		$this->session->remove('auth_hash');
	}

	private function _generateHash()
	{
		return md5(rand(100000, 999999));
	}

	private function _writeHash($user)
	{
		$user->hash = $this->_generateHash();
		$user->save();

		return $user;
	}

	private function _authorization($user)
	{
		$this->_writeHash($user);

		$this->_registerSession($user);

	}

	public function logoutAction()
	{
		$this->_removeTypeSession();

		return $this->response->redirect();
	}

    public function loginAction()
    {
    	$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_LAYOUT);

    	if ($this->session->has('auth_hash'))
    	{
			$hash = $this->session->get('auth_hash')['hash'];
    		$user =  Core_UserCenter_Table::findFirst(
	    		array(
	    			"conditions" => "hash = '$hash'"
	    		)
	    	);

    		if (!$user)
    		{
    			$this->_removeAllSession();
    			return ;
    		}

    		$this->view->pick('auth/locked');
    		$this->view->user = $user;
    	}

    	if (!$this->request->isPost())
    	{
			return ;
    	}
    	$validation = new Core_Validation();

    	$validation->add('login', new \Phalcon\Validation\Validator\PresenceOf());

    	$messages = $validation->validate($this->request->getPost());

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

    		$this->_authorization($user);

	    	$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

    		return $this->response->redirect();
    	}
    }

    public function switchAction()
    {
    	$this->_removeAllSession();

    	return $this->response->redirect();
    }
}