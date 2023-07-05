@extends('layouts.app')
@section('title', $title)
@section('style')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/timepicker.css') }}">
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style>
        .input-group .btn {
            color: #1e2125;
            padding: 0;
        }

        .input-group .btn:hover {
            background-color: transparent !important;
            border-color: #1e2125 !important;
        }

        /* 1e2125 */
    </style>
@endsection
@section('breadcrumb')
    @include('layouts.components._breadcrumb', ['breadcrumbs' => $breadcrumbs, 'title' => $title])
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5>จำนวนผู้ลงทะเบียน/ใช้งาน</h5>
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <input type="text" class="form-control datepicker" id="startDate"
                                value="{{ $date['startDate'] }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control datepicker" id="endDate"
                                value="{{ $date['endDate'] }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-info w-100" onclick="getRegisterAndVisit()">ค้นหา</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="RegisterAndVisit" class="w-100">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5>จำนวนผู้สอบผ่าน</h5>
        </div>
        <div class="card-body">
            <div id="lessonSammaries" class="w-100" data-item="{{ json_encode($lessonSammaries) }}">
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card total-transactions pb-3">
                <div class="row m-0">
                    <div class="col-md-4 col-sm-4 p-0">
                        <div class="card-header card-no-border">
                            <h5>ผู้สมัครทั้งหมด</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="report-content text-center">
                                <h1>{{ $memberTotal }}</h1>
                                <div class="progress progress-round-primary">
                                    <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 p-0" style="border-left:1px solid rgba(30,47,101,0.1)">
                        <div class="card-header card-no-border">
                            <h5>ผ่านการทดสอบ</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="report-content text-center">
                                <h1>{{ $memberPassed }}</h1>
                                <div class="progress progress-round-primary">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($memberPassed * 100) / $memberTotal }}%"
                                        aria-valuenow="{{ ($memberPassed * 100) / $memberTotal }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 p-0" style="border-left:1px solid rgba(30,47,101,0.1)">
                        <div class="card-header card-no-border">
                            <h5>ยังไม่ผ่านการทดสอบ</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="report-content text-center">
                                <h1>{{ $memberUnpassed }}</h1>
                                <div class="progress progress-round-primary">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ ($memberUnpassed * 100) / $memberTotal }}%"
                                        aria-valuenow="{{ ($memberUnpassed * 100) / $memberTotal }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>หน่วยงานผู้สมัคร</h5>
                </div>
                <div class="card-body">
                    <div id="businessType" class="w-100" data-item="{{ json_encode($businessType) }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <script>
        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap5'
        });
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap5'
        });
    </script>
    {{-- getRegisterAndVisit --}}
    <script>
        let morrisRegisterAndVisit;
        initRegisterAndVisit();
        getRegisterAndVisit();

        function initRegisterAndVisit() {
            morrisRegisterAndVisit = Morris.Line({
                element: 'RegisterAndVisit',
                xkey: 'date',
                ykeys: ['register', 'visit'],
                labels: ['Register', 'Visit'],
                parseTime: false,
                resize: true,
            });
        }

        function setRegisterAndVisit(data) {
            morrisRegisterAndVisit.setData(data);
        }

        function getRegisterAndVisit(e) {
            let startDate = $('#startDate').val()
            let endDate = $('#endDate').val()
            let url = "{!! route('dashboard.getRegisterAndVisit') !!}"
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(data) {
                    setRegisterAndVisit(data)
                }
            })
        }
    </script>
    {{-- lessonSammaries --}}
    <script>
        initLessonSammaries()

        function initLessonSammaries() {
            let data = $('#lessonSammaries').attr('data-item')
            data = jQuery.parseJSON(data);
            Morris.Bar({
                element: 'lessonSammaries',
                data: data,
                xkey: 'period',
                ykeys: ['passed', 'unpassed'],
                labels: ['ผ่าน', 'ไม่ผ่าน'],
                parseTime: false,
                resize: true,
                formatter: function(a) {
                    return a + "%"
                }
            });
        }
    </script>
    {{-- businessType --}}
    <script>
        initBusinessType()

        function initBusinessType() {
            let data = $('#businessType').attr('data-item')
            data = jQuery.parseJSON(data);
            Morris.Donut({
                element: 'businessType',
                data: data,
                resize: true,
                colors: ['#55efc4', '#74b9ff', "#a29bfe", "#ffeaa7", "#ff7675"],
            });
        }
    </script>
@endsection
