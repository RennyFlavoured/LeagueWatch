var services = angular.module('myServices',[]);

services.factory('SummonerDetails', function($http) {
    var summoner = {};

    summoner.getSummoner = function(name, server){
        var url = "https://" + server + ".api.pvp.net/api/lol/" + server + "/v1.4/summoner/by-name/" + name + "?api_key=e94f35ec-71f9-4b70-a443-036518613402";

        return $http.get(url)
            .then(function (response){
                for (var key in response.data) {
                    if (response.data.hasOwnProperty(key)) {
                        return response.data[key];
                    }
                };
            }, function (response){
                var err = new Error('bad status code');
                err.httpStatus = response.status;
                throw err;
            });
    };

    summoner.getFeaturedGame = function(){
        var url = "https://euw.api.pvp.net/observer-mode/rest/featured?api_key=e94f35ec-71f9-4b70-a443-036518613402";

        return $http.get(url)
            .then(function (response){
                return response.data.gameList[0];
            }, function (response){
                var err = new Error('bad status code');
                err.httpStatus = response.status;
                throw err;
            });
    }

    return summoner;
});
