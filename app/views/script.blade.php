@extends('layouts.master')

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
		<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-sortable="false">
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
				<h2>{{$scriptName}} Unique Users</h2>

			</header>

			<!-- widget div-->
			<div>

				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->

				</div>
				<!-- end widget edit box -->

				<!-- widget content -->
				<div class="widget-body no-padding" style="text-align:center;">

					<span class="{{$scriptName}}-unique-users" style="font-weight:bold;font-size:64px;">0</span>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->

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
				<h2>{{$scriptName}} Active Users</h2>

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

					<div id="{{$scriptName}}-active-users" class="chart no-padding"></div>

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

	var {{ $scriptName }}_user_runs;
	var {{ $scriptName }}_unique_runs;
	var {{ $scriptName }}_active_users;

	$.getJSON("/rest/scriptruns/{{ $scriptName }}", function(data) {
		if ($('#{{ $scriptName }}-user-runs').length) {
			var week_data = data;
			{{ $scriptName }}_user_runs = Morris.Line({
				element : '{{$scriptName}}-user-runs',
				data : week_data,
				xkey : "period",
				ykeys : ['{{ $scriptName }}'],
				labels : ['{{ $scriptName }}'],
				events : ['{{ $startDate }}', '{{ $endDate }}']
			});
		}
	});

	$.getJSON("/rest/activeusers/{{$scriptName}}", function(data){
		var week_data = data;
		{{ $scriptName }}_active_users = Morris.Bar({
			element : "{{$scriptName}}-active-users",
			data : week_data,
			xkey : "period",
			ykeys : ['{{$scriptName}}'],
			labels : ['{{$scriptName}}'],
		});
	});

	$.getJSON("/rest/uniqueruns/{{$scriptName}}", function(data){
		var week_data = data;
		{{ $scriptName }}_unique_runs = Morris.Line({
			element : "{{$scriptName}}-unique-runs",
			data : week_data,
			xkey : "period",
			ykeys : ['{{$scriptName}}'],
			labels : ['{{$scriptName}}'],
			events : ['{{$startDate}}', '{{$endDate}}']
		});
	});

	$.getJSON("/rest/uniqueusers/{{ $scriptName }}", function(data) {

		$('.{{ $scriptName }}-unique-users')
		  .prop('number', $('.{{ $scriptName }}-unique-users').html())
		  .animateNumber(
		    {
		      number: data
		    },
		    2000
		  );
		$('.{{ $scriptName }}-unique-users').html(data);
	});

	function update() {
				// Consolidate these into one func later.
		$.getJSON("/rest/scriptruns/{{ $scriptName }}", function(data) {
	  		{{$scriptName}}_user_runs.setData(data);
	  	});
		$.getJSON("/rest/uniqueruns/{{ $scriptName }}", function(data) {
	  		{{$scriptName}}_unique_runs.setData(data);
	  	});
		$.getJSON("/rest/activeusers/{{ $scriptName }}", function(data) {
	  		{{$scriptName}}_active_users.setData(data);
	  	});
	  	$.getJSON("/rest/uniqueusers/{{ $scriptName }}", function(data) {
	  		$('.{{ $scriptName }}-unique-users')
			  .prop('number', $('.{{ $scriptName }}-unique-users').html())
			  .animateNumber(
			    {
			      number: data
			    },
			    2000
			  );
	  	});

	}

	setInterval(update, 10000);

});
</script>

@stop