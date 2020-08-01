<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Report;
use App\Weather;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Undeadline\YW\YandexWeather;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reports = Report::get();
            $dt = DataTables::of($reports);
            $dt->addColumn('action', function ($data) {
                $button = '<a href="/admin/reports/' . $data->id . '" type="button" name="show" id="' . $data->id . '" class="download btn btn-default btn-sm mr-1">Просмотреть</a>';
                $button .= '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm mr-1">Изменить</button>';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm mr-1">Удалить</button>';
                $button .= '<button type="button" name="download" id="' . $data->id . '" class="download btn btn-success btn-sm">Скачать</button>';

                return $button;
            });
            return $dt->rawColumns(['action'])->make(true);
        }
        return view('admin.reports.index');
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {

            return view('admin.reports.show');
        }
        return view('admin.reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $report_data = [
            'name' => 'Отчет',
        ];
        $report = Report::create($report_data);
        $report->name = 'Отчет номер ' . $report->id;
        $report->save();
        $cities = City::all();
        $report->cities()->attach($cities);
        foreach ($cities as $city) {
            $latitude = $city->lat;
            $longitude = $city->lon;
            $params = [
                'lang' => 'ru_RU', // response language
                'limit' => 5, // forecast period
                'hours' => true, // response is contains horly period
                'extra' => true // detailed precipitation forecast
            ];

            $weather = new YandexWeather($latitude, $longitude, $params);

            $data_arr = [
                'info' => $weather->content()->info,
                'forecasts' => $weather->content()->forecasts,
                'fact' => $weather->content()->fact
            ];
            $fact = $data_arr['fact'];

            $form_data = array(
                'report_id' => $report->id,
                'city_id' => $city->id,
                'status' => 'fact',
                'icon' => $fact->icon,
                'condition' => $fact->condition,
                'temp' => $fact->temp,
                'temp_max' => $fact->feels_like,
                'temp_min' => $fact->temp,
                'humidity' => $fact->humidity,
                'date' => date("Y-m-d"),
            );
            Weather::create($form_data);
            foreach ($data_arr['forecasts'] as $forecast) {
                $form_data = array(
                    'report_id' => $report->id,
                    'city_id' => $city->id,
                    'status' => 'forecasts',
                    'icon' => $forecast->parts->day_short->icon,
                    'condition' => $forecast->parts->day_short->condition,
                    'temp' => $forecast->parts->day_short->temp,
                    'temp_max' => $forecast->parts->day_short->temp_min,
                    'temp_min' => $forecast->parts->night_short->temp,
                    'humidity' => $forecast->parts->day_short->humidity,
                    'date' => $forecast->date,
                );
                Weather::create($form_data);
            }


        }
        $response = [
            'text' => 'Успешно',
            'redirectTo' => '/admin/reports/' . $report->id,
        ];
        return response()->json(['success' => $response]);
    }

    /**
     * Display the specified resource.
     *
     * @param Report $report
     * @return Application|Factory|View
     */
    public function show(Report $report, Request $request)
    {

        if ($request->ajax()) {
            $cities = $report->cities()->get();
            $dt = DataTables::of($cities);
            $raw_col = ['action'];
            $dt->addColumn('name', function ($cities) use ($report) { //Создаем поле с именем города
                return $cities->name;
            });

            for ($i = 0; $i < 6; $i++) { // Заупскаем цикл по колонкам погоды
                $dt->addColumn('weather_' . $i, function ($city) use ($i, $report) { // создаем колонку прогноза со счетчиком в названии, получаем данные из фнутренней функции
                    $weather = Weather::where('report_id', $report->id)->where('city_id', $city->id)->get();
                    $this_weather = $weather[$i];
                    return '
                                 <div class="row">
                                    <div class="col-12 mb-4">
                                        <img src="https://yastatic.net/weather/i/icons/blueye/color/svg/' . $this_weather->icon . '.svg"  class="img-thumbnail" width="100">
                                    </div>
                                    <div class="col-12" style="max-width: 200px;word-break: break-all;">
                                    <p>id: ' . $this_weather->id . '</p>
                                    <p>report_id: ' . $this_weather->report_id . '</p>
                                    <p>city_id: ' . $this_weather->city_id . '</p>
                                    <p>status: ' . $this_weather->status . '</p>
                                        <p>Дата: ' . date('d.m.Y', strtotime($this_weather->date)) . '</p>
                                        <p>Состояние: ' . $this_weather->condition . '</p>
                                        <p>Температура: ' . $this_weather->temp . '°</p>
                                        <p>Влажность: ' . $this_weather->humidity . '%</p>

                                    </div>
                                </div>
                            '; // Выводим данные указывая id  в массиве данных
                });
                $raw_col[] = 'weather_' . $i;
            }

            $dt->addColumn('action', function ($data) {
                return '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Удалить</button>';
            });

            return $dt->rawColumns($raw_col)->make(true);
        }
        return view('admin.reports.show', [
            'report' => $report
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Report $report
     * @return Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Report $report
     * @return Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Report $report
     * @return Response
     */
    public function destroy(Report $report)
    {
        $report->cities()->detach();
        $report->weather()->delete();
        $report->delete();
        return response()->json(['success' => 'Данные успешно удалены']);
    }
}
