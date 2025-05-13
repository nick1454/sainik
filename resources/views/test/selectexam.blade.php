@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title" style="display:flex;">
                <div style="display:flex;align-items:center;">Test List</div>
              </h4>
              <p class="card-description">
                List of all the test available.
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
                        <a href="{{ route('admin.exam.form.edit', [$item->id]) }}" class="btn btn-info btn-sm">edit</a>
                        <button onclick="deleteEntry('{{ route('admin.exam.destroy',[$item->id]) }}')" class="btn btn-danger btn-sm">delete</button>
                        <a href="{{ route('admin.test.form') }}?exam_id={{ $item->id }}" class="btn btn-primary btn-sm">Select</a>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setExamIdForFileUpload({{ $item->id }})">
                          Excel Upload
                        </button>
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

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form action="{{ route('admin.test.excel') }}" enctype="multipart/form-data" method="post">
                  <input type="hidden" id="exam-id" name="examId">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Excel Upload</h5>
                      <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="file" class="form-control" name="file">
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-- End Modal -->
@endsection
@section('page_script')
  <script>
    function setExamIdForFileUpload(id) {
      console.log(id);
      document.getElementById('exam-id').value = id;
    }
  </script>
@endsection