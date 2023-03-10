@extends('includes.master') 
@section('title', 'INVALID CLAIMS')
@section('page-styles')
<!-- DataTables -->
<link
    rel="stylesheet"
    href="{{
        asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')
    }}"
/>
<link
    rel="stylesheet"
    href="{{
        asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')
    }}"
/>
<link
    rel="stylesheet"
    href="{{
        asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')
    }}"
/>

@stop 

@section('pageheading', "Invalid Claims") 

@section('contentone')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Claims</h3>
            </div>
            <div class="card-body table-responsive">
                <div class="col-md-12">
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Scheme</label>
                                    <select
                                        class="form-control select2bs4"
                                        name="schemeid"
                                    >
                                        <option value="0">Select Scheme</option>
                                        @foreach ($schemes as $scheme)
                                        <option
                                            value="{{$scheme->id}}"
                                            {{request()->query('schemeid')== $scheme->id ?
                                            'selected="selected"': ''}}>{{$scheme->name.' - '. $scheme->tiertype}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input
                                        class="form-control"
                                        value="{{request()->query('startdate')}}"
                                        name="startdate"
                                        type="date"
                                    />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input
                                        class="form-control"
                                        value="{{request()->query('enddate')}}"
                                        name="enddate"
                                        type="date"
                                    />
                                </div>
                            </div>

                            <div class="col-md-3" style="margin-top: 10px">
                                <div class="form-group">
                                    <br />
                                    <button
                                        type="submit"
                                        class="btn btn-success btn-flat btn-block"
                                    >
                                        <i class="fa fa-search"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop @section('contenttwo')
<div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Claims</h3>
            </div>
            <div class="card-body table-responsive">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table
                        id="example1"
                        class="table table-bordered table-striped text-nowrap"
                    >
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th style="width: 100px">Description</th>
                                <th>Scheme</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claims as $claim)
                            <tr>
                                <td>
                                    <div
                                        style="
                                            flex-direction: row;
                                            justify-content: center;
                                            align-items: center;
                                        "
                                    >
                                        <i
                                            class="fa fa-folder-open"
                                            style="
                                                color: chocolate;
                                                font-size: 20px;
                                            "
                                        ></i>
                                        <strong>{{$claim->claimid}}</strong>
                                    </div>
                                </td>
                                <td style="width: 100px">
                                    {{$claim->description}}
                                </td>
                                <td>
                                    {{$claim->scheme->name}} -
                                    {{$claim->scheme->tiertype}}
                                </td>

                                <td>
                                    @if($claim->active)
                                    <span class="badge badge-success">
                                        Valid Claim</span
                                    >
                                    @else
                                    <span class="badge badge-danger"
                                        >
                                        <i class="fa fa-times-circle"></i> Invalid</span
                                    >
                                    @endif
                                </td>

                                <td>
                                    {{date('Y-m-d H:iA', strtotime($claim->created_at))}}
                                </td>
                                <td>
                                    
                                    
                                    <button
                                        class="btn btn-xs bg-danger btn-flat"
                                        onclick="deleteClaim('{{$claim->id}}')"
                                    >
                                        <i class="fa fa-trash"></i> Delete Claim
                                    </button>

                                     <button
                                        class="btn btn-xs bg-warning btn-flat"
                                        onclick="updateValidState('{{$claim->id}}')"
                                    >
                                        <i class="fa fa-times"></i> Set To Valid
                                    </button>
                                   
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
@stop @section('page-scripts')
<!-- DataTables  & Plugins -->
<script src="{{
        asset('plugins/datatables/jquery.dataTables.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')
    }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{
        asset('plugins/datatables-buttons/js/buttons.html5.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-buttons/js/buttons.print.min.js')
    }}"></script>
<script src="{{
        asset('plugins/datatables-buttons/js/buttons.colVis.min.js')
    }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        $("#example1")
            .DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["excel", "pdf", "print"],
            })
            .buttons()
            .container()
            .appendTo("#example1_wrapper .col-md-6:eq(0)");
    });

    function deleteClaim(id) {
        Swal.fire({
            title: "Delete Claim Now?",
            text: "Are your sure? Action cannot be undone!!!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/delete-claim')}}",
                    method: "POST",
                    data: { id: id, _token: "{{Session::token()}}" },
                    success: function (response) {
                        if (response.status == "success") {
                            Swal.fire(
                                "Success",
                                response.message,
                                "success"
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error!!!", response.message, "error");
                        }
                    },
                    error: function (error) {
                        Swal.fire(
                            "Error!!!",
                            "Oops, unable to delete claim. please try again",
                            "error"
                        );
                    },
                });
            }
        });
    }


    function updateValidState(id) {
        Swal.fire({
            title: "Claim Validation",
            text: "You are about to validate a claim. Are you sure?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Validate",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{url('/update-valid-status')}}",
                    method: "POST",
                    data: { claimid: id, state: 1, _token: "{{Session::token()}}" },
                    success: function (response) {
                        if (response.status == "success") {
                            Swal.fire(
                                "Success",
                                response.message,
                                "success"
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire("Error!!!", response.message, "error");
                        }
                    },
                    error: function (error) {
                        Swal.fire(
                            "Error!!!",
                            "Oops, unable to validate claim. please try again",
                            "error"
                        );
                    },
                });
            }
        });
    }
</script>
@stop
