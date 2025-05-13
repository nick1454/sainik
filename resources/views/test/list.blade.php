@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card" style="padding-left: 10px; padding-right: 10px;">
                <div class="card-body">
                <h4 class="card-title">Test List</h4>
                <p class="card-description">
                    <form action="{{ route('admin.test.list') }}">
                        <div class="form-group row">
                            <div class="col-3">
                                <input type="text" class="form-control" id="question" placeholder="Question" name="question" value="{{ request()->has('question') ? request()->question: '' }}">
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </p>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Subject</th>
                                <th style="max-width:300px !important;">Question Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questionList as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.test.form.edit', [$item->id]) }}" class="btn btn-primary btn-sm">edit</a>
                                    <br>
                                    <a href="{{ route('admin.test.destroy',[$item->id]) }}" class="btn btn-danger btn-sm">delete</a>
                                    <br>
                                    <!-- Button trigger modal -->
                                    <button 
                                        type="button" 
                                        class="btn btn-info btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#exampleModal-{{$item->id}}"
                                    >
                                        Preview
                                    </button>
                                </td>
                                <td>{{ ucfirst($item->subject_name) }}</td>
                                <td style="line-height:22px; word-wrap:break-word; max-width:300px; overflow:hidden;">
                                    <p class="white-space: normal;">{!! $item->question() !!}</p>
                                    <br>
                                    {!! $item->options() !!}
                                    <br>
                                    <b style="color:#81cc7f">{{ $item->rightAnswer() }}</b>
                                </td>
                                <td>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div style="padding:20px" class="row">
                                                <h3 style="white-space: normal; margin-bottom: 20px;" class="col-12">
                                                    Que:<br>
                                                    {!! $item->que !!}
                                                </h3>
                                                @if ($item->quef)
                                                <div class="col-12" 
                                                    style="height: 400px;"
                                                >
                                                    <img src="{{ asset('/storage/'.$item->quef) }}" 
                                                        style="width:inherit;height:100%;object-size:cover;border-radius: 0px;" 
                                                        alt=""
                                                    />
                                                </div>
                                                @endif
                                                <div class="col-12 row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="line-height: 22px; font-size:20px;">
                                                            {{ $item->qf1 }}
                                                            {{ $item->qf2 }}
                                                            {{ $item->qf3 }}
                                                            {{ $item->qf4 }}
                                                            {!! $item->options() !!}                                                         
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="color:#81cc7f; font-size: 20px;" class="col-12">
                                                    {{ $item->rightAnswer()}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                {{ $questionList->links() }}
            </div>
        </div>
    </div>
</div>

@endsection