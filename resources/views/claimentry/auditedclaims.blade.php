@extends('includes.master')

@section('pageheading', 'Audited Claims')

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

@section('contentone')
<div class="row">
 <div class="col-12" >
            <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                    <h3 class="card-title">Claims</h3>
                </div>          
                <div class="card-body table-responsive ">
                    <div class="col-md-12">
                        <form action="{{url('/audited-claims')}}">
                            <div class="col-md-12 row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                       <label>Filter By</label>
                                       <select class="form-control select2bs4" name="filterby" onchange="filterBy(event)">
                                            <option value="bycompany" {{request()->query('filterby')=='bycompany'?'selected="selected"':''}} >By Company</option>
                                            <option value="byscheme" {{request()->query('filterby')=='byscheme'?'selected="selected"':''}}>By Scheme</option>
                                       </select>
                                    </div>
                                </div>
                                <div id="bydate" class="col-md-6 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" required value="{{request()->query('startdate') == null ? $startdate : request()->query('startdate')}}" name="startdate" id="startdate"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" required value="{{request()->query('enddate') == null ? $enddate : request()->query('enddate')}}" name="enddate" id="enddate"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3" id="bycompany" {{request('filterby')=='byscheme'?'hidden':''}}>
                                    <div class="form-group">
                                        <label>Company</label>
                                       <select class="form-control select2bs4" name="company">
                                            <option value="0" disabled>Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{$company->id}}" {{request()->query('company') == $company->id?'selected="selected':''}}>{{$company->name}}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="byscheme" {{request('filterby')=='byscheme'?'':'hidden'}}>
                                    <div class="form-group">
                                       <label>Scheme</label>
                                       <select class="form-control select2bs4" name="scheme">
                                            <option value="0" disabled>Select Scheme</option>
                                            @foreach ($schemes as $scheme)
                                                <option value="{{$scheme->id}}" {{request()->query('scheme') == $scheme->id?'selected="selected':''}}>{{$scheme->name.' '.$scheme->tiertype}}</option>
                                            @endforeach
                                       </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                        
                                       <button class="btn btn-success btn-block btn-flat pull-right"><i class="fa fa-search"></i> Filter</button>
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
    <div class="col-12" >
        <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                    <h3 class="card-title">Audited Claims</h3>
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
                            <th>Claim ID</th>
                            <th>Description</th>
                            <th>Scheme</th>
                            <th>Amount(GHC)</th>
                            <th>Audit Status</th>
                            <th>Payment Status</th>
                            <th>Created Date</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($claims as $claim)
                            <tr>
                                    <td>
                                        <div style="flex-direction:row; justify-content:center; align-items:center">
                                            <i class="fa fa-folder-open" style="color:chocolate; font-size: 20px;"></i>
                                            <strong>{{$claim->claimid}}</strong>
                                        </div>
                                    </td>
                                    <td>{{$claim->description}}</td>
                                    <td>{{$claim->scheme->name}} - {{$claim->scheme->tiertype}}</td>
                                    <td>{{$claim->claim_amount}}</td>
                                    <td>
                                    @if($claim->audited)
                                    <span class="badge badge-success"><i class="fa fa-edit"></i> Audited By {{$claim->audited_by}}</span>
                                    @else
                                    <span class="badge badge-danger"><i class="fa fa-edit"></i> Not Audited</span>
                                    @endif
                                    </td>
                                    <td>
                                    @if($claim->paid)
                                    <span class="badge badge-success"><i class="fa fa-card"></i> Received</span>
                                    @else
                                    <span class="badge badge-danger"><i class="fa fa-card"></i> Not Received</span>
                                    @endif
                                    </td>
                                    <td>{{date('Y-m-d H:iA', strtotime($claim->created_at))}}</td>
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
                                        @if($claim->audited )
                                            
                                            <a href="{{url('/payment-info?claimid='.$claim->claimid)}}" class="btn btn-xs btn-flat btn-primary"><i class="fa fa-upload"></i> receive</a>
                                        @endif
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
    function filterBy(event){
        var value = event.target.value;
        if(value=='bycompany'){
            $('#bycompany').removeAttr('hidden');
            $('#byscheme').attr('hidden', 'hidden');
        }else if(value=='byscheme'){
            $('#byscheme').removeAttr('hidden');
            $('#bycompany').attr('hidden', 'hidden');
        }
    }
</script>
@stop