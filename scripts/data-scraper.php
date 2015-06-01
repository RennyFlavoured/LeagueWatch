#!/usr/bin/php
<?php
// Stop app from running
define('DONT_RUN_APP', true);

// Get application and bootstrap resources
require(realpath(dirname(__FILE__) . '/../public/index.php'));

// Tidyup
$_SERVER['SERVER_NAME'] = gethostname();

$config = $application->bootstrap()->getOptions();
$application->bootstrap('date');
$application->bootstrap('autoload');

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'dbname'   => 'lw_static'
));

$staticData = array(
    'champion',
    'item',
    'mastery',
    'rune',
    'summoner_spell'
);   

    $curl = new API_Curl(); 

foreach ($staticData as $statics){
    try {

        switch ($statics) {

// --------- Populate the champion static data from the Riot DataDragon
            case 'champion':
                $getChampIds = $curl->sendDDRequest($statics, NULL, NULL);
                $getChampIds = json_decode($getChampIds, true);

                $unwanteds = array('tags','partype','enemytips','recommended','spells','blurb','allytips','lore','passive');

                foreach ($getChampIds['data'] as $champIds){

                    $getChampData = $curl->sendDDRequest($statics, $champIds['id'], 'champData=all&');
                    $getChampData = json_decode($getChampData, true);

                    foreach ($unwanteds as $unwanted){
                        unset($getChampData[$unwanted]);
                    }

                    $champData = array(
                        'id' => $getChampData['id'],
                        'key' => $getChampData['key'],
                        'name' => $getChampData['name'],
                        'title' => $getChampData['title'],
                        'image' => json_encode($getChampData['image']),
                        'skins' => json_encode($getChampData['skins']),
                        'info' => json_encode($getChampData['info']),
                        'stats' => json_encode($getChampData['stats'])
                    );
                    

                    $db->insert('champion', $champData);

                }
                break;


            case 'mastery':

                break;


            case 'rune':

                break;


            case 'summoner_spell':

                break;


        }
    

    } catch (Exception $e) {
        var_dump($e);
    }
}
