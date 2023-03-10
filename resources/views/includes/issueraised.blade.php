<div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">Issue Summary</h3>
    </div>
    <div class="card-body">
      @if(isset($issue))
            <p><strong>Issue Message</strong></p>
            <p>{{$issue->message}}</p>

            <hr/>
                <?php 
                    $file = $issue->claim_file;
                    $filename = '';
                    if($file != null){
                      $fileParts = explode("/",$file->filename);
                      $filename = $fileParts[count($fileParts) - 1];

                    }
                ?>
               <p><strong>Affected File</strong></p>
               <span>
                    @if($file != null)
                        @if(in_array(strtolower($file->extension), ['png', 'jpg', 'jpeg']))
                            <img src="{{asset('dist/img/filestypes/image.png')}}" style="width: 40px;"/>
                        @elseif (strtolower($file->extension) =='pdf')
                            <img src="{{asset('dist/img/filestypes/pdf.png')}}" style="width: 40px;"/>
                        @elseif (in_array(strtolower($file->extension), ['xls', 'xlsx']))
                            <img src="{{asset('dist/img/filestypes/sheets.png')}}" style="width: 40px;"/>
                        @elseif (in_array(strtolower($file->extension), ['doc', 'docx']))
                            <img src="{{asset('dist/img/filestypes/word.png')}}" style="width: 40px;"/>
                        @else
                            <img src="{{asset('dist/img/filestypes/file.png')}}" style="width: 40px;"/>
                        @endif
                    @else 
                         <img src="{{asset('dist/img/filestypes/file.png')}}" style="width: 40px;"/>
                    @endif
                    <span> {{$filename}}</span>
               </span>
      @endif
    </div>
</div>