

<div class="card card-danger card-outline">
    <div class="card-header">
      <h3 class="card-title">Claim Issues</h3>
    </div>
    <div class="card-body">
        @if(isset($pendingIssues) && count($pendingIssues) >0)
        <div class="table-responsive">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Issue Raised</th>
                        <th>Action</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($pendingIssues as $issue)
                        <tr>
                            <td><i class="fa fa-folder-open" style="color:rgb(238, 191, 120);"></i> {{$issue->claim->claimid}}</td>
                            <td style="white-space: nowrap;text-overflow:ellipsis; overflow:hidden;max-width: 20px;"  >{{$issue->message}}</td>
                            <td>
                              <?php 
                                  $file = $issue->claim_file;

                                  $filename = '';
                                  if($file != null){
                                    $fileParts = explode("/",$file->filename);
                                    $filename = $fileParts[count($fileParts) - 1];
                                  }
                              ?>
                              <button type="button" onclick="showIssueModal('{{$issue->message}}', '{{$filename}}')" class="btn btn-xs btn-flat btn-success" style="margin-right: 5px;"><i class="fa fa-eye"></i></button>
                              <a href="{{url('/issue-review?ticket='.$issue->issue_ticket)}}" class="btn btn-xs bg-purple btn-flat"><i class="fa fa-edit"></i> Review</a></td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
        @else
          <div class="">
            <p>No Pending Issues Now</p>
          </div>
        @endif
        </div>
</div>

<div class="modal fade" id="issue-modal">
    <div class="modal-dialog issue-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Issue Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><strong>Issue Message</strong></p>
          <p id="issuemessage"></p>
          <hr />
          <p><strong>File Name</strong></p>
            {{-- @if(in_array(strtolower($file->extension), ['png', 'jpg', 'jpeg']))
                <img src="{{asset('dist/img/filestypes/image.png')}}" style="width: 40px;"/>
            @elseif (strtolower($file->extension) =='pdf')
                <img src="{{asset('dist/img/filestypes/pdf.png')}}" style="width: 40px;"/>
            @elseif (in_array(strtolower($file->extension), ['xls', 'xlsx']))
                <img src="{{asset('dist/img/filestypes/sheets.png')}}" style="width: 40px;"/>
            @elseif (in_array(strtolower($file->extension), ['doc', 'docx']))
                <img src="{{asset('dist/img/filestypes/word.png')}}" style="width: 40px;"/>
            @else
                <img src="{{asset('dist/img/filestypes/file.png')}}" style="width: 40px;"/>
            @endif --}}
            <span id="claimfile"></span>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <script>
    
    function showIssueModal(message, claimfile){
        $('#issuemessage').html(message);

        $('#claimfile').html(claimfile);
        $('#issue-modal').modal('show');
    }
</script>