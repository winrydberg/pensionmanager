@extends('includes.master')

@section('page-styles')
  
@stop

@section('pageheading', "Schemes")

@section('contentone')
    <div class="row">
        @foreach ($schemes as $scheme)
            <div class="col-md-4">
              <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="far fa-folder-open"></i></span>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-weight: bold;">{{$scheme->name.' - '.$scheme->tiertype}}
                          @if($scheme->claims_count > 0)
                             <span class="badge badge-danger" style="margin-left: 15px;">{{$scheme->claims_count}}</span>
                          @endif
                          </span>
                      </div>
                      <div class="col-md-8">
                        @can($scheme->name.'--'.$scheme->tiertype)
                            <a href="{{url('/scheme-audited-claims?schemeid='.$scheme->id)}}"  class="btn btn-xs bg-purple col-md-12"><i class="fa fa-eye"></i> View Claims</a>
                        @endcan
                      </div>
                    </div>
                  </div>
               </div>
            </div>
         
        @endforeach
    </div>
@stop

@section('page-scripts')

@stop