@extends('layouts.master')

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
	
	<div class="row">

		<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
					
			<!-- Widget ID (each widget will need unique ID)-->
			
			<!-- end widget -->
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget" style="">
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
				<header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
					<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
					<h2>Script Creation</h2>

				<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

				<!-- widget div-->
				<div role="content">

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">


						<form class="form" role="form" id="newscript" action="/rest/createscript" method="post">
							
							<fieldset>
								<div class="form-group">
									<label class="sr-only" for="exampleInputEmail2">Script Name - Only Alpha Numerics (A-Z, 0-9) and No Spaces</label>
									<input type="text" name="scriptName" class="form-control" id="exampleInputEmail2" placeholder="Enter Script Name">
								</div>
								<input type="hidden" name="id" value="{{ Sentry::getUser()->id }}"/>

								<button type="submit" class="btn btn-primary">
									Create Script
								</button>
							</fieldset>
							
						</form>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>

		</article>
	</div>

@stop

@section('customJS')

<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			// fuelux wizard
			  var wizard = $('.wizard').wizard();
			  
			  wizard.on('finished', function (e, data) {
			    //$("#fuelux-wizard").submit();
			    //console.log("submitted!");
			    $.smallBox({
			      title: "Congratulations! Your form was submitted",
			      content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
			      color: "#5F895F",
			      iconSmall: "fa fa-check bounce animated",
			      timeout: 4000
			    });
			    
			  });

			$("#newscript").bootstrapValidator({
				message: 'This value is not valid',
				fields: {
					scriptName: {
						message: "The scriptname is not valid",
						validators: {
	                    	notEmpty: {
	                        	message: 'The Script is required and cannot be empty'
	                    	},
	                    	stringLength: {
		                        min: 2,
		                        max: 150,
		                        message: 'The Script must be more than 2 and less than 150 characters long'
	                    	},
	                    	regexp: {
		                        regexp: /^[a-zA-Z0-9]+$/,
		                        message: 'The Script can only consist of alphabetical, number and underscore'
	                    	}
	                	}
	            	}
                }
			});
		
		})

		</script>

@stop