<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Report;
use App\User;
use App\Weather;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reports = Report::get();
            $dt = DataTables::of($reports);
            $dt->editColumn('user_id', function ($data) {
                if ($data->user_id) {
                    $user = ' <p>' . User::findOrFail($data->user_id)->name . '</p>';
                } else {
                    $user = 'Не указан';
                }
                return $user;
            });
            $dt->addColumn('date', function ($data) {
                $button = ' <p>' . date('d.m.Y', strtotime($data->created_at)) . '</p>';
                return $button;
            });
            $dt->addColumn('time', function ($data) {
                $button = ' <p>' . date('H:i:s', strtotime($data->created_at)) . '</p>';
                return $button;
            });
            $dt->addColumn('action', function ($data) {
                $button = '<a href="/admin/reports/' . $data->id . '" type="button" name="show" id="' . $data->id . '" class="download btn btn-default btn-sm mr-1">Просмотреть</a>';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm mr-1">Удалить</button>';
                $button .= '<a  href="/admin/reports/download/' . $data->id . '" target="_blank" name="download" id="' . $data->id . '" class="download btn btn-success btn-sm">Скачать</a>';
                return $button;
            });
            return $dt->rawColumns(['action', 'date', 'time', 'user_id'])->make(true);
        }
        return view('admin.reports.index', [
            'cities' => City::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $cities = City::all();
        return view('admin.reports.create', [
            'cities' => $cities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $report_data = [
            'name' => 'Отчет',
            'user_id' => Auth::id()
        ];
        $report = Report::create($report_data);
        $cities = $request->input('cities');
        $report->cities()->attach($cities);

        if ($cities == null) {
            $response = [
                ['text' => 'Пожалуйста выберите хотябы один город'],
            ];
            return response()->json(['errors' => $response]);
        }
        foreach ($cities as $city) {
            $city = City::findOrFail($city);
            $store_weather = (new WeatherController)->store($report, $city);
        }
        if ($store_weather == 0) {
            $response = [
                'text' => 'Успешно',
                'redirectTo' => '/admin/reports/' . $report->id,
            ];
            return response()->json(['success' => $response]);
        } else {
            $response = [
                'text' => 'Не Удалось',
            ];
            return response()->json(['errors' => $response]);
        }
    }

    public function download(Report $report)
    {
        return (new ReportsExport($report))->download('report_' . $report->id . '_' . $report->created_at . '.csv');
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
            $dt->addColumn('name', function ($cities) { //Создаем поле с именем города
                return $cities->name;
            });

            for ($i = 0; $i < 6; $i++) { // Заупскаем цикл по колонкам погоды
                $dt->addColumn('weather_' . $i, function ($city) use ($i, $report) {
                    // создаем колонку прогноза со счетчиком в названии, получаем данные из внутренней функции
                    $weather = Weather::where('report_id', $report->id)->where('city_id', $city->id)->get();
                    return view('admin.reports.weather-display', [
                        'this_weather' => $weather[$i]
                    ]);
                });
                $raw_col[] = 'weather_' . $i;
            }

            return $dt->rawColumns($raw_col)->make(true);
        }
        return view('admin.reports.show', ['report' => $report]);
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
     * @return JsonResponse
     */
    public function destroy(Report $report)
    {
        $report->cities()->detach();
        $report->weather()->delete();
        $report->delete();
        return response()->json(['success' => 'Данные успешно удалены']);
    }
}
