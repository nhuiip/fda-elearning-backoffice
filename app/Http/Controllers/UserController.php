<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['route' => '', 'name' => 'User Management'],
        ];
        return view('user.main', [
            'title' => 'User Management',
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['route' => route('users.index'), 'name' => 'User Management'],
            ['route' => '', 'name' => 'Create User'],
        ];
        return view('user.form', [
            'title' => 'Create User',
            'breadcrumbs' => $breadcrumbs,
            'role' =>Role::select('name', 'name')->get()->pluck('name', 'name')->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|max:100',
                'email' => 'required|email|unique:users|max:255',
                'role' => 'required',
                'password' => 'required', 'min:8', 'confirmed',
                'password_confirmation' => 'required',
            ],
            [
                'name.required' => 'Please enter name',
                'name.max' => 'Name cannot be longer than 100 characters.',
                'email.required' => 'Please enter email',
                'email.email' => 'Invalid email format',
                'email.unique' => 'Email has already been taken.',
                'email.max' => 'Email cannot be longer than 255 characters.',
                'role.required' => 'Please select role.',
                'password.required' => 'Please enter password.',
                'password.min' => 'Please enter a password of at least 8 characters.',
                'password.confirmed' => 'Incorrect password confirmation',
                'password_confirmation.required' => 'Please confirm password.',
                'password_confirmation.min' => 'Please enter a confirmation password of at least 8 characters.',
            ]
        );

        $data = new User($request->all());
        $data->save();
        $data->assignRole($request->role);


        return redirect()->route('users.index')->with('toast_success', 'Create data succeed!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumbs = [
            ['route' => route('users.index'), 'name' => 'User Management'],
            ['route' => '', 'name' => 'Edit User'],
        ];
        return view('user.form', [
            'title' => 'Edit User',
            'breadcrumbs' => $breadcrumbs,
            'role' =>Role::select('name', 'name')->get()->pluck('name', 'name')->toArray(),
            'data' => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        switch ($request->action) {
            case 'resetpassword':
                $this->validate(
                    $request,
                    [
                        'password' => 'required', 'min:8', 'confirmed',
                        'password_confirmation' => 'required',
                    ],
                    [
                        'password.required' => 'Please enter password.',
                        'password.min' => 'Please enter a password of at least 8 characters.',
                        'password.confirmed' => 'Incorrect password confirmation',
                        'password_confirmation.required' => 'Please confirm password.',
                        'password_confirmation.min' => 'Please enter a confirmation password of at least 8 characters.',
                    ]
                );
                break;
            default:
                $this->validate(
                    $request,
                    [
                        'name' => 'required|max:100',
                        'email' => 'required|email|max:255|unique:users,email,' . $id,
                        'role' => 'required',
                    ],
                    [
                        'name.required' => 'Please enter name',
                        'name.max' => 'Name cannot be longer than 100 characters.',
                        'email.required' => 'Please enter email',
                        'email.email' => 'Invalid email format',
                        'email.unique' => 'Email has already been taken.',
                        'email.max' => 'Email cannot be longer than 255 characters.',
                        'role.required' => 'Please select role.',
                    ]
                );
                break;
        }

        $data = User::findOrFail($id);
        $data->update($request->all());
        $data->save();

        if (isset($request->role)) {
            if (!$data->hasRole($request->role)) {
                // remove old role
                $data->removeRole($data->roles->first()->name);
                // assign new role
                $data->assignRole($request->role);
            }
        }

        return redirect()->route('users.index')->with('toast_success', 'Update data succeed!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return back()->with('toast_success', 'Delete data succeed!');
    }

    public function resetpassword($id)
    {
        $breadcrumbs = [
            ['route' => route('users.index'), 'name' => 'User Management'],
            ['route' => '', 'name' => 'Reset Password'],
        ];
        return view('user.form', [
            'title' => 'Reset Password',
            'breadcrumbs' => $breadcrumbs,
            'data' => User::findOrFail($id)
        ]);
    }


    public function jsontable(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search');
        $order = $request->get('order');


        $columnorder = array(
            'id',
            'name',
            'email',
            'role',
            'created_at',
            'updated_at',
            'action',
        );

        if (empty($order)) {
            $sort = 'name';
            $dir = 'asc';
        } else {
            $sort = $columnorder[$order[0]['column']];
            $dir = $order[0]['dir'];
        }
        // query
        $keyword = trim($search['value']);

        $data = User::when($keyword, function ($query, $keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%')->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($sort, $dir)
            ->get();
        $recordsTotal = User::select('id')->count();
        $recordsFiltered = User::select('id')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')->orWhere('email', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->count();
        $resp = DataTables::of($data)
            ->editColumn('id', function ($data) {
                return str_pad($data->id, 5, "0", STR_PAD_LEFT);
            })
            ->editColumn('role', function ($data) {
                return $data->roles->first()->name;
            })
            ->editColumn('created_at', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->created_at)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->created_at)) . '</small>';
            })
            ->editColumn('updated_at', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->updated_at)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->updated_at)) . '</small>';
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                return view('user._actions', compact('id'));
            })
            ->setTotalRecords($recordsTotal)
            ->setFilteredRecords($recordsFiltered)
            ->escapeColumns([])
            ->skipPaging()
            ->addIndexColumn()
            ->make(true);
        return $resp;
    }
}
