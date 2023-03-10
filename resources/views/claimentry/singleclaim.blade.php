@extends('includes.master')


@section('page-styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/plugins/fullcalendar/main.css')}}">

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

<script>
    var id=0;
    var filename = ''
    function showFileIssueModal(id, filename){
        id=id;
        filename = filename;
        $('#filename').val(filename);
        $('#id').val(id);
        $('#fileIssue').modal('show');
    }
</script>

<script>
    function commentEditModal(id){
        $('#edit-comment').modal('show');
    }
</script>

 <script>
    
    function showIssueModal(message){
        $('#issuemessage').html(message);
        $('#issue-modal').modal('show');
    }
</script>
@stop

@section('pageheading')
  {{$claim->claimid}} Details
@stop

@section('contentone')
<div class="col-md-12 row ">
    @if (Session::has('error'))
          <p class="alert alert-danger">{!! Session::get('error') !!}</p>
    @elseif (Session::has('success'))
            <p class="alert alert-success">{!! Session::get('success') !!}</p>
    @endif
</div>
<div class="row">
    
    <div class="col-md-5">
        <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Claim Details</h3>
                </div>                 
                <div class="card-body">
                  <table class="table table-striped table-hover">
                    <tr>
                      <td><strong>Claim ID</strong></td>
                      <td>{{$claim->claimid}}</td>
                    </tr>

                    <tr>
                      <td><strong>Description</strong></td>
                      <td>{{$claim->description}}</td>
                    </tr>

                    <tr>
                      <td><strong>Company</strong></td>
                      <td>{{$claim->company->name}}</td>
                    </tr>

                    <tr>
                      <td><strong>Department Reached</strong></td>
                      <td>{{$claim->department_reached}}</td>
                    </tr>

                    <tr>
                      <td><strong>Process Status</strong></td>
                      @if($claim->processed)
                        <td><span class="badge badge-primary">Processed</span></td>
                      @else
                        <td><span class="badge badge-danger">Not Processed</span></td>
                      @endif
                    </tr>

                     <tr>
                      <td><strong>Audit Status</strong></td>
                      @if($claim->audited)
                        <td><span class="badge badge-success">Audited</span></td>
                      @else
                        <td><span class="badge badge-dark">Not Audited</span></td>
                      @endif
                    </tr>

                    <tr>
                      <td><strong>Scheme Admin Receive Status</strong></td>
                      @if($claim->paid)
                        <td><span class="badge badge-success">Received</span></td>
                      @else
                        <td><span class="badge badge-danger">Not Received</span></td>
                      @endif
                    </tr>

                    
                      <tr>
                        <td><strong>Validity Status</strong></td>
                          @if(!$claim->active)
                             <td><span class="badge badge-danger"><i class="fa fa-check-circle"></i> Not valid </span></td>
                          @else
                           <td><span class="badge badge-success"><i class="fa fa-check-circle"></i> Valid </span></td>
                          @endif
                      </tr>
                      @if(!$claim->active)
                        <tr>
                          <td><strong>Invalid Reason</strong></td>
                          <td> {{$claim->invalid_reason}} </td>
                        </tr>
                      @endif
                  

                    <tr>
                      <td><strong>Amount (GHC)</strong></td>
                      <td>{{$claim->claim_amount}}</td>
                    </tr>

                    <tr>
                      <td><strong>Created Date</strong></td>
                      <td>{{date('Y-m-d H:iA', strtotime($claim->created_at))}}</td>
                    </tr>

                    <tr>
                      <td><strong>Comment</strong></td>
                      <td>{{$claim->comment}} <button style="margin-left: 20px;" onclick="commentEditModal()" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit Now</button></td>
                    </tr>

                    @if($claim->audited)
                      <tr>
                        <td><strong>Audited By</strong></td>
                        <td><span class="badge badge-success"><i class="fa fa-check-circle"></i> {{$claim->audited_by}} </span></td>
                      </tr>
                    @endif
                  </table>


                  <hr />

                  <div class="row">
                    <div class="col-md-12" style="margin-top: 10px;">
                        <a href="{{url('/download/'.$claim->id.'/'.$claim->claimid)}}" class="btn btn-sm btn-block btn-flat bg-purple"><i class="fa fa-download"></i> Download Claim Files</a>
                        <a href="{{url('/customers?claimid='.$claim->id)}}" class="btn btn-sm btn-block btn-flat btn-success"><i class="fa fa-users"></i> Employees</a>
                        @if(Auth::user()->hasRole('audit') && $claim->audited==false && $claim->processed)
                            <a href="{{url('/audit?claimid='.$claim->claimid)}}" class="btn btn-sm btn-block btn-flat btn-dark"><i class="fa fa-upload"></i> Audit</a>
                        @endif 
                        
                        @if(Auth::user()->hasRole('claim-entry'))
                            <a href="{{url('/claim-files?claimid='.$claim->claimid)}}" class="btn btn-sm btn-block btn-flat btn-warning"><i class="fa fa-upload"></i> Upload Request/Additional Files</a>
                            @if($claim->processed == false)
                                <a href="{{url('/processed-files?claimid='.$claim->claimid)}}" class="btn btn-sm btn-block btn-flat btn-info"><i class="fa fa-upload"></i> Upload Processed Files</a>
                            @endif
                        @endif

                        @role('accounting')
                            @if($claim->audited && !$claim->paid)
                                <button onclick="receiveClaim({{$claim->id}})" class="btn btn-sm btn-block btn-flat btn-primary"><i class="fa fa-upload"></i> Receive Claim</button>
                            @endif

                            @if($claim->paid)
                              <button onclick="transferToBank({{$claim->id}})" class="btn btn-sm btn-block btn-flat btn-primary"><i class="fa fa-upload"></i> Transfered To Bank</button>
                            @endif
                        @endrole

                        @if(!$claim->processed && $claim->active==true)
                            <button class="btn btn-sm btn-block bg-danger btn-flat" data-toggle="modal" data-target="#invalidModal"><i class="fa fa-info-circle"></i> Mark As Invalid</button>
                        @endif

                         @if(!$claim->active)
                            <button onclick="markAsValid()" class="btn btn-sm btn-block bg-orange btn-flat"><i class="fa fa-save"></i> Mark As Valid</button>
                        @endif
                    </div>
                  </div>
                </div>
        </div>

        
        <div class="card card-danger card-outline">
            <div class="card-header">
              <h3 class="card-title">Issues</h3>
            </div>
            <div class="card-body">
                @if(isset($issues) && count($issues) >0)
                 @if (Session::has('downloaderror'))
                          <p class="alert alert-danger">{!! Session::get('downloaderror') !!}</p>
                  @endif
                <div class="table-responsive">
                    <table class="table table-hover text-wrap">
                        <thead>
                            <tr>
                                <th>Issue Raised</th>
                                <th>On File</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach ($issues as $issue)
                                <tr>
                                    
                                    <td style="white-space: nowrap;text-overflow:ellipsis; overflow:hidden;max-width: 20px;"  >{{$issue->message}}</td>
                                    <td>
                                      @if($issue->claim_file != null)
                                         <?php
                                      
                                          $dirarray = explode('/', $issue->claim_file->filename);
                                          $filename = $dirarray[count($dirarray)-1];
                                          echo $filename;
                                      ?>
                                      @endif
                                    </td>
                                    <td>
                                      @if($issue->resolved)
                                        <span class="badge badge-success">Resolved</span>
                                      @else
                                        <span class="badge badge-danger">Not Resolved</span>
                                      @endif
                                    </td>
                                    <td>
                                        <button type="button" onclick="showIssueModal('{{$issue->message}}')" class="btn btn-xs btn-flat btn-success" style="margin-right: 5px;"><i class="fa fa-eye"></i></button>
                                        @if($issue->resolved)
                                          <a href="{{url('/reviewfiles-download/'.$issue->id)}}" class="btn btn-xs bg-purple btn-flat"><i class="fa fa-download"></i> Download Reviewed File(s)</a>
                                        @endif
                                    </td>
                                     
                                      </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
                @else
                  <div class="">
                    <p>No Issues Reported On Claim</p>
                  </div>
                @endif
                </div>
        </div>
    </div>

    <div class="col-md-7">

        <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                    <h3 class="card-title"></h3>
              </div> 
              <div class="card-body">
                <!-- THE CALENDAR -->
                 <div class="row">
                  <div class="col-md-12">
                    <!-- The time line -->
                    <div class="timeline">
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-red">CLAIM ACTIVIES</span>
                      </div>
                      <!-- /.timeline-label -->
                      @foreach ($activities as $activity)
                        <!-- timeline item <i class="fas fa-hand-point-right"></i> -->
                      <div>
                        <i class="fas fa-hand-point-right bg-purple"></i>
                        <div class="timeline-item">
                          <span class="time" style="font-size: 16px"><i class="fa fa-clock"></i> {{date('d-m-Y H:i A', strtotime($activity->created_at))}}</span>
                          <h3 class="timeline-header"><a href="#">{{$activity->description}}</a> </h3>

                          <div class="timeline-body">
                             <p>{{$activity->description}} By <span class="badge badge-dark">{{$activity->causer->firstname.' '.$activity->causer->lastname }}</span></p> 
                          </div>
                          {{-- <div class="timeline-footer">
                            
                          </div> --}}
                        </div>
                      </div>
                      <!-- END timeline item -->
                      @endforeach
                      
                    
                      {{-- <div>
                        <i class="fas fa-clock bg-gray"></i>
                      </div> --}}
                    </div>
                  </div>
                <!-- /.col -->
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>



         <div class="col-md-12">
          <div class="card card-primary card-outline" >     
                <div class="card-header">
                    <h3 class="card-title">Claim Files</h3>
                </div>          
                <div class="card-body table-responsive">
                    @if (Session::has('error'))
                          <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                            <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <table id="example1" class="table table-bordered table-striped text-nowrap" >
                            <thead>
                            <tr>
                            <th>File Type</th>
                            <th>File Name</th>
                            <th>Has Issue</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($claim->claim_files as $file)
                            <tr>
                                    <td style="width: 20px;">
                                        @if(in_array(strtolower($file->extension), ['png', 'jpg', 'jpeg']))
                                            <img src="{{asset('dist/img/filestypes/image.png')}}" style="width: 40px;"/>
                                        @elseif (strtolower($file->extension) =='pdf')
                                            <img src="{{asset('dist/img/filestypes/pdf.png')}}" style="width: 40px;"/>
                                        @elseif (in_array(strtolower($file->extension), ['xls', 'xlsx']))
                                            <img src="{{asset('dist/img/filestypes/sheets.png')}}" style="width: 40px;"/>
                                        @elseif (in_array(strtolower($file->extension), ['doc', 'docx']))
                                            <img src="{{asset('dist/img/filestypes/word.png')}}" style="width: 40px;"/>
                                        @else
                                            <img src="{{asset('dist/img/filestypes/file.png')}}" style="width: 40px;"/>
                                        @endif
                                    </td>
                                    <td >
                                      <?php
                                      
                                          $dirarray = explode('/', $file->filename);
                                          $filename = $dirarray[count($dirarray)-1];
                                          echo $filename;
                                      ?>
                                    </td>

                                     <?php
                                            // $showIssuebtn = true;
                                            $hasIssue = false;
                                            $fileIssues = $file->issues;

                                            foreach ($fileIssues as $iss) {
                                              if($iss->resolved ==false){
                                              
                                                $hasIssue = true;
                                                break;
                                                
                                              }else{
                                                $hasIssue = false;
                                        
                                              }
                                            }
                                      ?>

                                    <td>
                                      @if($hasIssue==false)
                                          <p>No</p>
                                      @else
                                          <p>Yes</p>
                                      @endif
                                    </td>
                                   
                                    <td>
                                        @if($hasIssue==false)
                                            <button onclick="showFileIssueModal('{{$file->id}}', '{{$filename}}')" {{$claim->paid ==true?'disabled="disabled"': ''}} class="btn btn-xs btn-warning"><i class="fa fa-info-circle"></i> Report Issue</button>
                                        @endif
                                         
                                        @if($hasIssue == true)
                                            <a href="{{url('/issue-review?ticket='.$file->unresolved_issue()?->issue_ticket)}}" class="btn btn-xs bg-purple"><i class="fa fa-info-circle"></i> Resolve Issue</a>
                                        @else
                                        @endif
                                        <button onclick="deleteClaimFile('{{$file->id}}')" {{$claim->paid ==true?'disabled="disabled"': ''}} class="btn btn-flat btn-danger btn-xs"><i class="fa fa-trash"></i> Delete File</button>
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
</div>

<div class="modal fade" id="fileIssue">
    <div class="modal-dialog fileIssue">
        <form id="fileIssueForm" action="POST">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Report An Issue on Claim File </h4>
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
                        <label>File Name</label>
                        <input class="form-control" readonly name="filename" id="filename" />
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" rows="5" required id="messagefile"></textarea>
                        <small>Enter a short description of issue on claim file.</small>
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
</div>


  
<div class="modal fade" id="issue-modal">
    <div class="modal-dialog issue-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Issue Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="issuemessage"></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>

  <div class="modal fade" id="edit-comment">
    <div class="modal-dialog edit-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Comment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="editCommentForm">
              <div class="form-group">
                  <label>Enter Comment</label>
                  <textarea class="form-control" rows="5" required id="commentmessage"></textarea>
                  <small>Enter comment to replace edit</small>
              </div>
              <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i>Edit Comment</button>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>


  <div class="modal fade" id="invalidModal">
    <div class="modal-dialog ivalid-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Mark as In Valid</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="invalidForm">
              {{csrf_field()}}
              <input hidden value="{{$claim->id}}" name="claimid" />
              <div class="form-group">
                  <label>Reason For Invalidity</label>
                  <textarea class="form-control" rows="5" required name="invalid_comment" id="invalid_comment"></textarea>
                  <small>Enter reason for marking claim as invalid</small>
              </div>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Save Status</button>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
  

@stop



@section('page-scripts')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- fullCalendar 2.2.5 -->
  <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
  <script src="{{asset('plugins/fullcalendar/main.js')}}"></script>
<script>
$('#issueForm').submit(function(e) {
    e.preventDefault();
    var claimid2 = $('#id').val();
    var message = $('#message').val();

    console.log(claimid2);
    $.ajax({
        url: "{{url('/report-issue')}}",
        method: "POST",
        data: {
            claimid: claimid2,
            message: message,
            _token: "{{Session::token()}}"
        },
        success: function(response) {
            $('#issueForm').trigger('reset')
            $('#modal-lg').modal('hide');
            // alert(JSON.stringify(response));
            if (response.status == 'success') {
                 Swal.fire(
                        'Success',
                         response.message,
                        'success'
                        ).then(()=> {
                            window.location.reload()
                    })
            } else {
                 Swal.fire(
                        'Error',
                         response.message,
                        'error'
                        )
            }
        },
        error: function(error) {
            
            Swal.fire(
                        'Error',
                         'Oops something went wrong. Please try again',
                        'error'
                        )
        }
    })
})



$('#fileIssueForm').submit(function(e) {
    e.preventDefault();
    
    var id = $('#id').val();
    var message = $('#messagefile').val();

    $.ajax({
        url: "{{url('/report-issue-on-file')}}",
        method: "POST",
        data: {
            id: id,
            message: message,
            _token: "{{Session::token()}}"
        },
        success: function(response) {
            $('#fileIssueForm').trigger('reset')
            $('#fileIssue').modal('hide');
            // alert(JSON.stringify(response));
            if (response.status == 'success') {

                Swal.fire(
                        'Success',
                         response.message,
                        'success'
                        ).then(()=> {
                            window.location.reload()
                        })
            } else {
              
                 Swal.fire(
                        'Error!!!',
                         response.message,
                        'error'
                        )
            }
        },
        error: function(error) {
        
             Swal.fire(
                        'Error!!!',
                         'Oops something went wrong. Please try again',
                        'error'
            )
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


function transferedToBank (claimid) {
    Swal.fire({
        title: 'Transfered To bank',
        text: "Are your sure? Action cannot be undone!!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Payment Transfered To Bank'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('/transfer-to-bank')}}",
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





function deleteClaimFile (fileid) {
    Swal.fire({
        title: 'Deleting Claim File',
        text: "Are your sure? Action cannot be undone!!!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('/delete-single-claim-file')}}",
                method: "POST",
                data: {id: fileid, _token: "{{Session::token()}}"},
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
                    'Oops, unable to delete file. Please try again later',
                    'error'
                   ) 
                }
            })
        }
        })
}
</script>


<script>
  $('#editCommentForm').submit(function(event){
    event.preventDefault();
    var claimid = "{{$claim->id}}";
    var comment = $('#commentmessage').val()

    $.ajax({
      method: 'POST',
      url: "{{url('/edit-comment')}}",
      data: {claimid: claimid,comment: comment, _token: "{{Session::token()}}"},
      success: function(response) {
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
             ).then(()=> {
                window.location.reload()
            })
        }
      }, 
      error: function(error){
          Swal.fire(
            'Error!!!',
            'Oops, Unable to update comment. Please try again later',
            'error'
          ) 
      }
    })
  })
</script>

<script>
  $('#invalidForm').submit(function(event) {
    event.preventDefault();
    $.ajax({
      method: 'POST',
      url: "{{url('/mark-validity-status')}}",
      data: {claimid: "{{$claim->id}}", valid: 0, comment: $('#invalid_comment').val(), _token: "{{Session::token()}}"},
      success: function(response) {
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
             ).then(()=> {
                window.location.reload()
            })
        }
      }, 
      error: function(error){
          Swal.fire(
            'Error!!!',
            'Oops, something went wrong. Please try again later',
            'error'
          ) 
      }
    })
  })


  function markAsValid(){
    Swal.fire({
        title: 'Validate Claim',
        text: "Are your sure you want to set claim to valid?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Validate'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{url('/mark-validity-status')}}",
                method: "POST",
                data: {claimid: "{{$claim->id}}", valid: 1, _token: "{{Session::token()}}"},
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
                    'Oops, something went wrong. Please try again later',
                    'error'
                   ) 
                }
            })
        }
        })
  }
</script>





@stop




