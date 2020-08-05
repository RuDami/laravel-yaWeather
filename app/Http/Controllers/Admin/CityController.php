<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Weather;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\View;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CityController extends Controller
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
     * @return JsonResponse
     * @return \Illuminate\View\View
     *
     *
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = City::get();
            return DataTables::of($data)->addColumn('action', function ($data) {
                $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm mr-1">Изменить</button>';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Удалить</button>';
                return $button;
            })->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'lat' => 'required|numeric|min:-100|max:100',
            'lon' => 'required|numeric|min:-100|max:100',

        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'name' => $request->input('name'),
            'lat' => $request->input('lat'),
            'lon' => $request->input('lon'),
        );
        City::create($form_data);
        return response()->json(['success' => 'Данные успешно добавлены']);
    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return Response
     */
    public function show(City $city)
    {
        return view('admin.cities.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Request $id
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = City::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param City $city
     * @return Response
     */
    public function update(Request $request, City $city)
    {
        $rules = array(
            'name' => 'bail|required|string',
            'lat' => 'required|numeric|min:-100|max:100',
            'lon' => 'required|numeric|min:-100|max:100',

        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'name' => $request->input('name'),
            'lat' => $request->input('lat'),
            'lon' => $request->input('lon'),
        );
        $city->update($form_data);

        return response()->json(['success' => 'Данные успешно обновлены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param City $city
     * @return Response
     */
    public function destroy(City $city)
    {
        Weather::where('city_id', $city->id)->delete();
        $city->reports()->detach();
        $city->delete();
        return response()->json(['success' => 'Данные успешно удалены']);
    }
}
