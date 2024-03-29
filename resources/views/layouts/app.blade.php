@include('backend.partials.header')

<body>

<div id="wrapper">

    @include('backend.partials.navbar')
    <div id="page-wrapper" class="gray-bg">
        @include('backend.partials.topbar')
        <br>
        @include('backend.partials.notification')

        @yield('content')

        <div class="footer">
            <div class="pull-right">

            </div>
            <div>
                <strong>Copyright</strong> &copy; {{date("Y")}}
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<!-- Mainly scripts -->
<script src="{{url('assets/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{url('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{url('assets/js/inspinia.js')}}"></script>
<script src="{{url('assets/js/plugins/pace/pace.min.js')}}"></script>


<!-- Peity -->
<script src="{{url('assets/js/plugins/peity/jquery.peity.min.js')}}"></script>
<!-- Peity demo -->
<script src="{{url('assets/js/demo/peity-demo.js')}}"></script>

{{--   New archives  --}}

<script src="{{ url('assets/web_dav/libs/bootbox.js/5.5.2/bootbox.min.js') }}?a="></script>

<!-- script src="{{ url('assets/web_dav/libs/bootbox.js/5.5.2/bootbox.min.js') }}?a="></script -->


<form action="{{ url('/') }}"
      method="GET"
      id="formNext" name="formNext">
</form>
<br/>
<form action="{{ url('/') }}"
      method="POST"
      id="invokePost" name="invokePost">
    {{ csrf_field() }}
</form>

</body>
</html>
