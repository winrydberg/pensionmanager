@extends('includes.master')

@section('page-styles')
        <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@stop

@section('pageheading', "New Company")

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Register  Company</h3>
              </div>

              <!-- /.card-header -->
              <!-- form start -->
              
                
                <div class="card-body">
                    <form class="{{url('/new-company')}}" method="POST">
                        {{csrf_field()}}

                    @if (Session::has('error'))
                        <p class="alert alert-error">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif

                    @include('includes.errordisplay')
              
                        <div class="form-group">
                            <label for="firstname">Company Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Company Name">
                        </div>
                        {{-- <div class="form-group">
                            <label for="lastname">Company Phone No</label>
                            <input type="text" class="form-control" id="name" name="phoneno" placeholder="Company Phone No.">
                        </div>
                        <div class="form-group">
                            <label for="email">Company Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Compamy Email">
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="department_id">Region</label>
                            <select class="form-control select2bs4" name="region_id">
                                <option value="null" selected="selected" disabled>Select Company Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{$region->id}}">{{$region->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                           <div class="col-md-5 row">
                             <button type="submit" class="btn btn-success btn-block"><i class="fa fa-paper-plane"></i> Register Company</button>
                           </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  
                </div>
              
            </div>
 </div>

  <div class="col-md-4">
      <div class="card card-primary card-outline">
            <div class="card-body">
                <img src="{{asset('dist/img/company.png')}}" class="img-fluid"/>
            </div>
      </div>

      @include('includes.usefullinks')
  </div>
 </div>
@stop


@section('page-scripts')
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script>
        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
    </script>
@stop