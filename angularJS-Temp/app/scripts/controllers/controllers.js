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
                game: function ($route, GameDetails){
                    return GameDetails.getGameOverview($route.current.params.server, $route.current.params.summonerGame);
                },
                detailedSummoner: function ($route, GameDetails){
                    return GameDetails.getDetailedSummoner($route.current.params.server, $route.current.params.detailedSummoner);
                }
            }
        })
        // Summoners game overview (www.leaguewatch.com/TactickGame)
        .when('/:server/:summonerGame', {
            controller: 'gameOverviewController',
            templateUrl: 'scripts/partials/gameOverview.html',
            resolve: {
                game: function ($route, GameDetails){
                    return GameDetails.getGameOverview($route.current.params.server, $route.current.params.summonerGame);
                }
            }
        })
        .when('/testing', {
            controller: 'applicationController',
            templateUrl: 'scripts/partials/summonerSearch.html',
            resolve: {
                test: function (GameDetails){
                    return GameDetails.testing();
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

    //Controller for the entire application, deals with user searching for summoners not in a game
    app.controller("applicationController", function ($scope, $location, GameDetails){
        $scope.$on("$routeChangeError", function (angularEvent, current, previous, rejection) {
            if (rejection.httpStatus === 400) {
                $scope.errorMessage = 'Summoner is not currently in a game';
            } else if (rejection.httpStatus === 500) {
                $scope.errorMessage = 'Unable to contact Riots API service, please try again later';
            };
            $location.url("/");
        });
    });

    //Controller for searching a summoner's current game
    app.controller("summonerSearchController", function ($scope, $location){
        //Defaults server search to euw
        $scope.server = 'euw';

        $scope.submitSummonerSearch = function () {
            $location.url("/" + $scope.server + "/" + $scope.search);
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

// OLD API CALL
// SummonerDetails.getSummoner($scope.search, $scope.server)
//     .then(function(summoner){
//         $scope.summoner = summoner;
//     }, function(err){
//         console.log('nkm:', err.httpStatus);
//         switch (err.httpStatus) {
//             case 404:
//                 $scope.errorMessage = 'Summoner' + $scope.search + 'does not exist on specified server';
//                 break;
//             case 408:
//                 $scope.errorMessage = 'Summoner search has timed out, please try again or contact support at XXXXX';
//                 break;
//             case 503:
//                 $scope.errorMessage = 'Riot API server currently unavailable';
//                 break;
//             default:
//                 $scope.errorMessage = 'Unknown issue has arised with summoner search';
//         }
//     })
//     .finally(function() {
//         $scope.loadingMessage = false;
//     });
