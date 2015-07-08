<!DOCTYPE html>
<html>
<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <title>AbsoluteAdmin - A Responsive Bootstrap 3 Admin Dashboard Template</title>
  <meta name="keywords" content="HTML5 Bootstrap 3 Admin Template UI Theme" />
  <meta name="description" content="AbsoluteAdmin - A Responsive HTML5 Admin UI Framework">
  <meta name="author" content="AbsoluteAdmin">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="_token" content="<?= csrf_token() ?>">

  <!-- Font CSS (Via CDN) -->
  <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'>

  <!-- Theme CSS -->
  <link rel="stylesheet" type="text/css" href="http://themes.tur8.ru/absadmin/assets/skin/default_skin/css/theme.css">

  <!-- Favicon -->
  <link rel="shortcut icon" href="http://themes.tur8.ru/absadmin/assets/img/favicon.ico">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

</head>

<body class="error-page sb-l-o sb-r-c">

  <!-- Start: Main -->
  <div id="main">

    <!-- Start: Header -->
    <header class="navbar navbar-fixed-top navbar-shadow">
      <div class="navbar-branding">
        <a class="navbar-brand" href="/hermes">
          <b>Hermes</b>Module
        </a>
        <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
      </div>
    </header>
    <!-- End: Header -->

    <!-- Start: Sidebar -->
    <aside id="sidebar_left" class="nano nano-light affix">

      <!-- Start: Sidebar Left Content -->
      <div class="sidebar-left-content nano-content">

        <!-- Start: Sidebar Header -- >
        <header class="sidebar-header">

          <div class="sidebar-widget author-widget">
            <div class="media">
              <a class="media-left" href="#">
                <img src="http://themes.tur8.ru/absadmin/assets/img/avatars/3.jpg" class="img-responsive">
              </a>
              <div class="media-body">
                <div class="media-author">Admin</div>
              </div>
            </div>
          </div>
        </header>
        <!-- End: Sidebar Header -->
<?php 

$menu = [
    'label'=>'Меню',
    'points'=>[
        [
            'label'=>'Финансовая статистика',
            'href'=>'/hermes/payment',
        ],
        [
            'label'=>'Инкассация',
            'href'=>'/hermes/incass',
        ],
        [
            'label'=>'Терминалы',
            'href'=>'/hermes/points',
        ],
        [
            'label'=>'Статусы терминалов',
            'href'=>'/hermes/',
        ],
    ],
];?>
        <!-- Start: Sidebar Menu -->
        <ul class="nav sidebar-menu">
          <li class="sidebar-label pt20">{{ $menu['label'] }}</li>
          @foreach($menu['points'] as $point)
          <li>
            <a href="{{ $point['href'] }}">
              <span class="fa"></span>
              <span class="sidebar-title">{{ $point['label'] }}</span>
            </a>
          </li>
          @endforeach

        </ul>
        <!-- End: Sidebar Menu -->
      </div>
      <!-- End: Sidebar Left Content -->

    </aside>
    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">
      <!-- Begin: Content -->
      <section id="content" class="animated fadeIn">
		@yield('content')
      </section>
      <!-- End: Content -->

    </section>
  </div>
  <!-- End: Main -->

  <!-- BEGIN: PAGE SCRIPTS -->

@section('scripts')
  <!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <!--<script src="vendor/jquery/jquery-1.11.1.min.js"></script>
  <script src="vendor/jquery/jquery_ui/jquery-ui.min.js"></script>-->

  <!-- Theme Javascript -->
  <script src="http://themes.tur8.ru/absadmin/assets/js/utility/utility.js"></script>
  <!--<script src="http://themes.tur8.ru/absadmin/assets/js/demo/demo.js"></script>-->
  <script src="http://themes.tur8.ru/absadmin/assets/js/main.js"></script>
@show
  <script type="text/javascript">
  jQuery(document).ready(function() {

    "use strict";

    // Init Theme Core    
    Core.init();

    // Init Demo JS    
    //Demo.init();
    
    $.ajaxSetup({
    beforeSend: function (xhr, settings) {
        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
            xhr.setRequestHeader("X-CSRF-Token", $("meta[name='_token']").attr('content'));
        }
    }
});

  });
  </script>
  @yield('script')
  <!-- END: PAGE SCRIPTS -->

</body>
</html>
