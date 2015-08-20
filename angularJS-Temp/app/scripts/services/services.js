var services = angular.module('myServices', []);

services.factory('GameDetails', function ($http, $q){
    var game = {};

    game.getGameOverview = function (server, summonerGame){
        var url = "http://52.11.75.226/Summoner/index/name/" + summonerGame;

        return $http.get(url)
            .then(function (response){
                return [response.data];
            }, function (response){
                var err = new Error('bad status code');
                err.httpStatus = response.status;
                throw err;
            });
    };

    game.getDetailedSummoner = function (server, summonerName){
        return $q.resolve({
            "summonerName":"Tactick",
            "championName":"Ahri",
            "championPlayed":10,
            "rank":"Platinum3",
            "masteries":"21/9/0",
            "form":"bad",
            "team":"blue",
            "summonerSpells": "flash",
            "wins": 900,
            "rankedWinLose": "107/80",
            "previousSeasonRank": "Platinum4",
            "KDA": 9.3
        });
    };

    return game;
});
