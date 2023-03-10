@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">


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

@section('pageheading', "Search Claim")

@section('contentone')
<div class="row">
 <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Search Claim</h3>
                </div>                 
                <div class="card-body">
                    
                        <form class="{{url('/saerch-company')}}" method="GET">
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
                                            <label for="firstname">Company</label>
                                           <select class="form-control select2bs4" name="company">
                                                <option value="null">Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option {{request()->query('company') == $company->id ? 'selected="selected"' : ''}} value="{{$company->id}}">{{$company->name}}</option>
                                                @endforeach
                                           </select>
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" value="{{request()->query('startdate')}}" name="startdate" id="startdate"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" value="{{request()->query('enddate')}}" name="enddate" id="enddate"/>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
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
                          <th>Amount (GHC)</th>
                          <th>State</th>
                          <th>Audit Status</th>
                          <th>Payment Status</th>
                          <th>Date Created</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                           
                               @foreach ($claims as $claim)
                                    <tr>
                                        <td>
                                            <div style="flex-direction:row;  align-items:center">
                                                <i class="fa fa-folder-open" style="color:chocolate; font-size: 20px;"></i>
                                                {{$claim->claimid}}
                                            </div>
                                        </td>
                                        <td>{{$claim->company->name}}</td>
                                        <td>{{$claim->scheme->name.' '. $claim->scheme->tiertype}}</td>
                                        <td>{{$claim->claim_amount}}</td>
                                        <td>
                                            @if($claim->processed)
                                                <span class="badge badge-primary">Processed</span>
                                            @else
                                                <span class="badge badge-danger">Not Processed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($claim->audited)
                                            <span class="badge badge-success">Audited By {{$claim->audited_by}}</span>
                                            @else
                                             <span class="badge badge-danger">Not Audited</span>
                                            @endif
                                          </td>
                                          <td>
                                            @if($claim->paid)
                                            <span class="badge badge-success">Received</span>
                                            @else
                                             <span class="badge badge-danger">Not Received</span>
                                            @endif
                                          </td>
                                        <td>{{date('Y-m-d H:i A',strtotime($claim->created_at))}}</td>
                                        <td>
                                            
                                             {{-- <a href="{{url('/claim-files/tinymce5')}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-file"></i> Browse Files</a> --}}
                                            @if(Auth::user()->hasRole('audit') && $claim->audited==false)
                                                <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-dark"><i class="fa fa-upload"></i> Audit</a>
                                            @endif

                                            
                                            <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-users"></i> Employees</a>
                                            <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-xs btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                                            @if(!$claim->audited)
                                                <button class="btn btn-xs bg-danger btn-flat" onclick="showModal('{{$claim->id}}', '{{$claim->claimid}}')"><i class="fa fa-info-circle"></i> Report Issue</button>
                                            @endif
                                            @role('accounting')
                                                @if($claim->audited && !$claim->paid )
                                                    <button onclick="receiveClaim({{$claim->id}})"
                                                        class="btn btn-xs btn-flat btn-primary"><i class="fa fa-upload"></i> Receive Claim</button>
                                                @endif
                                            @endrole

                                            <a href="{{url('/claim?claimid='.$claim->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View Details</a>
                                            
                                        </td>
                                    </tr>
                               @endforeach
                             
                        </tbody>
                      </table>  
                </div>
            </div>
            @endif
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
 <!-- Select2 -->
 <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
    //Initialize Select2 Elements
    $('.select2bs4').select2({
    theme: 'bootstrap4'
    })
 </script>

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

<script>
    function receiveClaim (claimid) {
    Swal.fire({
        title: 'Receive Claim Now?',
        text: "Are your sure? Action cannot be undone!!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Mark as Received!'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('/receive-claim')}}",
                method: "POST",
                data: {claimid: claimid, _token: "{{Session::token()}}"},
                success: function(response){
                    if(response.status == 'success'){
                        Swal.fire(
                        'Success',
                         response.message,
                        'success'
                        ).then(()=> {
                            window.location.reload()
                        })
                    }else{
                       Swal.fire(
                        'Error!!!',
                         response.message,
                        'error'
                        )
                    }
                },
                error: function(error){
                   Swal.fire(
                    'Error!!!',
                    'Oops, unable to receive claim. please try again',
                    'error'
                   ) 
                }
            })
        }
        })
}
</script>
@stop