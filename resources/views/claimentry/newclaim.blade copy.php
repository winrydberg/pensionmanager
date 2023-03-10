@extends('includes.master')


@section('page-styles')
<!-- BS Stepper -->
<link rel="stylesheet" href="{{asset('plugins/bs-stepper/css/bs-stepper.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
#companyImg {
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
                <h3 class="card-title">New Claim</h3>
            </div>
            <div class="card-body">


                <form method="POST" action="{{url('/new-claim')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    @if (Session::has('error'))
                    <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                    @elseif (Session::has('success'))
                    <p class="alert alert-success">{!! Session::get('success') !!}</p>
                    @endif

                    @include('includes.errordisplay')
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#logins-part">
                                <button type="button" class="step-trigger" role="tab" aria-controls="logins-part"
                                    id="logins-part-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Company Info</span>
                                </button>
                            </div>

                            <div class="line"></div>
                            <div class="step" data-target="#information-part">
                                <button type="button" class="step-trigger" role="tab" aria-controls="information-part"
                                    id="information-part-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Claim Files</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- your steps content here -->
                            <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="">
                                                <i class=" fa fa-folder-open"
                                                    style="font-size: 60px; color: burlywood;"></i>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="company_id">Company</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                name="company_id" id="company_id">
                                                <option value="null" disabled selected="selected">Select Company
                                                </option>
                                                @foreach ($companies as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="scheme_id">Scheme</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                name="scheme_id" id="scheme_id">
                                                <option value="null" disabled selected="selected">Select Scheme</option>
                                                @foreach ($schemes as $scheme)
                                                <option value="{{$scheme->id}}">{{$scheme->name}} -
                                                    {{$scheme->tiertype}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                </div>
                                <button type="button" class="btn btn-success" onclick="nextState()">Next <i
                                        class="fa fa-arrow-right"></i></button>
                            </div>
                            <div id="information-part" class="content" role="tabpanel"
                                aria-labelledby="information-part-trigger">
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="">
                                                <i class=" fa fa-folder" style="font-size: 60px; color: burlywood;"></i>
                                            </div>

                                            {{-- <img src="{{asset('dist/img/company.png')}}" id="companyImg"
                                            class="img-rounded" /> --}}
                                        </div>
                                        <div class="form-group">
                                            <label for="grelfile">Choose File(Required)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" onchange="checkUploadedFileType()"
                                                        class="custom-file-input" id="grelfileUpload" name="claimfiles"
                                                        required>
                                                    <label class="custom-file-label" for="grelfile">Choose file</label>
                                                </div>
                                            </div>
                                            <small style="color:gray;">Supported File Types (.zip file containing .xls
                                                or xlsx and other )</small>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="previousState()"><i
                                        class="fa fa-arrow-left"></i> Previous</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i>
                                    Submit</button>
                            </div>
                        </div>
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

<!-- xlsx js -->
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
 --}}


<script>
var cloneclount = 0;

$(document).ready(function() {
    $('#company_id').on('change', function() {
        var sumCompany = $("#company_id option:selected").html();
        $('#sumCompany').html(sumCompany);
    });

    $('#cname').on('change', function() {
        $('#sumClaimDesc').html(this.value);
    })
})

// BS-Stepper Init
document.addEventListener('DOMContentLoaded', function() {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
})
//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})

$(function() {
    bsCustomFileInput.init();
});


function nextState() {
    stepper.next();
}

function previousState() {
    stepper.previous();
}

//REMOVE FILE INPUT
function removeFileInput(id) {
    $(`#input${id}`).remove();
}

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function checkUploadedFileType() {
    var fileUpload = document.getElementById("grelfileUpload");
    var ext = getExtension(fileUpload.value.toLowerCase());
    if (ext !== 'zip') {
        $('#grelfileUpload').val(null);
        alert("Not a zip file. Please Upload a zip file")
    }
}
</script>

@stop