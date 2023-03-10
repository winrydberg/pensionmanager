@extends('includes.master')

@section('pageheading', "Issue Review")

@section('page-styles')
     <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
@stop

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Issue Review</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="{{url('/issue/'.$issue->issue_ticket.'/review')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="card-body">
                    @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                        <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif
                 
                       <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="firstname">Issue Ticket(Optional)</label>
                                  <input type="text" value="{{$issue->issue_ticket}}" readonly class="form-control" id="claimid" name="issueticket">
                              </div>
                          </div>
                       </div>

                       <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="firstname">Message (Optional)</label>
                                  <textarea class="form-control" name="resolve_message"></textarea>
                              </div>
                          </div>
                       </div>

                       <div class="form-group">
                         <label for="firstname">Select File(s) (Optional)</label>
                          <input id="input-id" multiple name="issuefiles[]" type="file">   
                          <small>If there are any amended documents, upload them here</small>
                       </div>
  

                       <div class="form-group">
                        <div class="row">
                          <div class="col-md-6">
                              <button type="submit" class="btn btn-success btn-block btn-flat"><i class="fa fa-paper-plane"></i> Resolve Issue</button>
                          </div>
                        </div>
                      </div>
          
                 </div>
                 
                </div>
              </form>
 </div>
 <div class="col-md-4">
    @include('includes.issueraised')
    @include('includes.usefullinks')
</div>

</div>
@stop


@section('page-scripts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">

<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/plugins/buffer.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/plugins/filetype.min.js" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.1/js/fileinput.min.js"></script>

<script>
  $(document).ready(function() {
      // initialize with defaults
      // $("#input-id").fileinput();
  
      // with plugin options
      $("#input-id").fileinput({'previewFileType': 'any', 'showUpload': false, 'minFileCount': 0, 'maxTotalFileCount': 0});
  });
</script>

@stop