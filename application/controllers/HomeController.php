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

        $name = $this->_getParam('name');
        $apiKey = Zend_Registry::get('config')->api_key;

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => sprintf('https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/%s?api_key=%s', $name, $apiKey),
            CURLOPT_USERAGENT       => 'LeagueWatch API'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        $this->view->resp = $resp;

    }

    public function populateAction()
    {
        $request = $this->getRequest();
    }
}