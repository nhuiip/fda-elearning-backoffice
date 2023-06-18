<?php

namespace App\Http\Controllers;

use App\Imports\MemberImport;
use Illuminate\Support\Str;
use App\Models\Member;
use App\Models\MemberImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['route' => '', 'name' => 'Member Management'],
        ];
        return view('member.main', [
            'title' => 'Member Management',
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type)
    {
        switch ($type) {
            case 'import':
                $breadcrumbs = [
                    ['route' => route('members.index'), 'name' => 'Member Management'],
                    ['route' => '', 'name' => 'Import Member'],
                ];
                return view('member.form', [
                    'title' => 'Import Member',
                    'breadcrumbs' => $breadcrumbs,
                    'type' => $type,
                ]);
                break;

            default:
                $breadcrumbs = [
                    ['route' => route('members.index'), 'name' => 'Member Management'],
                    ['route' => '', 'name' => 'Create Member'],
                ];
                return view('member.form', [
                    'title' => 'Create Member',
                    'breadcrumbs' => $breadcrumbs,
                    'type' => $type,
                    'businessType' => Member::businessType
                ]);
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Member::findOrFail($id);
        $data->delete();
        return back()->with('toast_success', 'Delete data succeed!');
    }

    public function import(Request $request)
    {
        $this->validate(
            $request,
            [
                'file' => 'required|mimes:xlsx',
            ],
            [
                'file.required' => 'Please select file',
                'file.mimes' => 'Only xlsx file type is supported.',
            ]
        );

        Excel::import(new MemberImport, request()->file('file'));

        if ($request->hasfile('file')) {
            $filename = $request->file('file')->getClientOriginalName() . '-' . Str::random(8);
            $file_url = "import/" . $filename;
            Storage::disk('public')->put($file_url, file_get_contents($request->file('file')));
            
            $data = new MemberImportLog();
            $data->fileUrl = config('app.url') . "/storage/" . $file_url;
            $data->save();
        }

        return redirect()->route('members.index')->with('toast_success', 'Import data succeed!');
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
            'businessType',
            'passed',
            'created_at',
            'businessType',
            'registerDate',
            'lastVisitDate',
            'action',
        );

        if (empty($order)) {
            $sort = 'registerDate';
            $dir = 'asc';
        } else {
            $sort = $columnorder[$order[0]['column']];
            $dir = $order[0]['dir'];
        }
        // query
        $keyword = trim($search['value']);

        $data = Member::when($keyword, function ($query, $keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('company', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('department', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('position', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('businessType', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        })
            ->offset($start)
            ->limit($length)
            ->orderBy($sort, $dir)
            ->get();
        $recordsTotal = Member::select('id')->count();
        $recordsFiltered = Member::select('id')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('company', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('department', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('position', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('businessType', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->count();
        $resp = DataTables::of($data)
            ->editColumn('id', function ($data) {
                return str_pad($data->id, 5, "0", STR_PAD_LEFT);
            })
            ->editColumn('passed', function ($data) {
                return $data->passed ? '<i class="text-success"><u><b>Yes</b></u></i>' : '<i class="text-danger"><u><b>No</b></u></i>';
            })
            ->editColumn('created_at', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->created_at)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->created_at)) . '</small>';
            })
            ->editColumn('registerDate', function ($data) {
                return '<small>' . date('d/m/Y', strtotime($data->registerDate)) . '<br><i class="far fa-clock"></i> ' . date('h:i A', strtotime($data->registerDate)) . '</small>';
            })
            ->addColumn('action', function ($data) {
                $id = $data->id;
                return view('member._actions', compact('id'));
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
