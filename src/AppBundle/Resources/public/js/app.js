'use strict';

angular.module('myApp', ['ngRoute','ngResource'])

.config(function($routeProvider, $locationProvider, $httpProvider){

    $routeProvider.when('/posts', {templateUrl: 'bundles/app/tpl/posts.html', controller: 'postsController'});
    $routeProvider.when('/users', {templateUrl: 'bundles/app/tpl/users.html', controller: 'usersController'});

    $httpProvider.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    $httpProvider.defaults.transformRequest.unshift(function (data, headersGetter) {

    return data;
  });

})
.factory('Posts', function($resource){
    return $resource(Routing.generate('posts')+'/:id',{
        save: {
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }
    });
})
.factory('Users', function($resource){
    return $resource('http://jsonplaceholder.typicode.com/users/:user',{user: "@user"});
})
.controller('postsController', function($scope, Posts){
    $scope.title = 'Posts';

    $scope.posts = Posts.query();

    $scope.save = function(post){

        Posts.save($.param({'posts': post}), function(response){
            $scope.posts = Posts.query();
        });

    };

    $scope.delete = function(id){
        Posts.delete({id: id}, function(){
            $scope.posts = Posts.query();
        })
    };
})
.controller('usersController', function($scope, Users){
    $scope.title = 'Users';
    $scope.users = Users.query();


    Users.save({name: 'Cristian Angulo Nova', email: 'cristianangulonova@gmail.com'}, function(data){
        console.log
    });
})
