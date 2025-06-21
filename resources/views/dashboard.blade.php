@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome {{ strtoupper($user->name) }}</h3>
          <!-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
        </div>
        <div class="col-12 col-xl-4">
          <div class="justify-content-end d-flex">
            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
              <form action="{{ route('admin.dashboard') }}" id='dashboard-data'>
                <input type="date" name='date' id="datepicker" onchange="getDashboardData()" value="{{ $selectedDate }}"/> 
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin transparent">
      <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">Total Students</p>
              <p class="fs-30 mb-2">{{ $totalStudent }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4">Total Test</p>
              <p class="fs-30 mb-2">{{ $totalExam }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Students Registered Today</p>
              <p class="fs-30 mb-2">{{ $registeredToday }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Test Attempts Today</p>
              <p class="fs-30 mb-2">{{ $attempsToday }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page_style')
<style>
  #datepicker {
    padding: 5px;
    border-radius: 5px;
  }
</style>
@endsection
@section('page_script')
<script>
  function getDashboardData() {
    document.getElementById('dashboard-data').submit(); 
  }
</script>
@endsection