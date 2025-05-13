@extends('layouts.student')
@section('title','Admin Dashboard')
@section('content')
<style>
    .table td {
        white-space: normal !important;
    }
    .right-answer {
        background-color: #8ae48a !important;
        color: white;
    }
    .wrong-answer {
        background-color: #dd8080 !important;
        color: white;
    }
    .table-striped>tbody>tr:nth-of-type(odd) {
        color: white;
        --bs-table-accent-bg: none;
    }
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Student Test Question</h4>
                    <p class="card-description">
                        Total Rows: {{ $testCount }}
                        <!-- <a class="btn btn-success btn-sm align-right" href="?download=1{{ request()->has('id') && request()->id ? '&id='.request()->id : ''}}">
                            <i class="fa-solid fa-file-excel"></i>
                            Download
                        </a> -->
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Question</th>
                                    <th>Student Answer</th>
                                    <th>Right Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testList as $test)
                                <tr class="{{ ($test && strtolower($test->right_answer) === strtolower($test->student_answer)) ? 'right-answer': 'wrong-answer' }}">
                                    <td class="py-1 w-200">
                                        {{ $test->id }}
                                    </td>
                                    <td class="py-1 w-200">
                                        {{ $test->user_name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->exam_name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->subject_name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->que ?? '' }}
                                        <br>
                                        @if ($test->quef !== '') 
                                            <img src="{{ asset($test->quef) }}" alt="">
                                        @endif
                                    </td>
                                    <td class="w-200">
                                        {{ strtoupper($test->student_answer) }}
                                    </td>
                                    <td class="w-200">
                                        {{ strtoupper($test->right_answer ?? '') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection