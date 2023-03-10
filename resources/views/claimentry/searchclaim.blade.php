@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

   <!-- Toastr -->
   {{-- <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}"> --}}

  <script>
        var id=0;
        var claimid = ''
        function showModal(id, claimid){
            id=id;
            claimid = claimid;
            $('#claimid').val(claimid);
            $('#id').val(id);
            $('#modal-lg').modal('show');
        }
  </script>
@stop

@section('contentone')
<div class="row">
 <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Search Claim/Employee</h3>
                </div>                 
                <div class="card-body">
                    
                            <form class="{{url('/saerch-claim')}}" method="GET">
                                {{csrf_field()}}
                            @if (Session::has('error'))
                                <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                            @elseif (Session::has('success'))
                                <p class="alert alert-success">{!! Session::get('success') !!}</p>
                            @endif
        
                            @include('includes.errordisplay')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstname">Search By</label>
                                            <select class="form-control" name="searchby">
                                               
                                                <option {{request()->query('searchby')=='customer_name'?'selected="selected"':''}} value="customer_name">Employee Name</option>
                                                <option {{request()->query('searchby')=='claimid'?'selected="selected"':''}} value="claimid">Claim ID</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="firstname">Enter term to search</label>
                                            <input type="text" value="{{request()->query('term')}}" required class="form-control" id="term" name="term" required placeholder="Claim ID or Employee Name to search">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label></label>
                                              <button type="submit" style="margin-top: 8px;" class="btn btn-success btn-block btn-flat"><i class="fa fa-search"></i> Search Claim</button>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                               
                                
                            </form>
                      
                </div>
            </div>

            @if(isset($claims))
            <div class="card card-primary card-outline">     
                <div class="card-header">
                    <h3 class="card-title">Search Results</h3>
                </div>          
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped text-nowrap">
                        <thead>
                        <tr>
                          <th>Claim ID</th>
                          <th>Company</th>
                          <th>Scheme</th>
                          <th>Dept Reached</th>
                          <th>Status</th>
                          <th>Created Date</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- ISSET CLAIMS-->
                            @if(isset($claims))
                                @foreach ($claims as $claim)
                                    <tr>
                                        <td style="justify-content:center">
                                                <i class="fa fa-folder-open" style="color:rgb(238, 191, 120); font-size: 20px;"></i>
                                                <strong>{{$claim->claimid}}</strong>
                                        </td>
                                        <td>{{$claim->company->name}}</td>
                                        <td>{{$claim->scheme->name}} - {{$claim->scheme->tiertype}}</td>
                                        <td>{{$claim->departmentreached!=null?$claim->departmentreached->name:''}}</td>
                                        <td>
                                            @if($claim->state =='Uploaded')
                                                <span class="badge badge-primary">Uploaded</span>
                                            @elseif($claim->state =='Downloaded')
                                                <span class="badge badge-primary">Downloaded</span>
                                            @else
                                                <span class="badge badge-primary">Donwloaded</span>
                                            @endif
                                        </td>
                                        <td>{{date('Y-m-d H:i A', strtotime($claim->created_at))}}</td>
                                        <td>
                                            @if(Auth::user()->hasRole('audit'))
                                                    <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-dark"><i class="fa fa-upload"></i> Audit </a>
                                            @endif
                                            <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-users"></i> View Employees</a>
                                            <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-xs btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                                            <button class="btn btn-xs bg-danger btn-flat" onclick="showModal('{{$claim->id}}', '{{$claim->claimid}}')"><i class="fa fa-info-circle"></i> Report Issue</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                      </table>  
                </div>
            </div>
            @endif


            @if(isset($customers))
            <div class="card card-primary card-outline">     
                <div class="card-header">
                    <h3 class="card-title">Search Results</h3>
                </div>          
                <div class="card-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped text-nowrap">
                        <thead>
                        <tr>
                          <th>Claim ID</th>
                          <th>Customer Name</th>
                          <th>Scheme</th>
                          <th>Company</th>
                          <th>Status</th>
                          <th>Claim Created Date</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                               <tr>
                                    <td colspan="7"><strong>{{$customer->name}}</strong></td>
                               </tr>

                               @foreach ($customer->claims as $claim)
                                    <tr>
                                        <td>
                                            <div style="flex-direction:row;  align-items:center">
                                                <i class="fa fa-folder-open" style="color:rgb(238, 191, 120); font-size: 20px;"></i>
                                                {{$claim->claimid}}
                                            </div>
                                        </td>
                                        <td>{{$customer->name}}</td>
                                        <td>{{$claim->scheme->name}} - {{$claim->scheme->tiertype}}</td>
                                        <td>{{$claim->company->name}}</td>
                                        <td>
                                            @if($claim->processed)
                                                @if($claim->audited)
                                                    @if($claim->paid)
                                                        <span class="badge badge-success">Received</span>
                                                    @else
                                                        <span class="badge badge-info">Audited</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-primary">Processed</span>
                                                @endif
                                            @else
                                                    <span class="badge badge-danger">Not Processed</span>
                                            @endif
                                        </td>
                                        <td>{{date('Y-m-d H:i A',strtotime($claim->created_at))}}</td>
                                        <td>
                                            @if(Auth::user()->hasRole('audit'))
                                                    <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-dark"><i class="fa fa-upload"></i> Audit </a>
                                            @endif
                                            <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-users"></i> View Employees</a>
                                            <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-xs btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                                            {{-- <button class="btn btn-xs bg-danger btn-flat" onclick="showModal('{{$claim->id}}', '{{$claim->claimid}}')"><i class="fa fa-info-circle"></i> Report Issue</button> --}}
                                            <a href="{{url('/claim?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-info"><i class="fa fa-eye"></i>View Details</a>
                                            
                                        </td>
                                    </tr>
                               @endforeach
                                
                            @endforeach
                        </tbody>
                      </table>  
                </div>
            </div>
            @endif
 </div>

 </div>
@stop

@section('contenttwo')
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <form id="issueForm" action="POST">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Report An Issue on Claim </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label> ID</label>
                        <input class="form-control" readonly name="id" id="id" />
                    </div>

                    <div class="form-group">
                        <label>Claim ID</label>
                        <input class="form-control" readonly name="claimid" id="claimid" />
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" rows="5" required id="message"></textarea>
                        <small>Enter a short description of issue on claim.</small>
                    </div>
                
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit Issue</button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

 <!-- Toastr -->
{{-- <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script> --}}
 
<script>
     $(function () {
       $("#example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,
         "buttons": ["excel", "pdf", "print"]
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

<script>
    $('#issueForm').submit(function(e){
        e.preventDefault();
        var claimid2 = $('#id').val();
        var message = $('#message').val();

        console.log(claimid2);
        $.ajax({
            url: "{{url('/report-issue')}}",
            method: "POST",
            data: {claimid: claimid2, message: message, _token:"{{Session::token()}}"},
            success: function(response){
                $('#issueForm').trigger('reset')
                $('#modal-lg').modal('hide');
                // alert(JSON.stringify(response));
                if(response.status =='success'){
                    
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        // subtitle: 'Subtitle',
                        body: response.message
                    })
                }else{
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        // subtitle: 'Subtitle',
                        body: response.message
                    })
                }
            },
            error: function(error){
                alert(error.message);
                $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        // subtitle: 'Subtitle',
                        body: "Oops something went wrong. Please try again"
                })
            }
        })
    })
</script>
@stop