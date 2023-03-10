@extends('includes.master')

@section('pageheading', 'Dashboard')
@section('page-styles')
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
      <!-- fullCalendar -->
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jstree/themes/default/style.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

    <style>
      .fc-day:hover{background:lightblue;cursor: pointer;}
    </style>
@stop

@section('contentone')
       <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$pendingClaim}}</h3>

                <p>Pending  Audit</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-check"></i>
              </div>
              <a href="{{url('/claims')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$pendingSchemeRecept}}</h3>
                <p>Awaiting Scheme Receipt</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-clock"></i>
              </div>
              <a href="{{url('/schemes')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style="color:white;">{{$claimsWithIssueCount}}</h3>

                <p style="color:white;">Claims With Issues</p>
              </div>
              <div class="icon">
                <i class="fa fa-question-circle"></i>
              </div>
              <a href="{{url('/claim-with-issues')}}" style="color:white;" class="small-box-footer">More info <i class="fas fa-arrow-circle-right" style="color:white;"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$unProcessedCount}}</h3>

                <p>Claims Not Processed</p>
              </div>
              <div class="icon">
                <i class="fa fa-book-open"></i>
              </div>
              <a href="{{url('/unprocessed-claims')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <div class="row">
          <div class="col-md-8">
            @include('includes.claimmonth')
          </div>

          <div class="col-md-4">
              @include('includes.downloadformat')
              @include('includes.usefullinks')
              @include('includes.claimissues')
          </div>
        </div>

        
@stop


@section('page-scripts')
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('plugins/jstree/jstree.min.js')}}"></script>
{{-- <script src="{{asset('plugins/fullcalendar/main.js')}}"></script> --}}
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
       $("example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,
         "buttons": ["csv", "excel", "pdf", "print"]
       }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      //  $('#example2').DataTable({
      //    "paging": true,
      //    "lengthChange": false,
      //    "searching": false,
      //    "ordering": true,
      //    "info": true,
      //    "autoWidth": false,
      //    "responsive": true,
      //  });
     });
   </script>

<script>
  function changeEventHandler(event) {
   
       var urlParts = window.location.href.split("?");
       window.location.href = urlParts[0]+'?date='+event.target.value;
    }
</script>

@stop
