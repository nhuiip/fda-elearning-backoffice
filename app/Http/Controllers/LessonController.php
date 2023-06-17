<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['route' => '', 'name' => 'Lesson Management'],
        ];
        return view('lesson.main', [
            'title' => 'Lesson Management',
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => '', 'name' => 'Create Lesson'],
        ];
        return view('lesson.form', [
            'title' => 'Create Lesson',
            'breadcrumbs' => $breadcrumbs,
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
                'passScore' => 'required|integer',
                'sort' => 'required|integer'
            ],
            [
                'name.required' => 'Please enter name',
                'name.max' => 'Name cannot be longer than 100 characters.',
                'passScore.required' => 'Please enter score',
                'passScore.integer' => 'Please enter numbers only.',
                'sort.required' => 'Please enter name',
                'sort.integer' => 'Please enter numbers only.',
            ]
        );

        $data = new Lesson($request->all());
        $data->save();

        return redirect()->route('lessons.index')->with('toast_success', 'Create data succeed!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => '', 'name' => 'Edit Lesson'],
        ];
        return view('lesson.form', [
            'title' => 'Edit Lesson',
            'breadcrumbs' => $breadcrumbs,
            'data' => Lesson::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|max:100',
                'passScore' => 'required|integer',
                'sort' => 'required|integer'
            ],
            [
                'name.required' => 'Please enter name',
                'name.max' => 'Name cannot be longer than 100 characters.',
                'passScore.required' => 'Please enter score',
                'passScore.integer' => 'Please enter numbers only.',
                'sort.required' => 'Please enter name',
                'sort.integer' => 'Please enter numbers only.',
            ]
        );

        $data = Lesson::findOrFail($id);
        $data->update($request->all());
        $data->save();

        if ($request->status == null) {
            $data->status = false;
            $data->save();
        }

        return redirect()->route('lessons.index')->with('toast_success', 'Update data succeed!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Lesson::findOrFail($id);
        $data->delete();
        return back()->with('toast_success', 'Delete data succeed!');
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
            'passScore',
            'sort',
            'status',
            'created_at',
            'updated_at',
            'action',
        );

        if (empty($order)) {
            $sort = 'sort';
            $dir = 'asc';
        } else {
            $sort = $columnorder[$order[0]['column']];
            $dir = $order[0]['dir'];
        }
        // query
        $keyword = trim($search['value']);

        $data = Lesson::when($keyword, function ($query, $keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%')->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($sort, $dir)
            ->get();
        $recordsTotal = Lesson::select('id')->count();
        $recordsFiltered = Lesson::select('id')
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
            ->editColumn('status', function ($data) {
                return $data->status ? '<i class="text-success"><u><b>Active</b></u></i>' : '<i class="text-danger"><u><b>Inactive</b></u></i>';
            })
            ->editColumn('created_at', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->created_at)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->created_at)) . '</small>';
            })
            ->editColumn('updated_at', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->updated_at)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->updated_at)) . '</small>';
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                return view('lesson._actions', compact('id'));
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
