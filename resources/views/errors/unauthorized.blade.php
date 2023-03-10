@extends('includes.master')

@section('pageheading', "Error Page")

@section('contentone')
     <section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">{{$exception->getStatusCode()}}</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> OOOPS! UNAUTHORIZED ACCESS</h3>

          <p>
           You may have the right roles to perform this action. Please <a href="{{url('/dashboard')}}">return to dashboard</a> .
          </p>

          <a class="btn btn-sm btn-danger"><i class="fa fa-reply"></i> Go to Dashboard</a>
        </div>
      </div>
      <!-- /.error-page -->

    </section>
@stop