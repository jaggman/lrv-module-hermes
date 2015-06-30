<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Hermes - @yield('title')</title>
        <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="/bootstrap/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="/includes/themes/default/style.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="/includes/js/html5shiv.js"></script>
                <script src="/includes/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
		<ul id="sidemenu" class="sidemenu hidden-xs">
			<li><a class="sidemenu-brand" href="./">HERMES</a></li>
			<li><a class="selected" href="{{ action('\Modules\Hermes\Http\Controllers\HermesController@getIndex') }}"><i class="icon-home icon-2x"></i> <br/>Home</a></li>
                        <li>
                            <a class="flyout" href="#"><i class="icon-file"></i> <br/>Справочники <i class="icon-caret-right"></i></a>
                            <ul class="submenu">
                                <li class="submenu-section">Справочники</li>
                                <li class="submenu-item"><a href="{{ action('\Modules\Hermes\Http\Controllers\HermesController@getLibTerm') }}">Терминалы</a></li>
                            </ul>
                        </li>
                        @yield('sidebar')
			<li><a href="#"><i class="icon-info icon-2x"></i> <br/>About</a></li>
			<li><a href="#"><i class="icon-phone icon-2x"></i> <br/>Contact</a></li>
	    	</ul>
		<div class="content-area">
			<div class="container">
                            @yield('content')
			</div>

		</div>

        <script src="/includes/js/jquery-2.0.3.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js"></script>
        <script src="/includes/js/default.js"></script>
        <script type="text/javascript">
            @yield('script')
        </script>
    </body>
</html>