<?php

class SummonerController extends Zend_Controller_Action
{

    const CHALLENGER = 1;
    const DIAMOND = 1;
    const PLATINUM = 1;
    const GOLD = 1;
    const SILVER = 1;
    const BRONZE = 1;

    public function init()
    {
    }

    public function indexAction()
    {
        $name = $this->_getParam('name');

        $data = $this->gameData($name);

        return $this->_helper->json($data);
    }

    public function gameData($name)
    {
        $summoner = $this->getSummoner($name);

        $currentGame = $this->getCurrentGame($summoner['summoner_id']);
        $league = $this->getLeague($summoner['summoner_id']);
        $recent = $this->getRecentStats($summoner['summoner_id'], $currentGame['championId']);
        $data = array (
            "summonerName" => $summoner['name'],
            "championName" => $currentGame['championName'],
            "championPlayed" => $recent['champPrev'],
            "rank" => $league['tier'] . $league['division'],
            "masteries" => $currentGame['masteries'],
            "form" => $recent['won'],
            "team" => ($currentGame['team'] == '200' ? 'purple' : 'blue')
        );
        //$runes = $this->getRunes($summoner['summoner_id']);
        //$masteries = $this->getMasteries($summoner['summoner_id']);



        return $data;

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
        $summoner_db = new Model_Summoners();

        $summoner = $summoner_db->getSummonerByName($name);
        if (!empty($summoner)){
            return $summoner;
        }

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

            $summoner_db->createSummoner($data);
            break;
        }

        return $data;
    }
    
    public function getRunes($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');
        $modRunePages = new Model_RunePages();
        $modRunes = new Model_Runes();

        $staticRunes = file_get_contents(PROJECT_PATH .'/library/assets/runes.json');
        $staticRunes = json_decode($staticRunes, true);

        $currRunePages = $modRunePages->getRunePages($id);
        if (!empty($currRunePages)) {
            return $currRunePages;
        }

        $curl = new API_Curl();
        $runes = $curl->sendRequest('summoner/' . $id . '/runes', $apiKey);

        $runes = json_decode($runes, true);

        foreach($runes[$id]['pages'] as $runepages){



            $runepage = array(
                'summoner_id' => $id,
                'date_updated' => time(),
                'current' => $runepages['current'],
                'runeset' => json_encode($runepages['slots']),
                'page_id' => $runepages['id'],
                'page_name' => $runepages['name']
            );

            $modRunePages->createRunePage($runepage);

        }


    }


    public function getMasteries($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $masteries = $curl->sendRequest('summoner/' . $id . '/masteries', $apiKey);

        $masteries = json_decode($masteries, true);

        Model_Log::trace($masteries);



    }

    public function getCurrentGame($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $staticChampions = file_get_contents(PROJECT_PATH .'/library/assets/champions.json');
        $staticChampions = json_decode($staticChampions, true);


        $curl = new API_Curl();
        $currentGame = $curl->sendCustom('observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/' . $id, $apiKey);

        $currentGame = json_decode($currentGame, true);

        foreach ($currentGame['participants'] as $participant){

            if ($participant['summonerId'] == $id) {

                foreach($staticChampions['data'] as $champ) {
                    if($participant['championId'] == $champ['key']){
                        $champion = $champ['name'];
                        break;
                    }
                }

                $masteries = $this->masteryCheck($participant['masteries']);

                $data =  array(
                    'championId' => $participant['championId'],
                    'championName' => $champion,
                    'team' => $participant['teamId'],
                    'masteries' => $masteries
                );

                return $data;
            }
            continue;
        }

        return null;
    }

    public function getLeague($id)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $league = $curl->sendCustom('/api/lol/euw/v2.5/league/by-summoner/' . $id . '/entry', $apiKey);

        $league = json_decode($league, true);

        $league = $this->teirCheck($league[$id]);

        return $league;

    }

    public function getRecentStats($id, $champ)
    {
        $apiKey = Model_Config::getGlobals('api_key');

        $curl = new API_Curl();
        $data = $curl->sendCustom('/api/lol/euw/v1.3/game/by-summoner/' . $id . '/recent', $apiKey);

        $data = json_decode($data, true);

        $stats = array(
            'champPrev' => 0,
            'won' => 0
        );
        foreach ($data['games'] as $matches) {
            if ($matches['championId'] == $champ){
                $stats['champPrev']++;
            };
            if ($matches['stats']['win'] === true){
                $stats['won']++;
            };
        }

        return $stats;

    }

    public function masteryCheck($masteries)
    {
        $counter = array(
            'r' => 0,
            'b' => 0,
            'g' => 0
        );

        foreach ($masteries as $mastery){
            if ($mastery['masteryId'] < 4211){
                $counter['r'] = $counter['r'] + $mastery['rank'];
            } elseif ($mastery['masteryId'] > 4311){
                $counter['g'] = $counter['g'] + $mastery['rank'];
            } else {
                $counter['b'] = $counter['b'] + $mastery['rank'];
            }
        }
        $format = $counter['r'] . '/' . $counter['b'] . '/' . $counter['g'];

        return $format;
    }

    public function teirCheck($tiers)
    {
        $highest = array(
            'count' => 0,
            'tier' => '',
            'division' => '',
        );



        foreach ($tiers as $tier){
            switch ($tier['tier']) {
                case 'CHALLENGER':
                    if(6 > $highest['count']){
                        $highest['count'] = 6;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
                case 'DIAMOND':
                    if(5 > $highest['count']){
                        $highest['count'] = 5;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
                case 'PLATINUM':
                    if(4 > $highest['count']){
                        $highest['count'] = 4;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
                case 'GOLD':
                    if(3 > $highest['count']){
                        $highest['count'] = 3;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
                case 'SILVER':
                    if(2 > $highest['count']){
                        $highest['count'] = 2;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
                case 'BRONZE':
                    if(1 > $highest['count']){
                        $highest['count'] = 1;
                        $highest['tier'] = $tier['tier'];
                        $highest['division'] = $tier['entries'][0]['division'];
                    };
                break;
            }
        }


        return $highest;
    }
}