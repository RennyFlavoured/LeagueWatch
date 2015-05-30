<?php

class HomeController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $name = $this->_getParam('name');

        $resp = $this->populateAction($name);

        $this->view->resp = $resp;

    }

    public function populateAction($name)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        
        $curl->sendRequest('summoner/by-name', $name, $apiKey);
        Model_Log::trace($summoner_id);
        $curl->sendRequest('summoner', $summoner_id, $apiKey);

        $summoner_db = new Model_Summoner();
        $summoner_db->createSummoner($data);



        return $resp;
    }
}