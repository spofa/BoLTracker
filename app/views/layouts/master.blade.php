<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> BoL Tracker </title>
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/css/font-awesome.min.css">
		<link rel="stylesheet" href="/css/github.css">
		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="/css/smartadmin-production.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/css/smartadmin-skins.css">
		<link rel="stylesheet" href="/css/bootstrapValidator.min.css"/>

		<!-- SmartAdmin RTL Support is under construction
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="/css/demo.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	</head>
	<body class="">
		<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <a href="/"><img src="/img/logo.png" alt="SmartAdmin"></a></span>
				<!-- END LOGO PLACEHOLDER -->
				
			</div>

			
			<!-- pulled right: nav area -->
			<div class="pull-right">

				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="/auth/logout" title="Sign Out"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->


			</div>
			<!-- end pulled right: nav area -->

		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<div class="dropdown" style="margin-left:10px;"> <!-- User image size is adjusted inside CSS, it should stay as it --> 
					
					<a href="#" class="username-toggle" data-toggle="dropdown" id="show-shortcut">
						<img src="/img/avatars/sunny.png" alt="me" class="online" /> 
						<span>
							{{{ explode("@", Sentry::getUser()->email)[0] }}} 
						</span>
						<i class="fa fa-angle-down"></i>
					</a> 
					<ul class="dropdown-menu">
			            <li><a href="/script/create/new">Add scripts</a></li>
			            <li><a href="/script/create/edit">Edit scripts</a></li>
			            <li class="divider"></li>
			            <li><a href="#">Edit User</a></li>
			        </ul>
					
				</div>
			</div>
			<!-- end user info -->

			<!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional hre="" links. See documentation for details.
				-->

				<ul>
					<li class="@if(Route::currentRouteName() == 'dashboard') active @endif">
						<a href="/" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard </span></a>
					</li>
					<li class="@if(Route::currentRouteName() == 'tutorial' || Route::currentRouteName() == 'newScript' || Route::currentRouteName() == 'editScript') open @endif">
						<a href="#"><i class="fa fa-lg fa-fw fa-edit"></i> <span class="menu-item-parent">Script Management</span></a>
						<ul>
							<li class="@if(Route::currentRouteName() == 'tutorial') active @endif">
								<a href="/script/create/tutorial"><i class="fa fa-lg fa-fw fa-book" style="margin-right:4px;"></i><span class="menu-item-parent">Tutorial</span></a>
							</li>
							<li class="@if(Route::currentRouteName() == 'newScript') active @endif">
								<a href="/script/create/new"><i class="fa fa-lg fa-fw fa-plus-circle"></i> <span class="menu-item-parent">Add Scripts</span></a>
							</li>
							<li class="@if(Route::currentRouteName() == 'editScript') active @endif">
								<a href="/script/create/edit"><i class="fa fa-lg fa-fw fa-pencil"></i> <span class="menu-item-parent">Edit Scripts</span></a>
							</li>
							</ul>
					</li>
					<li class="@if(Route::currentRouteName() == 'singleScript') open @endif">
						<a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Your Scripts</span></a>
						<ul>
							@if(count($scripts) == 0) 
							<li>
								<a href="#">No Scripts Added</a>
							</li>
							@else
							@foreach($scripts as $script)
							<li class="<?php if(isset($scriptName)) { 
												if($scriptName == $script->script_name) {
													echo 'active'; 
												}
											} ?>
											">
								<a href="/script/{{{ $script->script_name }}}">{{{ $script->script_name }}}</a>
							</li>

							@endforeach
							@endif
						</ul>
					</li>
				</ul>
			</nav>
			<span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>

		</aside>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
				<!-- Not sure what to put here... -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="alert alert-success fade in">
					<button class="close" data-dismiss="alert">
						×
					</button>
					<i class="fa-fw fa fa-check-circle-o"></i>
					<strong>New Update!</strong> I have cleaned up a lot of backend code to make things run smoother! I have also added basic demographics. For now a lot of the data hasn't been tracked with demographics, but don't worry, it will populate soon!
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"> @yield('bread') </h1>
					</div>
					@yield('dashData')
				</div>

				@yield('content')
			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="/js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
		<script src="/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->
		<script src="/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="/js/plugin/jquery-validate/jquery.validate.min.js"></script>
		<script type="text/javascript" src="/js/bootstrapValidator.min.js"></script>
		<!-- JQUERY MASKED INPUT -->
		<script src="/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="/js/plugin/fastclick/fastclick.js"></script>

		<script src="/js/jquery.animateNumber.min.js"></script>

		<!--[if IE 7]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="/js/app.js"></script>
		
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="/js/plugin/fuelux/wizard/wizard.js"></script>
		<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
		<script src="/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>
		
		<!-- Full Calendar -->
		<script src="/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>
		<script src="/js/libs/highlight.pack.js"></script>
		<!-- Morris Chart Dependencies -->
		<script src="/js/plugin/morris/raphael.2.1.0.min.js"></script>
		<script src="/js/plugin/morris/morris.min.js"></script>

		 @yield('customJS')

		<script>

			$(document).ready(function() {

				// DO NOT REMOVE : GLOBAL FUNCTIONS!
				pageSetUp();

				$(".username-toggle").dropdown();

				/*
				 * PAGE RELATED SCRIPTS
				 */

				$(".js-status-update a").click(function() {
					var selText = $(this).text();
					var $this = $(this);
					$this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
					$this.parents('.dropdown-menu').find('li').removeClass('active');
					$this.parent().addClass('active');
				});

				/*
				* TODO: add a way to add more todo's to list
				*/
			});

		</script>

		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-53062921-1', 'auto');
		  ga('send', 'pageview');

		</script>
	</body>

</html>
