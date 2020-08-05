<?php

namespace App\Http\Controllers\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
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
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::get();
            $dt = DataTables::of($data);
            $dt->addColumn('roles', function ($data) {

                return '<p>Тут будут роли</p>';
            });
            $dt->addColumn('action', function ($data) {
                $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm mr-1">Изменить</button>';
                $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Удалить</button>';
                return $button;
            });

            return $dt->rawColumns(['action', 'roles'])
                ->make(true);
        }
        return view('admin.user_management.users.index');
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',

        );
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        );
        $form_data['password'] = Hash::make($form_data['password']);
        User::create($form_data);

        return response()->json(['success' => 'Данные успешно добавлены']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $request
     * @param int $id
     * @return JsonResponse
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            // 'roles' => 'required'
        ]);


        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        User::find($id)->update($input);

        //  DB::table('model_has_roles')->where('model_id', $id)->delete();
        //  $user->assignRole($request->input('roles'));

        return response()->json(['success' => 'Данные успешно обновлены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        //  Report::where('user_id', $user->id)->delete();
        $user->delete();
        return response()->json(['success' => 'Данные успешно удалены']);
    }
}
