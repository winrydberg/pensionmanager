@extends('includes.master')

@section('page-styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

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

@section('pageheading', "Company Reports")

@section('contentone')
<div class="row">
 <div class="col-12" >
            <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                    <h3 class="card-title">Generate Reports</h3>
                </div>          
                <div class="card-body table-responsive ">
                    <div class="col-md-12">
                        <form action="{{url('/company-reports')}}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" required class="form-control" value="{{request()->query('startdate')}}" name="startdate" id="startdate"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" required class="form-control" value="{{request()->query('enddate')}}" name="enddate" id="enddate"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select class="form-control select2bs4" name="company" required>
                                            <option value="" disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <br/>
                                       <button class="btn bg-purple btn-flat btn-block"><i class="fa fa-paper-plane"></i> Generate Report</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                      
                </div>
                

            </div>
 </div>

 </div>

 <div class="row">
       @isset($claims)
    <div class="col-12" >
                <!-- general form elements -->
                <div class="card card-primary card-outline" >     
                    <div class="card-header">
                        <h3 class="card-title">Generate Company Reports</h3>
                    </div>          
                    <div class="card-body table-responsive ">
                         @if (Session::has('error'))
                          <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                            <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif

                 
                        @if(count($claims) > 0)
                           <hr />
                          
                                <p><strong>Company Name: </strong> {{$selcompany->name}}</p>
                                <p><strong>Total Amount(GHC): </strong> {{$total_amount}}</p>
                           <hr>
                                <a href="{{url('/excel-reports?company='.request()->query('company').'&startdate='.request()->query('startdate').'&enddate='.request()->query('enddate'))}}" class="btn btn-success btn-md btn-flat"><i class="fa fa-save"></i> Export To Excel</a>
                           <hr/>
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <table id="example1" class="table table-bordered table-striped text-nowrap" >
                                    <thead>
                                    <tr>
                                    <th>Month, Year</th>
                                    <th>Company</th>
                                    <th>Amount (GHC)</th>
                                    <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($claims as $claim)
                                    <tr>
                                            <td>{{$claim->months}}</td>
                                            <td>{{$selcompany->name}}</td>
                                            <td>{{$claim->sums}}</td>
                                            <td>
                                                <a href="{{url('/reports-breakdown?month='.$claim->months.'&company='.$selcompany->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-folder-open"></i> Breakdown</a>
                                                {{-- <a href="#" class="btn btn-success btn-xs"><i class="fa fa-user"></i>Breakdown By Employee</a> --}}
                                            </td>
                                           
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    
                                </table>   
                            </div>  
                        @else
                            <p>No received claims found for selected date and company</p>
                        @endif
                    
                    </div>
                </div>
    </div>
    @endisset
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
 <script>
    //Initialize Select2 Elements
    $('.select2bs4').select2({
    theme: 'bootstrap4'
    })
 </script>
 <script>
     $(function () {
       $("#example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,
       }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
     });
   </script>

@stop