@extends('layouts.master')

@section('content')
<!-- widget grid -->
<section id="widget-grid" class="">

<!-- row -->
<div class="row">

	@foreach($scripts as $script)
	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<!-- Widget ID (each widget will need unique ID)-->
		<div class="jarviswidget" id="wid-id-7" data-widget-editbutton="false">
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
				<h2>{{ $script->script_name }} Script Runs</h2>

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

					<div id="{{ $script->script_name }}" class="chart no-padding"></div>

				</div>
				<!-- end widget content -->

			</div>
			<!-- end widget div -->

		</div>
		<!-- end widget -->
	</article>
	@endforeach
</div>

<!-- end row -->

@stop

@section('customJS')

<script>
$(document).ready(function() {

	@foreach($scripts as $script)

		$.getJSON("rest/scriptruns/{{ $script->script_name }}", function(data) {
			if ($('#{{ $script->script_name }}').length) {
				var week_data = data;
				var {{ $script->script_name }} = Morris.Line({
					element : '{{$script->script_name}}',
					data : week_data,
					xkey : "period",
					ykeys : ['{{ $script->script_name }}'],
					labels : ['{{ $script->script_name }}'],
					events : ['{{ $startDate }}', '{{ $endDate }}']
				});

				function update() {
					$.getJSON("rest/scriptruns/{{ $script->script_name }}", function(data) {
				  		{{$script->script_name}}.setData(data);
				  	});
				}
				setInterval(update, 1000);
			}
		});

	@endforeach
});
</script>

@stop