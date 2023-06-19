<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => '', 'name' => 'Question Management'],
        ];
        return view('question.main', [
            'title' => $lesson->name . ': Question Management',
            'breadcrumbs' => $breadcrumbs,
            'lesson' => $lesson
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($lessonId)
    {
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => route('questions.index', $lessonId), 'name' => 'Question Management'],
            ['route' => '', 'name' => 'Create Question'],
        ];
        return view('question.form', [
            'title' => 'Create Question',
            'breadcrumbs' => $breadcrumbs,
            'lesson' => Lesson::findOrFail($lessonId)
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
                'name' => 'required|max:255',
                'score' => 'required|integer',
                'sort' => 'required|integer',
                'image' => 'mimes:jpeg,jpg,png,webp',
            ],
            [
                'name.required' => 'Please enter question',
                'name.max' => 'Question cannot be longer than 255 characters.',
                'score.required' => 'Please enter score',
                'score.integer' => 'Please enter numbers only.',
                'sort.required' => 'Please enter name',
                'sort.integer' => 'Please enter numbers only.',
                'image.mimes' => 'Only jpeg,jpg,png,webp file type is supported.',
            ]
        );

        $data = new Question($request->all());
        $data->save();

        if ($request->hasfile('image')) {
            $imageUrl = $request->file('image')->store('lesson/' . $data->lessonId . '/question/' . $data->id, 'public');

            // !update image url
            $data->hasImage = true;
            $data->imageUrl = config('app.url') . "/storage/" . $imageUrl;
            $data->save();
        }

        return redirect()->route('questions.edit', $data->id)->with('toast_success', 'Create data succeed!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Question::findOrFail($id);
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => route('questions.index', $data->lessonId), 'name' => 'Question Management'],
            ['route' => '', 'name' => 'Edit Question'],
        ];
        return view('question.form', [
            'title' => 'Edit Question',
            'breadcrumbs' => $breadcrumbs,
            'lesson' => Lesson::findOrFail($data->lessonId),
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->action != 'deleteImage') {
            $this->validate(
                $request,
                [
                    'name' => 'required|max:255',
                    'score' => 'required|integer',
                    'sort' => 'required|integer',
                    'image' => 'mimes:jpeg,jpg,png,webp',
                ],
                [
                    'name.required' => 'Please enter question',
                    'name.max' => 'Question cannot be longer than 255 characters.',
                    'score.required' => 'Please enter score',
                    'score.integer' => 'Please enter numbers only.',
                    'sort.required' => 'Please enter name',
                    'sort.integer' => 'Please enter numbers only.',
                    'image.mimes' => 'Only jpeg,jpg,png,webp file type is supported.',
                ]
            );
        }

        $data = Question::findOrFail($id);
        $data->update($request->all());
        $data->save();

        if ($request->action != 'deleteImage' && $request->status == null) {
            $data->status = false;
            $data->save();
        }

        if ($request->hasfile('image')) {
            $imageUrl = $request->file('image')->store('lesson/' . $data->lessonId . '/question/' . $data->id, 'public');

            // !update image url
            $data->hasImage = true;
            $data->imageUrl = config('app.url') . "/storage/" . $imageUrl;
            $data->save();
        }

        switch ($request->action) {
            case 'deleteImage':
                return back()->with('toast_success', 'Delete data succeed!');
                break;

            default:
                return redirect()->route('questions.edit', $data->id)->with('toast_success', 'Update data succeed!');
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Question::findOrFail($id);
        $data->delete();
        return back()->with('toast_success', 'Delete data succeed!');
    }

    public function jsontable(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search');
        $order = $request->get('order');

        $lessonId = $request->get('lessonId');

        $columnorder = array(
            'id',
            'name',
            'score',
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

        $data = Question::when($lessonId, function ($query, $lessonId) {
            if (!empty($lessonId)) {
                return $query->where('lessonId', $lessonId);
            }
        })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($sort, $dir)
            ->get();
        $recordsTotal = Question::select('id')
            ->when($lessonId, function ($query, $lessonId) {
                if (!empty($lessonId)) {
                    return $query->where('lessonId', $lessonId);
                }
            })->count();
        $recordsFiltered = Question::select('id')
            ->when($lessonId, function ($query, $lessonId) {
                if (!empty($lessonId)) {
                    return $query->where('lessonId', $lessonId);
                }
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
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
                return view('question._actions', compact('id'));
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
