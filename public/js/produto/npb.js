var app = angular.module('kettle', ['ngResource'],  
 function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.directive('postsPagination', function(){  
   return{
      restrict: 'E',
      template: '<ul class="pagination">'+
        '<li ng-show="currentPage != 1"><a href="javascript:void(0)" ng-click="getPosts(1)">&laquo;</a></li>'+
        '<li ng-repeat="i in range" ng-class="{active : currentPage == i}">'+
            '<a href="javascript:void(0)" ng-click="getPosts(i)">{{i}}</a>'+
        '</li>'+
        '<li ng-show="currentPage != totalPages"><a href="javascript:void(0)" ng-click="getPosts(totalPages)">&raquo;</a></li>'+
      '</ul>'
   };
});

app.controller('postController', [ '$http', '$scope', function($http, $scope) {
  $scope.posts = [];
  $scope.totalPages = 0;
  $scope.currentPage = 1;
  $scope.range = [];

  $scope.getPosts = function(pageNumber){

    if(pageNumber===undefined){
      pageNumber = '1';
    }
    
    $http({
        method : 'GET',
        url: baseUrl + '/negocio-produto-barra',
        params: {
          'id': codproduto,
          'page': pageNumber,
        }
    }).then(function(response) {
        $scope.posts        = response.data.data;
        $scope.totalPages   = response.data.last_page;
        $scope.currentPage  = response.data.current_page;

        var pages = [];

        for(var i=1; i <= response.data.last_page; i++) {          
          pages.push(i);
        }

        $scope.range = pages; 
    });
  };
}]);    
