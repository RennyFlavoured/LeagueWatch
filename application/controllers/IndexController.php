<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('login');
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Summonersearch();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();

                return $this->_redirect($this->view->url(array('controller' => 'Summoner', 'name' => $values['summonerName']), null, true));
            }
        }

        $this->view->form = $form;
    }


}

