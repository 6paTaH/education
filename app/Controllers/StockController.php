<?php

class StockController extends Core_Controller_Abstract
{

    public function manageAction()
    {
    	$this->view->setRenderLevel(Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
    	$this->view->cars = Cars::find();
    }

}