@extends('includes.master')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

  <script>
    var id=0;
    var claimid = ''
    var data = null;
    function showModal(data){
        data = data;
        $('#empname').text(data.name);
        $('#claimtype').text(data.claimtype);
        // $('#id').val(id);
        $('#modal-lg').modal('show');
    }
</script>
@stop

@section('pageheading', "Reports Breakdown")

@section('contentone')
<div class="row">
 <div class="col-12" >
            <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                     <h3 class="card-title"><strong>Reports Breakdown</strong></h3>
                </div>          
                <div class="card-body table-responsive ">

                    @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                    <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="example1" class="table table-bordered table-striped text-nowrap" >
                        <thead>
                        <tr>
                          <th>Employee Name</th>
                          <th>Claim Type</th>
                          <th>Amount(GHC)</th>
                          <th>Company</th>
                          <th>Account No.</th>
                          <th>Cheque No.</th>
                        </tr>
                        </thead>
                        <tbody>
                         @foreach ($employees as $emp)
                           <tr>
                                <td>
                                    {{$emp->name}}
                                </td>
                                <td>{{$emp->claimtype}}</td>
                                {{-- <td>{{$claim->scheme->name}}</td> --}}
                                <td>{{$emp->amount}}</td>
                                <td>{{$emp->company}}</td>
                                <td>{{$emp->accnumber}}</td>
                                <td>{{$emp->cheque_number}}</td>
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
                <h4 class="modal-title">Employee Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th>Name</th>
                            <td id="empname"></td>
                        </tr>
                        <tr>
                            <th>Claim Type</th>
                            <td id="claimtype"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
 
<script>
     $(function () {
       $("#example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,
         "buttons": ['excel', 'pdf', 'print']
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


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function() {
        document.querySelector('select[data-select-name]').onchange=changeEventHandler;
    },false);

    function changeEventHandler(event) {
        var urlParts = window.location.href.split("&");
        window.location.href = urlParts[0]+'&schemeid='+this.options[this.selectedIndex].value;
    }
</script>
@stop