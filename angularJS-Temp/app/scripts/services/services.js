var services = angular.module('myServices', []);

services.factory('GameDetails', function ($http, $q){
    var game = {};

    game.getGameOverview = function (server, summonerGame){
        var url = "http://52.11.75.226/Summoner/index/name/" + summonerGame;

        return $http.get(url)
            .then(function (response){
				console.log(reponse);
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
			
		// return $q.resolve({
            // "summoners": [
                // {
                    // "summonerName":"Tactick",
                    // "championName":"Ahri",
                    // "championPlayed":10,
                    // "rank":"Platinum",
                    // "masteries":"21/9/0",
                    // "form":"bad",
                    // "team":"blue"
                // },
                // {
                    // "summonerName":"Helgrimm",
                    // "championName":"Vayne",
                    // "championPlayed":8,
                    // "rank":"Silver",
                    // "masteries":"21/9/0",
                    // "form":"good",
                    // "team":"blue"
                // },
                // {
                    // "summonerName":"Nerdy4tw",
                    // "championName":"Thresh",
                    // "championPlayed":6,
                    // "rank":"Gold",
                    // "masteries":"21/9/0",
                    // "form":"average",
                    // "team":"blue"
                // },
                // {
                    // "summonerName":"ShadowedSun",
                    // "championName":"Quinn",
                    // "championPlayed":2,
                    // "rank":"Silver",
                    // "masteries":"21/0/9",
                    // "form":"average",
                    // "team":"blue"
                // },
                // {
                    // "summonerName":"Aklith",
                    // "championName":"Amumu",
                    // "championPlayed":1,
                    // "rank":"Silver",
                    // "masteries":"0/21/9",
                    // "form":"average",
                    // "team":"blue"
                // }
            // ]
        // });
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
