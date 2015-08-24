<?php

class SummonerController extends Zend_Controller_Action
{

    protected $staticChampions = array();

    public function init()
    {
        $staticChampions = file_get_contents(PROJECT_PATH .'/library/assets/champions.json');
        $staticChampions = json_decode($staticChampions, true);

        foreach($staticChampions['data'] as $champ){
            $this->staticChampions[$champ['key']] = $champ;
        }
    }

    public function indexAction()
    {
//        $serviceRunes = new Service_Runes();
//        $reposne = $serviceRunes->getResponse();
//
        $this->getResponse()
             ->setHeader('content-type', 'application/json')
             ->setHeader('Access-Control-Allow-Origin', '*');

        $name = $this->_getParam('name');
        $data = $this->gameData($name);

        return $this->_helper->json($data);
    }

    public function gameData($name)
    {

        $summoner       = $this->getSummoner($name);
        $currentGame    = $this->getCurrentGame($summoner['summoner_id']);
        $league         = $this->getLeagues($currentGame);
//        $recent         = $this->getRecentStats($summoner['summoner_id'], $currentGame['championId']);
//
//        $data = array (
//            "summonerName" => $summoner['name'],
//            "championName" => $currentGame['championName'],
//            "championPlayed" => $recent['champPrev'],
//            "rank" => $league['tier'] . $league['division'],
//            "masteries" => $currentGame['masteries'],
//            "form" => $recent['won'],
//            "team" => ($currentGame['team'] == '200' ? 'purple' : 'blue')
//        );

        $data = array();

        foreach($league as $key => $recent){

            $recent['championPlayed'] = rand(0,10);
            $recent['form'] = 'bad';
            $recent['summonerId'] = $key;

            if($recent['team'] == 'Blue'){
                $data['summoners']['blue'][] = $recent;
            } elseif($recent['team'] == 'Purple'){
                $data['summoners']['red'][] = $recent;
            }
        }
        $data['summoners']['blue']['bans'] = $currentGame['bans']['Blue'];
        $data['summoners']['red']['bans'] = $currentGame['bans']['Purple'];

        return $data;
    }

    public function getSummoner($name)
    {
        $apiKey = Model_Config::getGlobals('api_key');
        $summoner_db = new Model_Summoners();

        $rawName = rawurlencode($name);

        $summoner = $summoner_db->getSummonerByName($name);

        if (!empty($summoner)){
            return $summoner;
        }

        $curl = new API_Curl();
        $summoner_id = $curl->sendRequest('summoner/by-name/' . $rawName, $apiKey);

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
        $apiKey         = Model_Config::getGlobals('api_key');
        $modRunePages   = new Model_RunePages();

//        $staticRunes = file_get_contents(PROJECT_PATH .'/library/assets/runes.json');
//        $staticRunes = json_decode($staticRunes, true);

        $currRunePages = $modRunePages->getRunePages($id);
        if (!empty($currRunePages)) {
            return $currRunePages;
        }

        $curl   = new API_Curl();
        $runes  = $curl->sendRequest('summoner/' . $id . '/runes', $apiKey);

        $runes = json_decode($runes, true);

        foreach($runes[$id]['pages'] as $runepages){

            $runepage = array(
                'summoner_id'   => $id,
                'date_updated'  => time(),
                'current'       => $runepages['current'],
                'runeset'       => json_encode($runepages['slots']),
                'page_id'       => $runepages['id'],
                'page_name'     => $runepages['name']
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

        $curl = new API_Curl();
        $currentGame = $curl->sendCustom('observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/' . $id, $apiKey);

        $currentGame = json_decode($currentGame, true);

        if (empty($currentGame)){
            throw new Zend_Controller_Action_Exception('This game does not exist', 404);
        }

        $data = array();
        $bannedChamps = $this->getBanned($currentGame);

        $data['bans'] = $bannedChamps;

        foreach ($currentGame['participants'] as $participant){
            if ($participant['championId'] = 223){

                $currChamp = array();
                $currChamp['name'] = 'Tahm Kench';

            } else {
                $currChamp = $this->staticChampions[$participant['championId']];
            }

            $masteries = $this->masteryCheck($participant['masteries']);
            $teamId = $this->getTeam($participant['teamId']);

            $data[$participant['summonerId']] =  array(
                'summonerName'  => $participant['summonerName'],
                'championId'    => $participant['championId'],
                'championName'  => $currChamp['name'],
                'team'          => $teamId,
                'masteries'     => $masteries
            );
        }

        return $data;

    }

    public function getLeagues($ids)
    {
        $apiKey = Model_Config::getGlobals('api_key');
        $curl = new API_Curl();

        unset($ids['bans']);
        $champIds = implode(',', array_keys($ids));
        $leagueResult = $curl->sendCustom('/api/lol/euw/v2.5/league/by-summoner/' . $champIds . '/entry', $apiKey);

        $leagues = json_decode($leagueResult, true);

        foreach($leagues as $key =>$league) {
            $currLeague = $this->teirCheck($league);

            $ids[$key]['league'] = $currLeague;
        }

        return $ids;

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

    /////////////////////////////////////////////////////
                    // Aux Functions //
    ////////////////////////////////////////////////////

    public function getBanned($currentGame)
    {
        $bannedChamps = array();

        foreach($currentGame['bannedChampions'] as $bannedChamp){
            $currChamp = $this->staticChampions[$bannedChamp['championId']];

            $teamBanned = $this->getTeam($bannedChamp['teamId']);
            $bannedChamps[$teamBanned][] = $currChamp['name'];
        }

        return $bannedChamps;
    }

    public function getTeam($team)
    {
        if($team == '100') {
            return 'Blue';
        } elseif($team == '200') {
            return 'Purple';
        }

        return null;
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

        return $highest['tier'] . $highest['division'];
    }
}