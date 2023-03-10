@extends('includes.master')

@section('page-styles')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@stop

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">New Staff</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="{{url('/new-staff')}}" method="POST">
                {{csrf_field()}}
                <div class="card-body">
                    @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif

                   
                 
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name">
                            </div>
                        </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Phone No</label>
                                    <input type="text" class="form-control" id="phoneno" name="phoneno" placeholder="Enter Phone No.">
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>

                       
                            <div class="form-group">
                            <label>Assign Roles</label>
                            <div class="select2-purple">
                                <select class="select2" id="role" name="roles[]" required multiple="multiple" data-placeholder="Select User Roles" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            </div>
                            <!-- /.form-group -->
                       
                         <div class="form-group">
                            <label for="department_id">Department</label>
                            <select class="form-control" name="department_id">
                                <option value="null" selected="selected" disabled>Select a Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        

                            <div class="form-group" id="schemeassignment" hidden>
                                <label>Assign to Scheme</label>
                                <div class="select2-purple">
                                    <select class="select2" name="permissions[]"  required multiple="multiple" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                    @foreach ($permissions as $permission)
                                        <option value="{{$permission->id}}">{{$permission->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /.form-group -->
                       

                        <div class="form-group">
                           <div class="col-md-6 row">
                             <button type="submit" class="btn btn-success btn-block"><i class="fa fa-paper-plane"></i> Register Staff</button>
                           </div>
                        </div>
          
                 </div>
                 
                </div>
                <!-- /.card-body -->
              
              </form>
 </div>
 <div class="col-md-4">
    <div class="card card-primary card-outline">
          <div class="card-body">
              <img src="{{asset('dist/img/avatar.png')}}" class="mx-auto d-block" class="img-fluid"/>
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
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    $('#role').on('change', function(event){
        var values = $('#role').val();
        var last = values[values.length -1 ];
        if(values.includes('4')){
            $('#schemeassignment').removeAttr('hidden');
        }else{
            $('#schemeassignment').attr("hidden",true);;
        }
    })
  })
</script>
@stop