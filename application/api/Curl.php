<?php

class API_Curl 
{
    public  function sendRequest($url, $data, $apiKey)
	{

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => sprintf('https://euw.api.pvp.net/api/lol/euw/v1.4/%s/%s?api_key=%s', $url, $data, $apiKey),
            CURLOPT_USERAGENT       => 'LeagueWatch API'
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        Model_Log::trace($resp);

		return $resp;
	}
}
