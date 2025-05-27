<?php
return [
    '/' => 'HomeController@index',
    '/admin' => 'AdminController@index',
    '/admin/login' => 'AuthController@login',
    '/auth/login' => 'AuthController@login',
    '/auth/logout' => 'AuthController@logout',
    '/admin/add-plane' => 'AdminController@addPlane',
    '/admin/edit-plane' => 'AdminController@editPlane',
    '/admin/delete-plane' => 'AdminController@deletePlane',
    '/seats' => 'SeatsController@index',
    '/seats/select' => 'SeatsController@select',
    '/route' => 'RouteController@index',
    '/flights/book/{id}' => 'FlightController@book'
];