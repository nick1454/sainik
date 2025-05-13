@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-description">
                        <h4 class="card-title">Student Test</h4>
                        <a class="btn btn-info mdi mdi-file-excel" href="{{ route('admin.reports.students') }}?download=1">
                            <i class="mdi mdi-file-excel"></i>
                            download
                        </a>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Test Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $key => $item)
                                <tr>
                                    <td class="py-1">
                                        {{ $key+1 }}
                                    </td>
                                    <td class="py-1">
                                        <a href="{{ route('admin.reports.studenttestattempt') }}{{ '?id='.$item->id }}">{{ $item->name }}</a>
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
