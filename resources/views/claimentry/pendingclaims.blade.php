@extends('includes.master')

@section('page-styles')

@stop

@section('pageheading', "Pending Claims")

@section('contentone')
<div class="row">
 <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">     
                <div class="card-header">
                    <h3 class="card-title">Recent Claims</h3>
                </div>          
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Claim ID</th>
                          <th>Company</th>
                          <th>Scheme</th>
                          <th>Dept Reached</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                         @foreach ($claims as $claim)
                           <tr>
                                <td>
                                    <div style="flex-direction:row; justify-content:center; align-items:center">
                                        <i class="fa fa-folder" style="color:rgb(238, 191, 120); font-size: 30px;"></i>
                                        <strong>{{$claim->claimid}}</strong>
                                    </div>
                                </td>
                                <td>{{$claim->company->name}}</td>
                                <td>{{$claim->scheme->name}}</td>
                                <td>{{$claim->departmentreached!=null?$claim->departmentreached->name:''}}</td>
                                <td>
                                    @if($claim->state =='Uploaded')
                                      
                                            <span class="badge badge-primary">Uploaded</span>
                                       
                                    @elseif($claim->state =='Downloaded')
                                        
                                            <span class="badge badge-primary">Downloaded</span>
                                       
                                    @else
                                        
                                            <span class="badge badge-primary">Donwloaded</span>
                                      
                                    @endif
                                </td>
                                <td>
                                    <a href="{{url('/claim-files/tinymce5')}}" class="btn btn-xs btn-success"><i class="fa-light fa-browser"></i> Browse Files</a>
                                </td>
                          </tr>
                         @endforeach
                       
                        </tbody>
                        <tfoot>
                        <tr>
                          <th>Claim ID</th>
                          <th>Company</th>
                          <th>Scheme</th>
                          <th>Dept Reached</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                        </tfoot>
                      </table>  

                      {{ $claims->links() }}
                </div>
            </div>
 </div>

 </div>
@stop


@section('page-scripts')

@stop