@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Student Test Attempt</h4>
                    <p class="card-description">
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>UserName</th>
                                    <th>Exam</th>
                                    <th>Questions Attempted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testList as $test)
                                <tr>
                                    <td class="py-1">
                                        {{ $test->user->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $test->exam->name ?? '' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reports.studenttestquestion',[$test->exam->id]) }}">
                                            {{ $test->question_attempted ?? '' }}
                                        </a>
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