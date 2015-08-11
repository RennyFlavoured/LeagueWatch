var directives = angular.module('myDirectives',[]);

directives.directive('lwSummonerDetails', function(){
    return {
        restrict: 'E',
        scope: {
            name: '=',
            champ: '=championName'
        },
        templateUrl: 'scripts/partials/summonerDetails.html'
    };
});
