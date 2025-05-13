@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card scrollable-div"  >
                <div class="card-body">
                  <h4 class="card-title">Change Password</h4>
                  @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>  
                  @endif
                  @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>  
                  @endif
                  <!-- <p class="card-description">
                    Basic form layout
                  </p> -->
                  <form class="forms-sample" method="post" action="{{ route('change.password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" 
                            class="form-control" 
                            id="name" 
                            placeholder="Password"
                            name='password' 
                            value=""
                        />
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" 
                            class="form-control" 
                            id="name" 
                            placeholder="Confirm Password" 
                            name='con_pass' 
                            value=""
                        />
                        @error('con_pass')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">New Password</label>
                        <input type="password" 
                            class="form-control" 
                            id="name" 
                            placeholder="New Password" 
                            name='new_pass' 
                            value=""
                        />
                        @error('new_pass')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a class="btn btn-light" href="">Cancel</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
@endsection