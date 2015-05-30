<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
         $id = $this->getRequest()->getParam('name');
 $this->view->id= $id;
        file_put_contents('/tmp/file.txt', $id);
    }

    public function populateAction()
    {
        $request = $this->getRequest();
    }
}