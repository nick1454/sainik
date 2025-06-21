@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
              <h4 class="card-title">Fee Structure List.</h4>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Class</th>
                      <th>Fee Structure</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($feeStructure as $item)
                    <tr>
                      <td style="line-height: 5px;">
                        <a href="{{ route('admin.feestructure.form.edit', [$item->id]) }}" class="btn btn-primary btn-sm">edit</a>
                        <button onclick="deleteEntry('{{ route('admin.feestructure.destroy',[$item->id]) }}')" class="btn btn-danger btn-sm">delete</button>
                      </td>
                      <td style="line-height: 5px;">
                        <div class="fee-block">Class: <b>{!! $item->class_id !!}</b></div>
                      </td>
                      <td style="line-height: 5px;">
                        <div class="fee-block">Admission No: <b>{!! $item->admission_fee !!}</b></div>
                        <br />
                        <div class="fee-block">Annual Fee: <b>{!! $item->annual_fee !!}</b></div>
                        <br />
                        <div class="fee-block">Tution Fee: <b>{!! $item->tution_fee!!}</b></div>
                        <br />
                        <div class="fee-block">Transport Fee: <b>{!! $item->transport_fee !!}</b></div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
        </div>
      </div>
      <div class="col-md-5 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Fee Structure</h4>
            @if (session()->has('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>  
            @endif
            @if (session()->has('success'))
              <div class="alert alert-success">{{ session('success') }}</div>  
            @endif

            <form class="" method='post' enctype="multipart/form-data" 
              action="{{ ($fee && $fee->id) ? route('admin.feestructure.update', [$fee->id]) : route('admin.feestructure.save') }}"
            >
              @csrf
              <div class="form-group">
                <label for="class">Class</label>
                <select class="form-control" id="class" name="class">
                  <option value="">Select</option>
                  @foreach ($classes as $key => $value)
                  <option value="{{ $value->id }}"
                    @if ($fee && $fee->class_id==$value->id) selected @endif
                  >
                    {{ $value->value }}
                  </option>
                  @endforeach
                </select>
                @error('class')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="que">Admission Fee</label>
                <input type="name" 
                  class="form-control" 
                  name="admission_fee" 
                  value="{{ ($fee && $fee->admission_fee) ? $fee->admission_fee : '' }}" 
                  placeholder="Addmission Fee"
                />
                @error('admission_fee')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="que">Annual Fee</label>
                <input type="name" 
                  class="form-control" 
                  name="annual_fee" 
                  value="{{ ($fee && $fee->annual_fee) ? $fee->annual_fee : '' }}" 
                  placeholder="Annual Fee"
                />
                @error('annual_fee')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="que">Tution Fee</label>
                <input type="name" 
                  class="form-control" 
                  name="tution_fee" 
                  value="{{ ($fee && $fee->tution_fee) ? $fee->tution_fee : '' }}" 
                  placeholder="Tution Fee"
                />
                @error('tution_fee')
                      <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="que">Transport Fee</label>
                <input type="name" 
                  class="form-control" 
                  name="transport_fee" 
                  value="{{ ($fee && $fee->transport_fee) ? $fee->transport_fee : '' }}" 
                  placeholder="Transport Fee"
                />
                @error('transport_fee')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary mr-2">
                {{ ($fee && $fee->id) ? 'Update' : 'Add' }}
              </button>
              <a class="btn btn-light" href="{{ route('admin.feestructure.form') }}">Reset</a>
            </form>
          </div>
        </div>
      </div>
  </div>
@endsection