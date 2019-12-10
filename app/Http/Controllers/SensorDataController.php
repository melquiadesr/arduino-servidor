<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class SensorDataController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        if ($request->input("initializing")) {
            $sensorData = new SensorData();
            $sensorData->date_time = date('Y-m-d H:i:s');
            $sensorData->liters = 0;
            $sensorData->average_flow = 0;
            $sensorData->save();
        } elseif ($request->input('data')) {
            foreach ($request->input('data') as $data) {
                $sensorData = new SensorData();
                $sensorData->date_time = date(
                    'Y-m-d H:i:s',
                    strtotime(
                        "-" . (
                            count($request->input('data')) - $data['minute']
                        ) . " minutes"
                    )
                );
                $sensorData->liters = $data['liters'];
                $sensorData->average_flow = $data['liters'] / 60;
                $sensorData->save();
            }
        }
        return new Response("Ok", 200);
    }

    public function all(Request $request): Response
    {
        $data = SensorData::where('id', '>', 0);
        if ($request->input('start_date_time')) {
            $data->where('date_time', '>=', $request->input('start_date_time'));
        }
        if ($request->input('end_date_time')) {
            $data->where('date_time', '<=', $request->input('end_date_time'));
        }
        return new Response($data->get(), 200);
    }

    public function getDataActualMonth(Request $request): Response
    {
        $actualDay = date('d');
        $lastDay = date('t');
        $data = [];
        $data['label'] = "Litros";
        $data['days'] = [];
        $data['hours'] = [];
        $data['data'] = [];
        $data['totals'] = [];
        $active = 0;
        for ($i = 1; $i <= $lastDay; $i++) {
            $active = $active ?? $i - 1;
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $active = $day == date('d') ? $i - 1 : $active;
            $dateStart = date("Y-m-{$day} 00:00:00");
            $dateEnd = date("Y-m-{$day} 23:59:59");
            DB::table('sensor_data')
                ->select(DB::raw("HOUR(date_time) as hour, SUM(liters) as liters"))
                ->where('date_time', '>=', $dateStart)
                ->where('date_time', '<=', $dateEnd)
                ->orderBy('hour', 'ASC')
                ->groupBy('hour')
                ->chunk(
                    24,
                    function ($sensorAllData) use (&$data, $day, $i) {
                        foreach ($sensorAllData as $sensorData) {
                            $data['data'][$i - 1][$sensorData->hour] = number_format($sensorData->liters, 2, '.', '');
                            $data['days'][$i - 1] = $day;
                        }
                    }
                );
            $data['totals'][$i - 1] = number_format(
                DB::table('sensor_data')
                    ->select(DB::raw("SUM(liters) as liters"))
                    ->where('date_time', '>=', $dateStart)
                    ->where('date_time', '<=', $dateEnd)
                    ->first()->liters ?? 0,
                2,
                '.',
                ''
            );
        }
        for ($i = 0; $i < 24; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data['hours'][] = date('g a', strtotime('+1 hour', strtotime(date("Y-m-d {$hour}:00:00"))));
        }
        $data['hours'][] = '';
        $data['active'] = $active;
        return new Response($data, 200);
    }

    public function getDataActualYear(Request $request): Response
    {
        $dateStart = date('Y-01-01 00:00:00');
        $dateEnd = date('Y-12-t 23:59:59');
        $data = [];
        $data['months'] = [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];
        $data['data'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $sensorData = DB::table('sensor_data')
                ->select(DB::raw("MONTH(date_time) as month, SUM(liters) as liters"))
                ->where('date_time', '>=', $dateStart)
                ->where('date_time', '<=', $dateEnd)
                ->having('month', $i)
                ->orderBy('month', 'ASC')
                ->groupBy('month')
                ->first();
            $data['data'][] = number_format($sensorData->liters ?? 0, 2, '.', '');
        }
        $data['total'] = number_format(
            DB::table('sensor_data')
                ->select(DB::raw("SUM(liters) as liters"))
                ->where('date_time', '>=', $dateStart)
                ->where('date_time', '<=', $dateEnd)
                ->first()->liters ?? 0,
            2,
            '.',
            ''
        );

        return new Response($data, 200);
    }
}
