@extends('includes.master')


@section('page-styles')
     <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
      <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@stop

@section('pageheading', 'Request Files')

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Upload Request Files</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="{{url('/claim-files')}}" method="POST" enctype="multipart/form-data">
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
                                  <label for="firstname">Claim ID</label>
                                  <input type="text" value="{{$claimid}}" readonly class="form-control" id="claimid" name="claimid">
                              </div>
                          </div>
                       </div>

                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                              <label for="firstname">Comment(Optional)</label>
                              <textarea class="form-control" rows="5" name="comment"></textarea>   
                            </div>
                          </div>
                      </div>

                      

                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="firstname">Select Files</label>
                              <input id="input-id" name="claimfiles[]" multiple  type="file" required>   
                            </div>
                          </div>
                      </div>


                     

                     

                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-block btn-flat"><i class="fa fa-paper-plane"></i> Upload Files</button>
                        </div>
                        {{-- <div class="col-md-6">
                          <a href="{{url('dashboard')}}" id="btnnotready" class="btn bg-purple btn-flat"><i class="fa fa-arrow-left"></i> Files Not Ready</a>
                      </div> --}}
                     </div>
                        
                    </div>
          
                 </div>
                 
                </div>
              </form>
 </div>
 <div class="col-md-4">
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
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>

<script>
    //Initialize Select2 Elements
  $('.select2bs4').select2({
      theme: 'bootstrap4'
  })

  $('#claimstate').on('change', function(event){
    var val = $('#claimstate').val()
    if(val == 1){
      $('#btnnotready').hide();
    }else{
       $('#btnnotready').show();
    }
  })

  $(document).ready(function() {
      // initialize with defaults
      // $("#input-id").fileinput();
  
      // with plugin options
      $("#input-id").fileinput({'previewFileType': 'any', 'showUpload': false, 'maxFileCount': 0});
  });
  </script>
@stop
