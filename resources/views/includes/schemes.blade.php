<div class="row">
    <div class="col-md-6 col-sm-6 col-12">
        @foreach ($schemes as $scheme)
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="far fa-bell"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">{{$scheme->name}}</span>
                  <span class="info-box-number">1,410</span>
                </div>
          </div>
        @endforeach
    </div>
</div>