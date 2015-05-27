<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        file_put_contents('/tmp/file.txt', 'bob');
    }

    public function populateAction()
    {
        $request = $this->getRequest();
    }
}