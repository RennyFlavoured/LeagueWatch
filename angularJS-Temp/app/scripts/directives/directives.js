var directives = angular.module('myDirectives',[]);

directives.directive('lwSummonerDetails', function(){
    return {
        restrict: 'E',
        scope: {
            summoner: '=',
            summonerGame: '=',
            server: '='
        },
        templateUrl: 'scripts/partials/summonerOverview.html'
    };
});
