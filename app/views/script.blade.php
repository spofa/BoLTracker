@extends('layouts.master')

@section('bread')

<i class="fa fa-bar-chart-o fa-fw "></i> Scripts <span>&gt; {{{$scriptName}}} </span>

@stop

@section('dashData')

<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
	<ul id="sparks" class="">
		<li class="sparks-info">
			<h5> Total Script Runs <span class="txt-color-blue"><a style="text-decoration:none;color:#57889c;" class="totalRuns">0</a> Runs</span></h5>
		</li>
		<li class="sparks-info">
			<h5> Unique Runs <span class="txt-color-greenDark"><a style="text-decoration:none;color:#496949;" class="uniqueUsers">0</a> Runs</span></h5>
		</li>
	</ul>
</div>

@stop

@section('content')


<section id="widget-grid" class="">

<!-- row -->
<div class="row">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-sortable="false">
			<!-- widget options:
			usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

			data-widget-colorbutton="false"
			data-widget-editbutton="false"
			data-widget-togglebutton="false"
			data-widget-deletebutton="false"
			data-widget-fullscreenbutton="false"
			data-widget-custombutton="false"
			data-widget-collapsed="true"
			data-widget-sortable="false"

			-->
			<header>
				<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
				<h2>{{$scriptName}} Script Runs</h2>

			</header>

			<!-- widget div-->
			<div>

				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->
					<input type="text">
				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<div class="widget-body no-padding">

					<div id="{{$scriptName}}-user-runs" class="chart no-padding"></div>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->

	</article>
	<!-- WIDGET END -->

</div>

<!-- end row -->

<!-- row -->

<!-- row -->
<div class="row">

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false" data-widget-sortable="false">
			<!-- widget options:
			usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

			data-widget-colorbutton="false"
			data-widget-editbutton="false"
			data-widget-togglebutton="false"
			data-widget-deletebutton="false"
			data-widget-fullscreenbutton="false"
			data-widget-custombutton="false"
			data-widget-collapsed="true"
			data-widget-sortable="false"

			-->
			<header>
				<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
				<h2>{{$scriptName}} Demographics</h2>

			</header>

			<!-- widget div-->
			<div>

				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->

				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<div class="widget-body no-padding">

					<div id="{{$scriptName}}-demographics" class="chart no-padding"></div>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->


	</article>

	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-sortable="false">
			<!-- widget options:
			usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

			data-widget-colorbutton="false"
			data-widget-editbutton="false"
			data-widget-togglebutton="false"
			data-widget-deletebutton="false"
			data-widget-fullscreenbutton="false"
			data-widget-custombutton="false"
			data-widget-collapsed="true"
			data-widget-sortable="false"

			-->
			<header>
				<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
				<h2>{{$scriptName}} Unique Runs</h2>

			</header>

			<!-- widget div-->
			<div>

				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->

				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<div class="widget-body no-padding">

					<div id="{{$scriptName}}-unique-runs" class="chart no-padding"></div>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->
	</article>
	</div>
</section>
@stop

@section('customJS')

<script>
$(document).ready(function() {

	var {{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_user_runs;
	var {{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_unique_runs;
	var {{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_active_users;
	var {{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_demographics;

	$.getJSON("/rest/scriptruns/{{ $scriptName }}", function(data) {
		if ($('#{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}-user-runs').length) {
			var week_data = data;
			{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_user_runs = Morris.Line({
				element : '{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}-user-runs',
				data : week_data,
				xkey : "period",
				ykeys : ['{{ $scriptName }}'],
				labels : ['{{ $scriptName }}'],
				xLabels: "day",
				events : ['{{ $startDate }}', '{{ $endDate }}']
			});
		}
	});

	$.getJSON("/rest/countries/{{$scriptName}}", function(data) {
		var jsonData = data;
		{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_demographics = Morris.Donut({
			element: '{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}-demographics',
			data: jsonData,
			formatter : function(x) {
				return x + "%"
			}
		});
	});

	$.getJSON("/rest/uniqueruns/{{preg_replace("/[^a-z0-9.]+/i", "", $scriptName)}}", function(data){
		var week_data = data;
		{{ preg_replace("/[^a-z0-9.]+/i", "", $scriptName) }}_unique_runs = Morris.Line({
			element : "{{preg_replace("/[^a-z0-9.]+/i", "", $scriptName)}}-unique-runs",
			data : week_data,
			xkey : "period",
			ykeys : ['{{$scriptName}}'],
			labels : ['{{$scriptName}}'],
			xLabels: "day",
			events : ['{{$startDate}}', '{{$endDate}}']
		});
	});

	$.getJSON("/rest/uniqueusers/{{ $scriptName }}", function(data) {
		$('.uniqueUsers').prop('number', $('.uniqueUsers').html()).animateNumber({
		      number: data
		    }, 
		    2000
		);
	});

	$.getJSON("/rest/weeklyruns/{{ $scriptName }}", function(data) {
		$('.totalRuns').prop('number', $('.totalRuns').html()).animateNumber({
		      number: data
		    }, 
		    2000
		);
	});

	function update() {
				// Consolidate these into one func later.
		$.getJSON("/rest/scriptruns/{{ $scriptName }}", function(data) {
	  		{{preg_replace("/[^a-z0-9.]+/i", "", $scriptName)}}_user_runs.setData(data);
	  	});
		$.getJSON("/rest/uniqueruns/{{ $scriptName }}", function(data) {
	  		{{preg_replace("/[^a-z0-9.]+/i", "", $scriptName)}}_unique_runs.setData(data);
	  	});
	  	$.getJSON("/rest/uniqueusers/{{ $scriptName }}", function(data) {
	  		$('.uniqueUsers')
			  .prop('number', $('.uniqueUsers').html())
			  .animateNumber(
			    {
			      number: data
			    },
			    2000
			  );
	  	});

	}

	setInterval(update, 30000);

});
</script>

@stop