@extends('includes.master')

@section('page-styles')
        <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@stop

@section('pageheading', "New Scheme")

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Add Scheme</h3>
              </div>

                <div class="card-body">
                    <form class="{{url('/new-scheme')}}" method="POST">
                        {{csrf_field()}}
                   <p>Register a new scheme</p>
                   <hr />
                    @if (Session::has('error'))
                        <p class="alert alert-error">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif

                    @include('includes.errordisplay')
              
                        <div class="form-group">
                            <label for="firstname">Scheme Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Scheme Name">
                        </div>
                        
                        <div class="form-group">
                            <label for="department_id">Region</label>
                            <select class="form-control select2bs4" name="tiertype" required>
                                <option value="null" selected="selected" disabled>Scheme Type</option>
                                <option value="Tier 2">Tier 2</option>
                                <option value="Tier 3">Tier 3</option>
                            </select>
                        </div>
                        <div class="form-group">
                           <div class="col-md-5 row">
                             <button type="submit" class="btn btn-success btn-block"><i class="fa fa-paper-plane"></i> Save Scheme</button>
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