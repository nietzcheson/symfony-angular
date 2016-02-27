'use strict';

angular.module('myApp', ['ngRoute','ngResource'])

.config(function($routeProvider, $locationProvider){

    $routeProvider.when('/posts', {templateUrl: 'bundles/app/tpl/posts.html', controller: 'postsController'});
    $routeProvider.when('/users', {templateUrl: 'bundles/app/tpl/users.html', controller: 'usersController'});

})
.service('Posts_', function($http){
    this.posts = function(success){
        $http.post('/app_dev.php/'+Routing.generate('posts_new')).success(success);
    }
})
.factory('Posts', function($resource){
    return $resource(Routing.generate('posts')+'/:id','');
})
.controller('postsController', function($scope, Posts, Posts_){
    $scope.title = 'Posts';

    // Posts.posts(function(data){
    //     $scope.posts = data;
    // });

    var post = Posts.get({ id: 1});

    console.log('/app_dev.php/'+Routing.generate('posts_new'));

    $scope.posts = Posts.query();

    $scope.save = function(post){

        var record = new Posts();

        //record.posts = ['posts', $scope.post];
        record.posts = $scope.post;

        Posts.save({'posts': $scope.post});

        // console.log(record.posts);
        // record.$save(function(response){
        //     console.log(response);
        //     $scope.posts.push(post);
        // });

    };

    $scope.delete = function(id){
        Posts.delete({id: id}, function(){
            $scope.posts = Posts.query();
        })
    };
})
.controller('usersController', function($scope){
    $scope.title = 'Users';
})
