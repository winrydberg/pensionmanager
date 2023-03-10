@extends('includes.master') @section('page-styles')
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

<script>
    var id = 0;
    var claimid = "";
    function showModal(id, claimid) {
        id = id;
        claimid = claimid;
        $("#claimid").val(claimid);
        $("#id").val(id);
        $("#modal-lg").modal("show");
    }
</script>
@stop @section('contentone')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Claims With Issue</h3>
            </div>
            <div class="card-body table-responsive">
                @if (Session::has('error'))
                <p class="alert alert-danger">{!! Session::get('error') !!}</p>
                @elseif (Session::has('success'))
                <p class="alert alert-success">
                    {!! Session::get('success') !!}
                </p>
                @endif
                <div class="dataTables_wrapper dt-bootstrap4">
                    <table
                        id="example1"
                        class="table table-bordered table-striped text-nowrap"
                    >
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Company</th>
                                <th>Description</th>
                                <th>Dept Reached</th>
                                <th>Issue Raised</th>
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
                                                color: rgb(238, 191, 120);
                                                font-size: 20px;
                                            "
                                        ></i>
                                        <strong>{{$claim->claimid}}</strong>
                                    </div>
                                </td>
                                <td>{{$claim->company->name}}</td>
                                <td>{{$claim->description}}</td>
                                <td>{{$claim->department_reached}}</td>

                                <td>
                                    {{$claim->issues[0] != null ? $claim->issues[0]->message : 'No Issue On Claim'}}
                                </td>
                                <td>
                                    {{date('Y-m-d H:iA', strtotime($claim->created_at))}}
                                </td>
                                <td>
                                    <a
                                        href="{{url('/issue-review?ticket='.$claim->issues[0]->issue_ticket)}}"
                                        class="btn btn-xs btn-flat bg-purple"
                                        ><i class="fa fa-edit"></i> Resolve
                                        Issue</a
                                    >
                                    {{--
                                    <button
                                        class="btn btn-flat btn-danger btn-xs"
                                    >
                                        <i class="fa fa-trash"></i> Delete Claim
                                    </button>
                                    --}}
                                    <a
                                        href="{{url('/claim?claimid='.$claim->id)}}"
                                        class="btn btn-xs btn-primary"
                                        ><i class="fa fa-eye"></i>View
                                        Details</a
                                    >
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

<script>
    $(function () {
        $("#example1")
            .DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["csv", "excel", "pdf", "print"],
            })
            .buttons()
            .container()
            .appendTo("#example1_wrapper .col-md-6:eq(0)");
        $("#example2").DataTable({
            paging: true,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
        });
    });
</script>

@stop
