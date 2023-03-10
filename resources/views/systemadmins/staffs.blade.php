@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@stop

@section('contentone')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">All Staffs</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                  <table id="example1" class="table table-bordered table-striped nowrap">
                    <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Phone No</th>
                      <th>Department</th>
                      <th>Assigned Scheme</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach ($staffs as $staff)
                       <tr>
                            <td>{{$staff->firstname.' '.$staff->lastname}}</td>
                            <td>{{$staff->email}}</td>
                            <td>{{$staff->phoneno}}</td>
                            <td>{{$staff->department!=null?$staff->department->name:''}}</td>
                            <td>
                              <?php
                                  $permissions = $staff->permissions;
                                ?>
                                @if(count($permissions) <= 0)
                                    <p>Not A Scheme Admin</p>
                                @else
                                    @foreach ($permissions as $p)
                                      <span class="badge badge-success">{{$p->name}}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                              @role('system-admin')
                                <a href="{{url('/edit-staff?staffid='.$staff->id)}}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>
                                <button class="btn btn-xs btn-danger">Delete</button>
                              @endrole
                            </td>
                      </tr>
                     @endforeach
                   
                    </tbody>
                    
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
        </div>
    </div>
@stop

@section('page-scripts')
  <!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
@stop