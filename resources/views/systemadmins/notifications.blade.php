@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

<script>
    function showModal(message, id){

        $('#message').text(message);
        $('#modal-lg').modal({backdrop: 'static', keyboard: false});

        $.ajax({
            url: "{{url('mark-read')}}",
            method: "POST",
            data: {id:id, _token: "{{Session::token()}}"},
            success: function(response){
                console.log(response);
            },
            error: function(error){
                console.log(error);
            }
        })

        // $('#modal-lg').modal({backdrop: 'static', keyboard: false})
    }

      
</script>
@stop



@section('contentone')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">{{$title}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <a class="btn btn-sm btn-primary" href="{{url('/read-notifications')}}">My Read Notifications</a>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No. #</th>
                      <th>Ticket</th>
                      <th>Status</th>
                      <th>Activity</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach ($notifications as $no)
                       <tr>
                            <td>{{$no->id}}</td>
                            <td>{{$no->issue_ticket}}</td>
                            <td>
                                @if($no->read == true)
                                    <span class="badge badge-success"><i class=" fa fa-envelope-open"></i>Read</span>
                                @else
                                    <span class="badge badge-danger"><i class=" fa fa-envelope"></i> Not Read</span>
                                @endif
                            </td>
                            <td>{{$no->type}} 
                            </td>
                            <td>
                                <button class="btn btn-xs btn-danger" onclick="showModal('{{$no->message}}','{{$no->id}}')"><i class="fa fa-eye"></i> Notification Message</button>
                               
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


@section('contenttwo')
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Message </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p id="message"></p>                
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <a href="{{url('/claim?claimid='.$no->claim_id)}}" class="btn bg-purple">Claim Details</a>
                </div>
            </div>
        
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

<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        // "buttons": ["csv", "excel", "pdf", "print"]
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