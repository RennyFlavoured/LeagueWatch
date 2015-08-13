(function() {
    var app = angular.module("myApp", ['myServices', 'myDirectives', 'ngRoute']);

    //setting up mappings between URLs, templates and controllers, resolve API calls for summoners in game/summoner details
    app.config(function emailRouteConfig($routeProvider){
        $routeProvider
        // Detailed summoner information, includes summoners game overview (www.leaguewatch.com/TactickGame/Tactick)
        .when('/:server/:summonerGame/:detailedSummoner', {
            controller: 'DetailedController',
            templateUrl: 'scripts/partials/gameOverview.html',
            resolve: {
                game: function ($routeParams, GameDetails){
                    return GameDetails.getGameOverview($routeParams.server, $routeParams.summonerGame);
                },
                detailedSummoner: function ($routeParams, GameDetails){
                    return GameDetails.getDetailedSummoner($routeParams.server, $routeParams.detailedSummoner);
                }
            }
        })
        // Summoners game overview (www.leaguewatch.com/TactickGame)
        .when('/:server/:summonerGame', {
            controller: 'gameOverviewController',
            templateUrl: 'scripts/partials/gameOverview.html',
            resolve: {
                game: function ($routeParams, GameDetails){
                    return GameDetails.getGameOverview($routeParams.server, $routeParams.summonerGame);
                }
            }
        })
        // Search page (www.leaguewatch.com)
        .otherwise({
            redirectTo: '/',
            controller: 'summonerSearchController',
            templateUrl: 'scripts/partials/summonerSearch.html'
        });
    });

    //FOR RESOLVING IF A USER ENTERS A URL WITH A SUMMONER NAME OR GAME OVERVIEW, REDIRECTS
    //Run Block, add event listener
    //Inject route scope
    //$routeChangeError
    //LocationService

    //Controller for searching a summoner's current game
    app.controller("summonerSearchController", function ($scope){
        //Defaults server search to euw
        $scope.server = 'euw';

        $scope.submitSummonerSearch = function () {
            window.location.href = "#/" + $scope.server + "/" + $scope.search;
        };
    });

    app.controller('gameOverviewController', function ($scope, $routeParams, game){
        //$routeParams required for URL creation within partial
        $scope.summonerGame = $routeParams.summonerGame;
        $scope.server = $routeParams.server;
        $scope.summoners = game.summoners;
    });

    app.controller("DetailedController", function ($scope, $routeParams, game, detailedSummoner){
        //$routeParams required for URL creation within partial
        $scope.summonerGame = $routeParams.summonerGame;
        $scope.server = $routeParams.server;
        $scope.summoners = game.summoners;
        $scope.detailedSummoner = detailedSummoner;
    });
})();
