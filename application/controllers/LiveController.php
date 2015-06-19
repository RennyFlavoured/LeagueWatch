<?php

class LiveController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $id = $this->getRequest()->getParam('summonerId');

        $apiKey = Model_Config::getGlobals('api_key');
        $searchString = sprintf('EUW1/%s', $id);

        $curl = new API_Curl();
        $gameData = $curl->sendCustom('observer-mode/rest/consumer/getSpectatorGameInfo/' . $searchString, $apiKey);

        $this->_helper->json($gameData, true);
    }

}