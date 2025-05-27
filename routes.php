<?php

return [
    // Головна сторінка
    'GET|/' => 'HomeController@index',
    
    // Маршрути для рейсів
    'GET|/flights' => 'FlightsController@index',
    'GET|/flights/search' => 'FlightsController@search',
    'GET|/flights/view/{id}' => 'FlightsController@view',
    'GET|/flights/update' => 'FlightsController@updateFlights',
    
    // Маршрути для бронювань
    'GET|/booking' => 'BookingController@index',
    'GET|/booking/create' => 'BookingController@create',
    'POST|/booking/store' => 'BookingController@store',
    'GET|/booking/details/{id}' => 'BookingController@details',
    'GET|/booking/cancel/{id}' => 'BookingController@cancel',

    // ... інші маршрути ...
]; 