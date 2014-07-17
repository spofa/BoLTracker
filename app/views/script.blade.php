@extends('layouts.master')

@section('content')

	<!-- widget grid -->
<section id="widget-grid" class="">

<!-- row -->
<div class="row">
	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget" id="wid-id-7" data-widget-editbutton="false" data-widget-custombutton="true">
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
				<h2>{{{ $scriptName }}} Script Runs</h2>

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

					<div id="{{{ $scriptName }}}" class="chart no-padding"></div>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->
	</article>
</div>

<!-- end row -->


@stop

@section('customJS')

<script>
$(document).ready(function() {
	$.getJSON("rest/scriptruns/{{ $scriptName }}", function(data) {
		if ($('#{{ $scriptName }}').length) {
			var week_data = data;
			var {{ $scriptName }} = Morris.Line({
				element : '{{$scriptName}}',
				data : week_data,
				xkey : "period",
				ykeys : ['{{ $scriptName }}'],
				labels : ['{{ $scriptName }}'],
				events : ['{{ $startDate }}', '{{ $endDate }}']
			});

			function update() {
				$.getJSON("rest/scriptruns/{{ $scriptName }}", function(data) {
			  		{{$scriptName}}.setData(data);
			  	});
			}
			setInterval(update, 10000);
		}
	});
});
</script>

@stop