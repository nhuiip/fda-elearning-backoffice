<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ChoiceController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create($questionId)
    {
        $question = Question::findOrFail($questionId);
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => route('questions.index', $question->lessonId), 'name' => 'Question Management'],
            ['route' => route('questions.edit', $question->id), 'name' => 'Edit Question'],
            ['route' => '', 'name' => 'Create Choice'],
        ];
        return view('choice.form', [
            'title' => 'Create Choice',
            'breadcrumbs' => $breadcrumbs,
            'question' => $question
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
                'sort' => 'required|integer',
                'image' => 'mimes:jpeg,jpg,png,webp',
            ],
            [
                'sort.required' => 'Please enter name',
                'sort.integer' => 'Please enter numbers only.',
                'image.mimes' => 'Only jpeg,jpg,png,webp file type is supported.',
            ]
        );

        $data = new Choice($request->all());
        $data->save();

        $question = Question::findOrFail($data->questionId);
        if ($request->hasfile('image')) {
            $imageUrl = $request->file('image')->store('lesson/' . $question->lessonId . '/question/' . $question->id . '/choice/' . $data->id, 'public');

            // !update image url
            $data->hasImage = true;
            $data->imageUrl = config('app.url') . "/storage/" . $imageUrl;
            $data->save();
        }

        return redirect()->route('questions.edit', $data->questionId)->with('toast_success', 'Create data succeed!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Choice::findOrFail($id);
        $question = Question::findOrFail($data->questionId);
        $breadcrumbs = [
            ['route' => route('lessons.index'), 'name' => 'Lesson Management'],
            ['route' => route('questions.index', $question->lessonId), 'name' => 'Question Management'],
            ['route' => route('questions.edit', $question->id), 'name' => 'Edit Question'],
            ['route' => '', 'name' => 'Edit Choice'],
        ];
        return view('choice.form', [
            'title' => 'Edit Choice',
            'breadcrumbs' => $breadcrumbs,
            'question' => $question,
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
                    'sort' => 'required|integer',
                    'image' => 'mimes:jpeg,jpg,png,webp',
                ],
                [
                    'sort.required' => 'Please enter name',
                    'sort.integer' => 'Please enter numbers only.',
                    'image.mimes' => 'Only jpeg,jpg,png,webp file type is supported.',
                ]
            );
        }

        $data = Choice::findOrFail($id);
        $data->update($request->all());
        $data->save();

        if ($request->action != 'deleteImage' && $request->status == null) {
            $data->status = false;
            $data->save();
        }
        if ($request->action != 'deleteImage' && $request->isRight == null) {
            $data->isRight = false;
            $data->save();
        }

        $question = Question::findOrFail($data->questionId);
        if ($request->hasfile('image')) {
            $imageUrl = $request->file('image')->store('lesson/' . $question->lessonId . '/question/' . $question->id . '/choice/' . $data->id, 'public');

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
                return redirect()->route('questions.edit', $data->questionId)->with('toast_success', 'Update data succeed!');
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Choice::findOrFail($id);
        $data->delete();
        return back()->with('toast_success', 'Delete data succeed!');
    }

    public function jsontable(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search');
        $order = $request->get('order');

        $questionId = $request->get('questionId');

        $columnorder = array(
            'id',
            'name',
            'isRight',
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

        $data = Choice::when($questionId, function ($query, $questionId) {
            if (!empty($questionId)) {
                return $query->where('questionId', $questionId);
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
        $recordsTotal = Choice::select('id')
            ->when($questionId, function ($query, $questionId) {
                if (!empty($questionId)) {
                    return $query->where('questionId', $questionId);
                }
            })->count();
        $recordsFiltered = Choice::select('id')
            ->when($questionId, function ($query, $questionId) {
                if (!empty($questionId)) {
                    return $query->where('questionId', $questionId);
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
            ->editColumn('name', function ($data) {
                return !$data->hasImage ? $data->name : '<a href="' . $data->imageUrl . '" target="_blank">' . $data->imageUrl . '</a>';
            })
            ->editColumn('isRight', function ($data) {
                return $data->isRight ? '<i class="text-success"><u><b>Yes</b></u></i>' : '<i class="text-danger"><u><b>No</b></u></i>';
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
                return view('choice._actions', compact('id'));
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
