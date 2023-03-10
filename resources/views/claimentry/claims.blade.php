@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

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

@section('pageheading', "Audit")

@section('contentone')
<div class="row">
 <div class="col-12" >
            <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                    <h3 class="card-title">Claims</h3>
                </div>          
                <div class="card-body table-responsive">
                        <div class="col-md-12">
                           
                           <form>
                             <div class="row">
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter By</label>
                                    <select class="form-control" name="filterby">
                                        <option value="0" {{request()->query('filterby')== 0 ? 'selected="selected"': ''}} >Not Audited</option>
                                        <option value="1" {{request()->query('filterby')==1 ? 'selected="selected"': ''}} >Audited</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input class="form-control" name="startdate" type="date" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input class="form-control" name="enddate" type="date" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                 <div class="form-group">
                                   <br />
                                    <button type="submit" class="btn btn-success btn-flat btn-block"><i class="fa fa-search"></i>Filter</button>
                                </div>
                            </div>

                            </div>
                           </form>
                           
                    </div>
                    <hr />
                    @if (Session::has('error'))
                          <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                            <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="example1" class="table table-bordered table-striped text-nowrap" >
                            <thead>
                            <tr>
                            <th>Claim ID</th>
                            <th>Description</th>
                            <th>Scheme</th>
                            <th>Amount (GHC)</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($claims as $claim)
                            <tr>
                                    <td>
                                        <div style="flex-direction:row; justify-content:center; align-items:center">
                                            <i class="fa fa-folder-open" style="color:rgb(238, 191, 120); font-size: 20px;"></i>
                                            <strong>{{$claim->claimid}}</strong>
                                        </div>
                                    </td>
                                    <td style="width:10%">{{$claim->description}}</td>
                                    <td>{{$claim->scheme->name}} - {{$claim->scheme->tiertype}}</td>
                                    <td>{{$claim->claim_amount}}</td>
                                    <td>
                                        @if($claim->processed)
                                            @if($claim->audited)
                                                 <span class="badge badge-success">Audited By {{$claim->audited_by}}</span>
                                            @else
                                                 <span class="badge badge-info">Not Audited</span>
                                            @endif
                                        @else
                                        <span class="badge badge-danger">Not Processed</span>
                                        @endif
                                    </td>
                        
                                    <td>{{date('Y-m-d H:iA', strtotime($claim->created_at))}}</td>
                                    <td>
                                        {{-- <a href="{{url('/claim-files/tinymce5')}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-file"></i> Browse Files</a> --}}
                                        @if(Auth::user()->hasRole('audit'))
                                            <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-dark"><i class="fa fa-upload"></i> Audit</a>
                                        @endif

                                        {{-- @if($claim->audited)
                                            <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-primary"><i class="fa fa-upload"></i> Download Audited</a>
                                        @endif --}}
                                        <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-users"></i> Employees</a>
                                        <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-xs btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                                        {{-- @if($claim->processed && !$claim->audited)
                                            <button class="btn btn-xs bg-warning btn-flat" onclick="showModal('{{$claim->id}}', '{{$claim->claimid}}')"><i class="fa fa-info-circle"></i> Report Issue</button>
                                        @endif --}}
                                        
                                        @if($claim->processed == false)
                                            @if(Auth::user()->hasRole('claim-entry'))
                                                <a href="{{url('/claim-files?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-warning"><i class="fa fa-upload"></i> Upload Claim Files</a>
                                            @endif
                                        @endif
                                        @if(!$claim->audited)
                                            <button class="btn btn-xs bg-danger btn-flat" onclick="deleteClaim('{{$claim->id}}')"><i class="fa fa-trash"></i> Delete Claim</button>
                                        @endif

                                         <a href="{{url('/claim?claimid='.$claim->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View Details</a>
                                        
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
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
 <script>
     $(function () {
       $("#example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,"ordering": false,
         "buttons": ["csv", "excel", "pdf", "print"]
       }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    //    $('#example2').DataTable({
    //      "paging": true,
    //      "lengthChange": false,
    //      "searching": false,
    //      "ordering": true,
    //      "info": true,
    //      "autoWidth": false,
    //      "responsive": true,
    //    });
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
                    Swal.fire(
                        'Success',
                         response.message,
                        'success'
                        ).then(()=> {
                            window.location.reload()
                    })
                //   $(document).Toasts('create', {
                //       class: 'bg-success',
                //       title: 'Success',
                //       // subtitle: 'Subtitle',
                //       body: response.message
                //   })
              }else{
                    Swal.fire(
                        'Error!!!',
                         response.message,
                        'error'
                    )
                //   $(document).Toasts('create', {
                //       class: 'bg-danger',
                //       title: 'Error',
                //       // subtitle: 'Subtitle',
                //       body: response.message
                //   })
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



function deleteClaim (id) {
    Swal.fire({
        title: 'Delete Claim Now?',
        text: "Are your sure? Action cannot be undone!!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('/delete-claim')}}",
                method: "POST",
                data: {id: id, _token: "{{Session::token()}}"},
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
                    'Oops, unable to delete claim. please try again',
                    'error'
                   ) 
                }
            })
        }
        })
}
</script>
@stop