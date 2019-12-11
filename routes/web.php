<?php

$router->get(
    '/',
    function () {
        for ($h = 8; $h < 20; $h++ ) {
            $s = 5;
            if ($h > 20) {
                break;
            }
            for ($i=0; $i < 60; $i++) {
                if ($h == 20 && $i > 20) {
                    break;
                }
                $data = new \App\Models\SensorData();
                $liters = rand(0, 10) / 8;
                $liters = $h > 8 ? $liters : 0;
                $averageFlow = $liters/60;
                $hh = str_pad($h , 2 , '0' , STR_PAD_LEFT);
                $ii = str_pad($i , 2 , '0' , STR_PAD_LEFT);
                $ss = str_pad($s , 2 , '0' , STR_PAD_LEFT);
                $dateTime = date("Y-m-d {$hh}:{$ii}:00", strtotime('-1 day'));
                $sendTime = date("Y-m-d {$hh}:{$ss}:00", strtotime('-1 day'));
                $data->date_time = $dateTime;
                $data->liters = $liters;
                $data->average_flow = $averageFlow;
                $data->setCreatedAt($sendTime);
                $data->save();
                if ($i == $s) {
                    $s += 5;
                }
            }
        }
        return "";
    }
);
$router->get(
    '/test1',
    function () {
        for ($h = 5; $h < 8; $h++ ) {
            $s = 5;
            if ($h > 20) {
                break;
            }
            for ($i=0; $i < 60; $i++) {
                if ($h == 20 && $i > 20) {
                    break;
                }
                $data = new \App\Models\SensorData();
                $liters = rand(0, 10) / 8;
                $liters = $h > 8 ? $liters : 0;
                $averageFlow = $liters/60;
                $hh = str_pad($h , 2 , '0' , STR_PAD_LEFT);
                $ii = str_pad($i , 2 , '0' , STR_PAD_LEFT);
                $ss = str_pad($s , 2 , '0' , STR_PAD_LEFT);
                $dateTime = date("Y-m-d {$hh}:{$ii}:00", strtotime('-1 day'));
                $sendTime = date("Y-m-d {$hh}:{$ss}:00", strtotime('-1 day'));
                $data->date_time = $dateTime;
                $data->liters = $liters;
                $data->average_flow = $averageFlow;
                $data->setCreatedAt($sendTime);
                $data->save();
                if ($i == $s) {
                    $s += 5;
                }
            }
        }
        return "";
    }
);
$router->post('/sensor_data', 'SensorDataController@add');
$router->get('/sensor_data', 'SensorDataController@all');
$router->get('/sensor_data/month/actual', 'SensorDataController@getDataActualMonth');
$router->get('/sensor_data/year/actual', 'SensorDataController@getDataActualYear');
