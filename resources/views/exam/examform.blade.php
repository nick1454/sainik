@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-5 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Test</h4>
            <p class="card-description">
              Add New Test
            </p>
            @if (session()->has('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>  
            @endif
            @if (session()->has('success'))
              <div class="alert alert-success">{{ session('success') }}</div>  
            @endif

            <form class="" method='post' enctype="multipart/form-data" 
              action="{{ ($exam && $exam->id) ? route('admin.exam.update', [$exam->id]) : route('admin.exam.save') }}"
            >
              @csrf
              <div class="form-group">
                <label for="school">School</label>
                <select class="form-control" id="school" name="school">
                  <option value="">Select</option>
                  @foreach ($schools as $key => $value)
                  <option value="{{ $value }}"
                    @if ($exam && $exam->school==$value) selected @endif
                  >
                    {{ $value }}
                  </option>
                  @endforeach
                </select>
                @error('exam')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-group">
                <label for="que">Name.</label>
                <input type="name" 
                  class="form-control" 
                  name="name" 
                  value="{{ ($exam && $exam->name) ? $exam->name:'' }}" 
                  placeholder="Name"
                />
                @error('name')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                </div>
              </div>

              <button type="submit" class="btn btn-primary mr-2">
                {{ ($exam && $exam->id) ? 'Update' : 'Add' }}
              </button>
              <a class="btn btn-light" href="{{ route('admin.test.form') }}">Reset</a>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Test List</h4>
              <p class="card-description">
                List of all the test available.
                {{ $exam && $exam->name ? $exam->name : '' }}
              </p>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>School</th>
                      <th>Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($exams as $item)
                    <tr>
                      <td style="line-height: 20px;">
                        <a href="{{ route('admin.exam.form.edit', [$item->id]) }}" class="btn btn-primary btn-sm">edit</a>
                        <br>
                        <button onclick="deleteEntry('{{ route('admin.exam.destroy',[$item->id]) }}')" class="btn btn-danger btn-sm">delete</button>
                      </td>
                      <td style="line-height: 20px;">
                        {!! $item->school !!}
                      </td>
                      <td>
                        {!! $item->name !!}
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
@endsection