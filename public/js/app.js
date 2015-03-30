/**
 * Created by bozh on 3/23/15.
 */


var app = angular.module('Snippets', []);

app.controller('user', ['$scope', function($scope){
    'use strict';
    $scope.passHint = false;
}]);

app.controller('editSnippet', ['$scope', function($scope){
    $scope.newTag = '';
    $scope.tags = [];

    $scope.deleteTag = function(tag){
        $scope.tags = _.without($scope.tags, tag);
    };


}]);

app.directive('tags', function(){
   'use strict';
    return {
        'restrict': 'A',
        scope: {
            tags: '=',
            newTag: '=',
            rmfn: '&'
        },
        template:'<div class="tagOnEdit" ng-repeat="tag in tags" ng-click="rmfn({tag: tag})">{{tag}}</div>',
        link: function(scope, element, attrs){
            scope.$watch('newTag', function(newVal){
                var tag;
                if(newVal.match(',')){
                    tag = newVal.replace(/^([^,]+),.*$/, '$1');
                    if(scope.tags.indexOf(tag) === -1){
                        scope.tags.push(tag);
                    }
                    scope.newTag = '';
                }
                
            });
        }
    }
});

app.directive('time', function(){
    'use strict';

    return {
        restrict: 'E',
        scope: {local: '@'},
        template : '{{time}}',
        link: function(scope){
            var date = new Date(parseInt(scope.local + '000')),
                dd, mm, yyyy, hh, ii;
            dd = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
            mm = date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
            yyyy = date.getFullYear();
            hh = date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
            ii = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
            scope.time = dd + '.' + mm + '.' + yyyy + ' ' + hh + ':' + ii;
        }
    }
});