@extends('includes.master')


@section('page-styles')
     <!-- BS Stepper -->
  <link rel="stylesheet" href="{{asset('plugins/bs-stepper/css/bs-stepper.min.css')}}">
    <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <style>
    #companyImg{
      height: 100px;
      width: 100px;
      object-fit: cover;
      border-radius: 20px;
    }
  </style>
@stop

@section('pageheading', "New Claim")


@section('contentone')
<div class="row">
          <div class="col-md-8">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Add Claim</h3>
              </div>
              <div class="card-body">
                

                <form method="POST" id="newClaimForm" action="{{url('/new-claim')}}" enctype="multipart/form-data">
                  {{csrf_field()}}
                  @if (Session::has('error'))
                        <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                  @elseif (Session::has('success'))
                          <p class="alert alert-success">{!! Session::get('success') !!}</p>
                  @endif

                  @include('includes.errordisplay')

                      <div class="row">
                        
                        <div class="col-md-12">
                          
                          <div class="form-group">
                              <div class="" >
                                <i class=" fa fa-folder-open" style="font-size: 60px; color: chocolate;"></i>
                              </div>
                          </div>

                          <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" required placeholder="A short description of claim" />
                          </div>

                          <div class="form-group">
                            <label for="company_id">Company</label>
                            <select class="form-control select2bs4" required style="width: 100%;" name="company_id" id="company_id">
                                <option value="" disabled selected="selected">Select Company</option>
                                @foreach ($companies as $company)
                                  <option value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="scheme_id">Scheme</label>
                            <select class="form-control select2bs4" required style="width: 100%;" name="scheme_id" id="scheme_id">
                                <option value="" disabled selected="selected">Select Scheme</option>
                                @foreach ($schemes as $scheme)
                                  <option value="{{$scheme->id}}">{{$scheme->name}} - {{$scheme->tiertype}}</option>
                                @endforeach
                            </select>
                          </div>
                        
                        </div>

                      <button type="submit" class="btn btn-success btn-flat"> <i id="loader" class="fa fa-spinner fa-spin"></i> Save Claim <i class="fa fa-arrow-right"></i></button>
                    </div>
                  
              
              </form>
            </div>
          </div>
        </div>
                
        <div class="col-md-4">
            @include('includes.usefullinks')
        </div>
        </div>
@stop

@section('page-scripts')
<!-- BS-Stepper -->
<script src="{{asset('plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- xlsx js -->
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
 --}}


<script>

  var cloneclount =0;


  

  $(document).ready(function(){
     $('#loader').hide()
     $('#newClaimForm').submit(function(event) {
      event.preventDefault();

     
      
      Swal.fire({
        title: 'Add a New Claim',
        text: "You are about to file a new claim",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Continue'
        }).then((result) => {
        if (result.isConfirmed) {
            $('#loader').show()
            $.ajax({
                url: "{{url('/new-claim')}}",
                method: "POST",
                data: $('#newClaimForm').serialize(),
                success: function(response){
                    if(response.status == 'success'){
                      $('#loader').hide()
                       var onBtnClicked = (btnId) => {
                          Swal.close();
                          if (btnId != "cancel") Swal.fire("you choosed: " + btnId);
                        };
                        Swal.fire({
                            allowOutsideClick: false,
                            title: 'Success',
                            // text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            html: response.message+`<div>
                                  <a href="${response.request_url}"  style="margin-top: 10px" role="button" tabindex="0" class="btn btn-primary btn-sm" onclick="onBtnClicked('request files')">Upload Request Documents</a>
                                  <a href="${response.processed_url}" style="margin-top: 10px" role="button" tabindex="0" class="btn btn-success btn-sm" onclick="onBtnClicked('processed files'")>Upload Processed Documents</a>
                                  <a href="${response.home_url}" style="margin-top: 15px" role="button" tabindex="0" class="btn bg-purple btn-sm" onclick="onBtnClicked('processed files'")>Go Dashboard</a>
                                </div>`,
                            showCancelButton: false,
                            showConfirmButton: false
                        })
                    }else{
                      $('#loader').hide()
                       Swal.fire(
                        'Error!!!',
                         response.message,
                        'error'
                        )
                    }
                },
                error: function(error){
                  $('#loader').hide()
                   Swal.fire(
                    'Error!!!',
                    'Oops, unable to add claim. Please try again',
                    'error'
                   ) 
                }
            })
        }
        })
     })
  })

    // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })
   //Initialize Select2 Elements
  $('.select2bs4').select2({
      theme: 'bootstrap4'
  })

  $(function () {
    bsCustomFileInput.init();
  });


</script>

@stop