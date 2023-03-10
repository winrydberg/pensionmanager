@extends('includes.master')

@section('page-styles')

    <!-- jQuery and jQuery UI (REQUIRED) -->
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/theme.css') }}">

    <!-- elFinder JS (REQUIRED) -->
    <script src="{{ asset('packages/barryvdh/elfinder/js/elfinder.min.js') }}"></script>



    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function() {
            $('#elfinder').elfinder({
                // set your elFinder options here
              
                customData: { 
                    _token: '{{ csrf_token() }}'
                },
                url : '{{ route("elfinder.connector") }}',  // connector URL
                soundPath: '{{ asset('packages/barryvdh/elfinder/sounds') }}'
            });
        });
    </script>
@stop

@section('contentone')
<div id="elfinder"></div>
@stop


@section('page-scripts')
<!-- File manager -->
{{-- <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

    fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
      window.opener.fmSetLink(fileUrl);
      window.close();
    });
  });
</script> --}}
@stop