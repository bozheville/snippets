/**
 * Created by bozh on 3/23/15.
 */


var app = angular.module('Snippets', []);

app.controller('user', ['$scope', function($scope){
    $scope.passHint = false;
}]);