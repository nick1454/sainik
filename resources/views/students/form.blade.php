@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card scrollable-div"  >
                <div class="card-body">
                  <h4 class="card-title">Add Student</h4>
                  @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>  
                  @endif
                  @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>  
                  @endif
                  <!-- <p class="card-description">
                    Basic form layout
                  </p> -->
                  <form class="forms-sample" method="post" action="{{ ($student && $student->id) ?  route('admin.student.update',[$student->id]) : route('admin.student.add') }}">
                    @csrf
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" placeholder="Name" name='name' value="{{ ($student && $student->name) ? $student->name: '' }}">
                      @error('name')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="father_name">Father's name</label>
                      <input type="text" class="form-control" id="father_name" placeholder="Father Name" name="father_name" value="{{ ($student && $student->father_name) ? $student->father_name: '' }}">
                      @error('father_name')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" placeholder="Email" name="email"value="{{ ($student) ? $student->user->email: '' }}">
                      @error('email')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="add_no">Admisson no.</label>
                      <input type="text" class="form-control" id="add_no" placeholder="Admission No" name="add_no" value="{{ ($student && $student->add_no) ? $student->add_no: '' }}">
                      @error('add_no')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="class">Class</label>
                      <select class="form-control" id="class" name="class">
                        <option value="">Select</option>
                        @foreach ($classes as $key => $value)
                        <option value="{{ $value }}" @if ($student && $student->class==$value) selected @endif>{{ $value }}</option>
                        @endforeach
                      </select>
                      @error('class')
                          <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a class="btn btn-light" href="{{ route('admin.student.form') }}">Cancel</a>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-7 grid-margin">
              <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Test List</h4>
                    <p class="card-description">
                      List of all the Students available.
                    </p>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Father</th>
                            <th>Email</th>
                            <th>Addmission No</th>
                            <th>Class</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($students as $item)
                          <tr>
                            <td>{{ ucfirst($item->name) }}</td>
                            <td>{{ ucfirst($item->father_name) }}</td>
                            <td>{{ $item->user->email ?? '' }}</td>
                            <td>{{ $item->add_no }}</td>
                            <td>{{ $item->class }}</td>
                            <td>
                              <a href="{{ route('admin.student.form.edit', [$item->id]) }}" class="btn btn-primary btn-sm">edit</a>
                              <a href="{{ route('admin.student.destroy',[$item->id]) }}" class="btn btn-danger btn-sm">delete</a>
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