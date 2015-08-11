<?php

class SummonerController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $name = $this->_getParam('name');

        $summoner_db = new Model_Summoners();
        $summoner = $summoner_db->getSummonerByName($name);

        if (empty($summoner)) {
            $summoner = $this->populateAction($name);
        };

        setcookie('summoner', $summoner['summoner_id'], time()+7200);

        $this->_helper->json($summoner, true);

    }

    public function populateAction($name)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $summoner_id = $curl->sendRequest('summoner/by-name', $name, $apiKey);
        //$summoner_data = $curl->sendRequest('summoner', $summoner_id, $apiKey);

        $summoner_id = json_decode($summoner_id, true);

        foreach ($summoner_id as $summoner_data){

            $data = array(
                'summoner_id'   => $summoner_data['id'],
                'date_created'  => time(),
                'name'          => $summoner_data['name'],
                'level'         => $summoner_data['summonerLevel'],
                'avatar'        => sprintf('http://ddragon.leagueoflegends.com/cdn/5.2.1/img/profileicon/%s.png', $summoner_data['profileIconId']),
            );

            $summoner_db = new Model_Summoners();
            $summoner_db->createSummoner($data);
            break;
        }

        return $data;

    }
}