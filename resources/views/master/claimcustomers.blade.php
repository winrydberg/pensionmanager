@extends('includes.master')

@section('pageheading', 'Staffs/Claimants')

@section('page-styles')
      <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<script>
    var cus_id=0;
    var claim_id=0
    function enterChequeNo(customer_id, claim_id){
        cus_id=customer_id;
        claim_id = claim_id;
        $('#cus_id').val(customer_id);
        $('#claim_id').val(claim_id);
        $('#chequeModal').modal('show');
    }
</script>

@stop



@section('contentone')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Claim <strong>{{$claim->claimid}}</strong> Staff Members</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped text-nowrap">
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Policy No</th>
                      <th>Claim Type</th>
                      <th>Company</th>
                      <th>Amount(GHC)</th>
                      <th>Account Name</th>
                      <th>Bank</th>
                      <th>Branch</th>
                      <th>Account No.</th>

                      <th>Employee Cont.</th>
                      <th>Employer Cont.</th>
                      <th>Withdrawal Amt</th>
                      <th>Tax Percentage</th>
                      <th>Amount Payable To SSNIT</th>
                      <th>Name on Cheque</th>
                      <th>Cheque No.</th>
                      <th>Payment Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach ($customers as $c)
                     
                       <tr>
                            <td>{{$c->name}}</td>
                            <td>{{$c->policy_number}}</td>
                            <td>{{$c->claimtype}}</td>
                            <td>{{$c->company}}</td>
                            <td>{{$c->amount}}</td>
                            <td>{{$c->name}}</td>
                            <td>{{$c->bank}}</td>
                            <td>{{$c->bankbranch}}</td>
                            <td>{{$c->accnumber}}</td>
                            <td>{{$c->employee_contribution}}</td>
                            <td>{{$c->employer_contribution}}</td>
                            <td>{{$c->withdrawal_amount}}</td>
                            <td>{{$c->tax_percentage}}</td>
                            <td>{{$c->amount_payable_to_ssnit}}</td>
                            <td>{{$c->name_on_cheque}}</td>
                            <td>{{$c->cheque_number}}</td>
                            <td>
                              @if($c->payment_status == true)
                                <span class="badge badge-success">Transferred To Bank</span>
                              @else
                                <span class="badge badge-danger">Not Paid</span>
                              @endif
                            </td>
                            <td>
                              @if($claim->paid)
                                  @role('accounting')
                                  <button class="btn btn-xs btn-primary" onclick="enterChequeNo({{$c->id}},  {{$c->claim_id}})"><i class="fa fa-credit-card"></i>  Enter Cheque No</button>
                                  @endrole
                              @endif
                              
                            </td>
                      </tr>
                     @endforeach
                   
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
        </div>
    </div>
@stop

@section('contenttwo')
    <div class="modal fade" id="chequeModal">
    <div class="modal-dialog chequeModal">
        <form id="chequeNoForm" action="POST">
            {{csrf_field()}}
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Enter Payment Cheque </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <div class="modal-body">
                   <div class="form-group" hidden>
                        <label>Customer Id</label>
                        <input class="form-control"  readonly name="cus_id" id="cus_id" />
                        <input class="form-control"  readonly name="claim_id" id="claim_id" />
                    </div>
                    <div class="form-group" >
                        <label> Name on Cheque </label>
                        <input class="form-control" required name="name_on_cheque" id="name_on_cheque" />
                    </div>
                    <div class="form-group" >
                        <label> Cheque No</label>
                        <input class="form-control" required name="chequeno" id="chequeno" />
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit </button>
                </div>
            </div>
        </form>
      <!-- /.modal-content -->
    </div>
</div>
@stop
@section('page-scripts')
  <!-- DataTables  & Plugins -->
 <!-- DataTables  & Plugins -->
 <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
 <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
 <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
 <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
 <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
 <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>

  
<script>
  $('#chequeNoForm').submit(function(event){
    event.preventDefault();
    var claimid = $('#claim_id').val();
    var cusid = $('#cus_id').val();
    var chequeno = $('#chequeno').val();
    var nameoncheque = $('#name_on_cheque').val();



    $.ajax({
      method: 'POST',
      url: "{{url('/cheque-no-entry')}}",
      data: {claim_id: claimid,cus_id: cusid, name_on_cheque: nameoncheque, chequeno: chequeno, _token: "{{Session::token()}}"},
      success: function(response) {
        if(response.status == 'success'){
            Swal.fire(
            'Success',
             response.message,
            'success'
            ).then(()=> {
                window.location.reload()
            })
        }else{
            Swal.fire(
             'Error!!!',
              response.message,
             'error'
             ).then(()=> {
                window.location.reload()
            })
        }
      }, 
      error: function(error){
         Swal.fire(
                    'Error!!!',
                    'Oops, Unable to record cheque no. Please try again later',
                    'error'
                   ) 
      }
    })
  })
</script>


@stop