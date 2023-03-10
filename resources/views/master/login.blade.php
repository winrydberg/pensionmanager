<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pension CMP | Log In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <style>
    #top-section{
      height: 70vh;
    }

     #bottom-section{
       width: 100%;
      /* height: 30vh;
      width: 100%;
      object-fit: cover;
      background-repeat: repeat-x;
      background-position: top;
      background-image: url({{asset('dist/img/bg.jpg')}}) */
    }
    /* .login-box{
      position: absolute
    } */
    .img-bg{
      height: 20vh;
      width: 100vw;
      position: absolute;
      bottom: 0;
      z-index: -9999;
      object-fit: cover;
      /* border-top: 3px solid #FFF; */
      /* background-color: rgba(0, 0, 0, 0.5) */

    }
  </style>
</head>
<body class="hold-transition login-page">
  
  <div class="login-box  col-md-8 col-sm-8 col-xs-8">
  <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{url('/')}}" class="h1"><b>Pension</b>CMP</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to continue</p>

        @if (Session::has('error'))
          <p class="alert alert-danger">{!! Session::get('error') !!}</p>
        @endif
        <form action="{{url('/login')}}" method="post">
          {{csrf_field()}}
          <div class="input-group mb-3">
            <input type="email" required name="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" required name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <!-- <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div> -->
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <!-- /.social-auth-links -->

        <!-- <p class="mb-1">
          <a href="{{url('/forgot-password')}}">I forgot my password</a>
        </p> -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    
  </div>


  {{-- <div id="bottom-section"> --}}
     <img src="{{asset('dist/img/bg.svg')}}" class="img-bg"/>
  {{-- </div> --}}

<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>

<script>
  setTimeout(() => {
    $('.alert').slideUp();
  }, 2000);
</script>
</body>
</html>
