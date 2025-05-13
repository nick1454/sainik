@extends('layouts.student')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
        </div>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Student Test</h4>
                    <p class="card-description">
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>UserName</th>
                                    <th>Exam</th>
                                    <th>No Of Attempts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testList as $test)
                                <tr onclick="openQuestionPaper({{ $test->id }},{{ $test->exam_id }})" title="click to check result">
                                    <td class="py-1">
                                        {{ $test->user->name ?? '' }}
                                    </td>
                                    <td>
                                        <!-- <a href="{{ route('admin.reports.studenttest', [$test->exam->id ?? '']) }}"> -->
                                            {{ $test->exam->name ?? '' }}
                                        <!-- </a> -->
                                    </td>
                                    <td>
                                        {{ $test->attempt_no ?? '' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $testList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page_script')
<script>
    function openQuestionPaper(id,examId)
    {
        console.log(id,examId);
        var uri = "/student/reports/"+examId+"/student-test-question?id="+id
        window.location.href = uri;
    }
</script>
@endsection