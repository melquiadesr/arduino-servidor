<?php

$router->get(
    '/',
    function () {
        return "Ok";
    }
);
$router->post('/sensor_data', 'SensorDataController@add');
$router->get('/sensor_data', 'SensorDataController@all');
$router->get('/sensor_data/month/actual', 'SensorDataController@getDataActualMonth');
$router->get('/sensor_data/year/actual', 'SensorDataController@getDataActualYear');
