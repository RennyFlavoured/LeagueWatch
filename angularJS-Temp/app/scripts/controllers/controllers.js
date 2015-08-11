(function() {
    var app = angular.module("myApp", ['myServices', 'myDirectives']);

    var players = [
        {summonerName:'Tactick', championName:'Ahri', team:'blue', index:1},
        {summonerName:'Helgrimm', championName:'Vayne', team:'blue', index:2},
        {summonerName:'Aklith', championName:'Pantheon', team:'blue', index:3},
        {summonerName:'Ketesha', championName:'Leona', team:'purple', index:4},
        {summonerName:'Nerdy4tw', championName:'Thresh', team:'purple', index:5}
    ];

    app.controller("startUpController", function($scope, $http, SummonerDetails){
        $scope.search = 'Tactick';
        $scope.server = 'euw';
        $scope.summoner;
        $scope.submitSummonerName = function() {
            $scope.loadingMessage = true;
            SummonerDetails.getSummoner($scope.search, $scope.server)
                .then(function(summoner){
                    $scope.summoner = summoner;
                }, function(err){
                    console.log('nkm:', err.httpStatus);
                    switch (err.httpStatus) {
                        case 404:
                            $scope.errorMessage = 'Summoner' + $scope.search + 'does not exist on specified server';
                            break;
                        case 408:
                            $scope.errorMessage = 'Summoner search has timed out, please try again or contact support at XXXXX';
                            break;
                        case 503:
                            $scope.errorMessage = 'Riot API server currently unavailable';
                            break;
                        default:
                            $scope.errorMessage = 'Unknown issue has arised with summoner search';
                    }
                })
                .finally(function() {
                    $scope.loadingMessage = false;
                });
        };
    });

    app.controller('featuredGame', function($scope, $http, SummonerDetails){
        $scope.game;
        $scope.getFeaturedGame = function () {
            SummonerDetails.getFeaturedGame()
                .then(function(game) {
                    $scope.game = game;
                })
        };
    });

    app.controller("champRepeat", function($scope){
        $scope.players = players;
    });
})();
