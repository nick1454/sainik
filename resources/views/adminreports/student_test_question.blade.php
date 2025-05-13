@extends('layouts.admin')
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
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
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
                                <tr class="{{ ($test->question && strtolower($test->question->right_answer) === strtolower($test->student_answer)) ? 'right-answer': 'wrong-answer' }}">
                                    <td class="py-1 w-200">
                                        {{ $test->user->name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->exam->name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->question->subject_name ?? '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ $test->question ? $test->question->question() : '' }}
                                    </td>
                                    <td class="w-200">
                                        {{ strtoupper($test->student_answer ?? '') }}
                                    </td>
                                    <td class="w-200">
                                        {{ strtoupper($test->question->right_answer ?? '') }}
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