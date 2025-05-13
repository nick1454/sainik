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
              {{ session()->has('exam_id') ? session('exam_id') : '' }}
            </p>
            @if (session()->has('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>  
            @endif
            @if (session()->has('success'))
              <div class="alert alert-success">{{ session('success') }}</div>  
            @endif

            <form class="" method='post' enctype="multipart/form-data" 
              action="{{ ($test && $test->id) ? route('admin.test.update', [$test->id]) : route('admin.test.save') }}"
            >
              @csrf
              <div class="form-group">
                <label for="subject">Subject Name</label>
                <select class="form-control" id="subject" name="subject">
                  <option value="">Select</option>
                  @foreach ($subjects as $key => $value)
                  <option value="{{ $value }}"
                    @if (($test && $test->subject_name==$value) || (session()->has('test_subject') && session('test_subject') == $value)) selected @endif
                  >
                    {{ $value }}
                  </option>
                  @endforeach
                </select>
                @error('subject')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="que">Question.</label>
                <textarea class="form-control" id="que" rows="4" placeholder="Question" name="que">{{ ($test && $test->que) ? $test->que : ''  }}</textarea>
                <input type="file" class="form-control"  name="quef"/>
                @error('que')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="o1">Option 1</label>
                <input type="text" class="form-control" id="o1" placeholder="Option 1" name="o1" value="{{ ($test && $test->o1) ? $test->o1: '' }}">
                <input type="file" class="form-control"  name="o1f"/>
                @error('o1')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="o2">Option 2</label>
                <input type="text" class="form-control" id="o2" placeholder="Option 2" name="o2" value="{{ ($test && $test->o2) ? $test->o2: '' }}">
                <input type="file" class="form-control"  name="o2f"/>
                @error('o2')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="o3">Option 3</label>
                <input type="text" class="form-control" id="o3" placeholder="Option 3" name="o3" value="{{ ($test && $test->o3) ? $test->o3: '' }}" />
                <input type="file" class="form-control"  name="o3f"/>
                @error('o3')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="o4">Option 4</label>
                <input type="text" class="form-control" id="o4" placeholder="Option 4" name="o4" value="{{ ($test && $test->o4) ? $test->o4: '' }}">
                <input type="file" class="form-control" name="o4f"/>
                @error('o4')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="right_answer">Right Answer</label>
                <input type="text" class="form-control" id="right_answer" placeholder="Right Answer" name="right_answer" value="{{ ($test && $test->right_answer) ? $test->right_answer: '' }}">
                @error('right_answer')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="unseen_passage">Unseen Passage</label>
                <textarea type="text" class="form-control" id="unseen_passage" placeholder="Unseen Passage" name="unseen_passage">{{ (($test && $test->unseen_passage) ? $test->unseen_passage: '') }} {{ ((session()->has('unseen_passage') && session('unseen_passage')  && (!$test || !$test->unseen_passage)) ? session('unseen_passage'): '') }}</textarea>
              </div>
              <div class="form-group">
                <label for="directions">Directions</label>
                <textarea type="text" class="form-control" id="directions" placeholder="Direction" name="directions">{{ (($test && $test->directions) ? $test->directions: '') }} {{ ((session()->has('directions') && session('directions')  && (!$test || !$test->directions)) ? session('directions'): '') }}</textarea>
              </div>
              <div class="form-group">
                <label for="small_instructions">Small Instructions</label>
                <textarea type="text" class="form-control" id="small_instructions" placeholder="Small Instructions" name="small_instructions">{{ (($test && $test->small_instructions) ? $test->small_instructions : '') }} {{ ((session()->has('small_instructions') && session('small_instructions') && (!$test || !$test->small_instructions)) ? session('small_instructions'): '') }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary mr-2">{{ ($test && $test->right_answer) ? 'Update' : 'Add' }}</button>
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
              </p>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Subject</th>
                      <th>Question Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($questionList as $item)
                    <tr>
                    <td style="line-height: 20px;">
                        <a href="{{ route('admin.test.form.edit', [$item->id]) }}" class="btn btn-primary btn-sm">edit</a>
                        <br>
                        <a href="{{ route('admin.test.destroy',[$item->id]) }}" class="btn btn-danger btn-sm">delete</a>
                      </td>
                      <td>{{ ucfirst($item->subject_name) }}</td>
                      <td style="line-height: 20px;">
                        {!! $item->question() !!}
                        <br>
                        {!! $item->options() !!}
                        <br>
                        <b style="color:#81cc7f">{{ $item->rightAnswer() }}</b>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $questionList->links() }}
              </div>
            </div>
        </div>
      </div>
  </div>
@endsection