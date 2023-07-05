<?php


namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Member;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['route' => '', 'name' => 'Dashboard'],
        ];
        $lessons = Lesson::all();
        $lessonSammaries = array();
        $memberTotal = Member::count();
        foreach ($lessons as $lesson) {
            $passed = Exam::where(['lessonId' => $lesson->id, 'isFinish' => true, 'isPass' => true])->count();
            $lessonSammaries[] = array(
                'period' => 'บทที่ ' . $lesson->sort,
                // 'passed' => ($passed / $memberTotal) * 100,
                // 'unpassed' => (($memberTotal - $passed) / $memberTotal) * 100,
                'passed' => $passed,
                'unpassed' => $memberTotal - $passed,
            );
        }

        $businessType = array();
        foreach (Member::businessType as $key => $value) {
            if ($value != 'อื่นๆ') {
                $businessType[] = array(
                    'label' => $value,
                    'value' => Member::where('businessType', $value)->count()
                );
            } else {
                $businessType[] = array(
                    'label' => $value,
                    'value' => Member::whereNotIn('businessType', Member::businessType)->count()
                );
            }
        }

        return view('dashboard.main', [
            'title' => 'Dashboard',
            'breadcrumbs' => $breadcrumbs,
            'date' => ['startDate' => date('Y-m-d', strtotime('-7 day')), 'endDate' => date('Y-m-d')],
            'lessonSammaries' => $lessonSammaries,
            'memberTotal' => $memberTotal,
            'memberPassed' => Member::where('passed', true)->count(),
            'memberUnpassed' => Member::where('passed', false)->count(),
            'businessType' => $businessType
        ]);
    }

    public function getRegisterAndVisit(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $dateRang = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );

        $data = array();
        foreach ($dateRang as $value) {
            $date = $value->format('Y-m-d');
            $data[] = array(
                'date' => $date,
                'register' => Member::whereDate('registerDate', $date)->count(),
                'visit' => Member::whereDate('firstLoginDate', $date)->count()
            );
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }
}
