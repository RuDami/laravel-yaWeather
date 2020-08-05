<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Report;
use App\Weather;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Undeadline\YW\YandexWeather;
use Yajra\DataTables\DataTables;

class WeatherController extends Controller
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
     *
     * @return JsonResponse
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Weather::get();
            return DataTables::of($data)->addColumn('action', function ($data) {
                return '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Удалить</button>';

            })->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.weather.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Report $report
     * @param City $city
     * @return int
     */

    public function store($report, $city)
    {
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

        Weather::create([
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
        ]);
        foreach ($data_arr['forecasts'] as $forecast) {
            Weather::create([
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
            ]);
        }
        return 0;
    }

    /**
     * Display the specified resource.
     *
     * @param Weather $weather
     * @return Response
     */
    public function show(Weather $weather)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Weather $weather
     * @return Response
     */
    public function edit(Weather $weather)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Weather $weather
     * @return Response
     */
    public function update(Request $request, Weather $weather)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Weather $weather
     * @return Response
     */
    public function destroy(Weather $weather)
    {
        $weather->delete();
        return response()->json(['success' => 'Данные успешно удалены']);
    }
}
