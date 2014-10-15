﻿angular.module('app').service('account', ['api', '$q', '$localStorage', function (api, $q, $localStorage) {
    'use strict';
    var token;
    var service = {
        /**
         Attempts to get an authentication token from the server. If remember me is provided, the token is saved in local storage
         and is used in future page loads. If false, the user is only logged in for this session
         */
        logIn: function (email, password, rememberMe) {
            //log out whoever is currently logged in
            service.logOut();
            
            var deferred = $q.defer();
            api.users.token({ email: email, password: password }, function (authToken, b, c) {
                debugger;
                if (rememberMe === true) {
                    $localStorage.token = authToken;
                } else {
                    token = authToken;
                }
                
                //retrieve the user data from the api so we have it for the remainder of the session
                service.user = api.users.get({ userId: service.token().userId });
                deferred.resolve(true);
            }, function (a, b, c) {
                return deferred.reject(false);
            });
            return deferred.promise;
        },
        /**
         Logs the current user out 
         */
        logOut: function () {
            debugger;
            $localStorage.token = undefined;
            token = undefined;
            service.user = undefined;
            try {
                delete $localStorage.token;
            } catch (e) { }
        },
        /**
         * Determines if there is a currently logged in user
         */
        isLoggedIn: function () {
            return service.token() !== undefined;
        },
        /**
         * Retrieves the token, if one exists
         */
        token: function () {
            return $localStorage.token || token;
        },
        user: undefined
    };
    
    //if the user is already logged in, retrieve their information
    if (service.isLoggedIn()) {
        service.user = api.users.get({ userId: service.token().userId });
    }
    return service;
}]); 