@extends('layouts.master')

@section('bread')

<i class="fa fa-pencil fa-fw "></i> Script Management <span>&gt; Edit Scripts </span>

@stop

@section('content')

@if (Session::has('error'))
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-times"></i>
	<strong>Error!</strong> {{{ Session::get('error') }}}
</div>
@endif

@if (Session::has('success'))
<div class="alert alert-success fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-check"></i>
	<strong>Success</strong> {{{ Session::get('success') }}}
</div>
@endif

<div class="">
	<div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false" role="widget" style="">
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
		<header role="heading">
			<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
			<h2>Scripts </h2>

		<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

		<!-- widget div-->
		<div role="content">

			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->

			</div>
			<!-- end widget edit box -->

			<!-- widget content -->
			<div class="widget-body" style="min-height:0px;">

				<div class="panel-group smart-accordion-default" id="accordion">
				@for($i = 0; $i < count($scripts); $i++)
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#{{{ $scripts[$i]->script_name }}}" @if($i != 0) class="collapsed" @endif> <i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i>{{{ $scripts[$i]->script_name }}}</a></h4>
						</div>
						<div id="{{{ $scripts[$i]->script_name }}}" @if ($i == 0) class="panel-collapse collapse in" @else class="panel-collapse collapse" @endif style="height: 0px;">
							<div class="panel-body">
								<form action="/rest/updatescript" method="post">		
									<fieldset>
										<input name="authenticity_token" type="hidden">
										<div class="form-group">
											<label>Script Name</label>
											<input class="form-control" value="{{{ $scripts[$i]->script_name }}}" name="scriptName" type="text">
										</div>
									</fieldset>
									<input type="hidden" value="{{{ $scripts[$i]->script_name }}}" name="oldScriptName" />
									<div class="form-actions">
										<a href="/rest/deletescript/{{{ $scripts[$i]->script_name }}}" class="btn btn-danger btn-md">
											<i class="fa fa-trash-o"></i>
											Delete Script
										</a>
										<button class="btn btn-primary btn-md" type="submit">
											<i class="fa fa-save"></i>
											Submit Change
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				@endfor
				</div>
			</div>
			<!-- end widget content -->

	</div>
	<!-- end widget div -->

</div>
</div>

@stop