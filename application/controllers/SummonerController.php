<?php

class SummonerController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        $name = $this->_getParam('name');

        $data = $this->populateAction($name);

        return $this->_helper->json($data);
    }

    public function populateAction($name)
    {
        $summoner = $this->getSummoner($name);
        $runes = $this->getRunes($summoner['summoner_id']);
        //$masteries = $this->getMasteries($summoner['summoner_id']);
        //$league = $this->getLeague($summoner['summoner_id']);

        $data = 'success';

        return $data;

    }
    public function getSummoner($name)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $summoner_id = $curl->sendRequest('summoner/by-name/' . $name, $apiKey);

        $summoner_id = json_decode($summoner_id, true);

        foreach ($summoner_id as $summoner_data){

            $data = array(
                'summoner_id'   => $summoner_data['id'],
                'date_created'  => time(),
                'name'          => $summoner_data['name'],
                'level'         => $summoner_data['summonerLevel'],
                'avatar'        => $summoner_data['profileIconId'],
            );

            //$summoner_db = new Model_Summoners();
            //$summoner_db->createSummoner($data);
            break;
        }

        return $data;
    }
    
    public function getRunes($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');
        //$modRunePages = new Model_RunePages();
        //$modRunes = new Model_Runes();

        $staticRunes = file_get_contents(PROJECT_PATH .'/library/assets/runes.json');
        $staticRunes = json_decode($staticRunes, true);

        $curl = new API_Curl();
        $runes = $curl->sendRequest('summoner/' . $id . '/runes', $apiKey);

        $runes = json_decode($runes, true);

        foreach($runes[$id]['pages'] as $runepages){

            $runepage = array(
                'summmoner_id' => $id,
                'date_updated' => time(),
                'current' => $runepages['current'],
                'page_id' => $runepages['id'],
                'page_name' => $runepages['name']
            );

            //$modRunePages->createRunePage($runepage);
//mongoDb !!!!!
            foreach($runepages['slots'] as $runes){
                $detail = $staticRunes['data'][$runes['runeId']];
            }
        }

        Model_Log::debug($runes);
    }


    public function getMasteries($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $masteries = $curl->sendRequest('summoner/' . $id . '/masteries', $apiKey);

        $masteries = json_decode($masteries, true);

        Model_Log::trace($masteries);



    }

    public function getLeague($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $league = $curl->sendCustom('/api/lol/euw/v2.5/league/by-summoner/' . $id, $apiKey);

        $league = json_decode($league, true);


        Model_Log::trace($league);



    }
}