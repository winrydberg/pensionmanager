<div class="card card-dark">
              <div class="card-header">
                <h3 class="card-title">Claims By Month</h3>
              </div>
                <div class="card-body">
                  <form method="GET" action="#">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Month</label>
                                <input type="month" onchange="changeEventHandler(event)" value="{{$currentMonthYear}}" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </form>
                  <div class="col-12" id="accordion">
                    @if(count($claimsByDate) > 0)
                        @foreach ($claimsByDate as $key => $claims)
                        <div class="card-dark card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapse{{$key}}">
                                <div class="card-header">
                                    <h4 class="card-title w-100" style="color:#343a40;">
                                        <i class="fa fa-plus-circle"></i> {{$key}}
                                    </h4>
                                </div>
                            </a>
                            <div id="collapse{{$key}}" class="collapse" data-parent="#accordion">
                                <div class="card-body table-responsive">
                                  <div class="dataTables_wrapper dt-bootstrap4 ">
                                  <table id="example1" class="table table-bordered table-striped text-nowrap">
                                     <thead>
                                      <tr>
                                      <th>Claim ID</th>
                                      <th>Process</th>
                                      <th>Audit</th>
                                      <th>Scheme Admins</th>
                                      <th>Action</th>
                                      </tr>
                                      </thead>
                                    <tbody>
                                      @foreach ($claims as $claim)
                                        <tr>
                                          <td><i class="fa fa-folder-open" style="color:chocolate"></i> <strong>{{$claim->claimid}}</strong> - {{$claim->description}}</td>
                                          <td>
                                            @if($claim->processed)
                                              <span class="badge badge-success"><i class="fa fa-check-circle"></i> Processed</span>
                                            @else
                                              <span class="badge badge-danger"><i class="fa fa-times-circle"></i> No Processed</span>
                                            @endif
                                          </td>
                                          <td>
                                            @if($claim->audited)
                                              <span class="badge badge-primary"><i class="fa fa-check-circle"></i> Audited By {{$claim->audited_by}}</span>
                                            @else
                                              <span class="badge badge-dark"><i class="fa fa-times-circle"></i> Pending Auditing</span>
                                            @endif
                                          </td>
                                          <td>
                                            @if($claim->audited)
                                              @if($claim->paid)
                                                  <span class="badge badge-success"> <i class="fa fa-times-circle"></i> Received</span>
                                              @else
                                                  <span class="badge badge-warning"><i class="fa fa-times-circle"></i> Not Received</span>
                                              @endif
                                            @else 
                                              <span class="badge badge-warning"><i class="fa fa-times-circle"></i> Not Received</span>
                                            @endif
                                          </td>
                                          <td>
                                            <a href="{{url('/claim?claimid='.$claim->id)}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View Details</a>
                                          </td>
                                        </tr>
                                      @endforeach                                    
                                  </tbody>
                                  </table>
                                  </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p>No Claims Found For Selected Date</p>
                    @endif
                </div>
                    {{-- <div id="calendar"></div> --}}
                </div>
            </div>