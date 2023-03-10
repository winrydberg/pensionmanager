@extends('includes.master')

@section('page-styles')

@stop

@section('pageheading', "Result")

@section('contentone')
<div class="row">
 <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary card-outline">     
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>          
                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="col-md-12 ">
                            <div class="text-center">
                                <i class="fa fa-check-square" style="font-size: 100px; color:rgb(70, 196, 70);"></i>
                            </div>
                            <h4 class="text-center" style="margin-top: 20px;">SUCCESS</h4>
                            <p class="text-center">{{Session::get('success')}}</p>
                            <div class="text-center" style="margin-top: 50px">
                                <a href="{{url('/dashboard')}}" class="btn btn-sm btn-primary text-center"><i class="fa fa-home"></i> Go Home</a>
                                 <a href="{{url()->previous()}}" class="btn btn-sm bg-purple text-center"><i class="fa fa-home"></i> Go Back</a>
                            </div>
                        </div>
                    @else
                       <div class="col-md-12 ">
                            <div class="text-center">
                                <i class="fa fa-times-circle" style="font-size: 100px; color:brown"></i>
                            </div>
                             <h4 class="text-center" style="margin-top: 20px;">ERROR</h4>
                            <p class="text-center">{{Session::get('error')}}</p>
                            <div class="text-center" style="margin-top: 50px">
                                <a href="{{url('/dashboard')}}" class="btn btn-sm btn-primary text-center"><i class="fa fa-home"></i> Go Home</a>
                                <a href="{{url()->previous()}}" class="btn btn-sm bg-purple text-center"><i class="fa fa-home"></i> Go Back</a>
                            </div>
                        </div>
                    @endif

                   

                </div>
            </div>
 </div>

    <div class="col-md-4">
                @include('includes.usefullinks')
    </div>

 </div>
@stop


@section('page-scripts')

@stop