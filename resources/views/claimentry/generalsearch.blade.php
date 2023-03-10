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

@section('contentone')
<div class="row">
 <div class="col-12" >
            <!-- general form elements -->
            <div class="card card-primary card-outline" >     
                <div class="card-header">
                   @if(isset($scheme))
                     <h3 class="card-title">Claims for <strong>{{$date}} - {{$scheme->name}}</strong></h3>
                   @else
                     <h3 class="card-title">Claims for <strong>{{$date}}</strong></h3>
                   @endif
                </div>          
                <div class="card-body table-responsive ">

                    @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                    <form>
                        <div class="col-md-5">
                            <div class="form-group" >
                                <label>Filter By Scheme</label>
                                <select class="form-control" name="schemeid" data-select-name>
                                    <option value="null">Select Scheme</option>
                                    @if(isset($scheme))
                                        @foreach ($schemes as $sch)
                                            <option value="{{$sch->id}}" {{$scheme->id == $sch->id?'selected="selected"':''}}>{{$sch->name}}</option>
                                        @endforeach
                                    @else
                                        @foreach ($schemes as $sch)
                                            <option value="{{$sch->id}}">{{$sch->name}}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="dataTables_wrapper dt-bootstrap4">
                    <table id="example1" class="table table-bordered table-striped text-nowrap" >
                        <thead>
                        <tr>
                          <th>Claim ID</th>
                          <th>Company</th>
                          {{-- <th>Scheme</th> --}}
                          <th>Dept Reached</th>
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
                                <td>{{$claim->company->name}}</td>
                                {{-- <td>{{$claim->scheme->name}}</td> --}}
                                <td>{{$claim->departmentreached!=null?$claim->departmentreached->name:''}}</td>
                                <td>
                                  @if($claim->processed )
                                    <span class="badge badge-success">Processed</span>
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
                                    <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-xs btn-flat btn-success"><i class="fa fa-users"></i> View Beneficiaries</a>
                                    <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-xs btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                                    <button class="btn btn-xs bg-danger btn-flat" onclick="showModal('{{$claim->id}}', '{{$claim->claimid}}')"><i class="fa fa-info-circle"></i> Report Issue</button>
                                </td>
                          </tr>
                         @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                          <th>Claim ID</th>
                          <th>Company</th>
                          {{-- <th>Scheme</th> --}}
                          <th>Dept Reached</th>
                          <th>Status</th>
                          <th>Created Date</th>
                          <th>Action</th>
                        </tr>
                        </tfoot>
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
 
<script>
     $(function () {
       $("#example1").DataTable({
         "responsive": true, "lengthChange": false, "autoWidth": false,
         "buttons": []
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