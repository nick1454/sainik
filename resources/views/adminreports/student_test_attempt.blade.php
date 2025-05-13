@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Student Test</h4>
                    <p class="card-description">
                        <a class="btn btn-info" href="?download=1{{ request()->has('id') && request()->id ? '&id='.request()->id : ''}}">
                            <i class="icon-excel menu-icon"></i>
                            Download
                        </a>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>UserName</th>
                                    <th>Exam</th>
                                    <th>Attempts No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testList as $test)
                                <tr onclick="openQuestionPaper({{ $test->id }})">
                                    <td class="py-1">
                                        {{ $test->user->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $test->exam->name ?? '' }}
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
    function openQuestionPaper(id)
    {
        console.log(id);
        window.location.href = "{{ route('admin.reports.studenttestquestion') }}"+'?id='+id;
    }
</script>
@endsection