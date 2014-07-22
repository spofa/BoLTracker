@extends('layouts.master')

@section('content')

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
					<h2>Script Integration Tutorial</h2>

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

						<ol>
							<li>
								The first step is to use the Add Script page to add the name of the script you want to start keeping track of in the database. I would suggest having no spaces in it, as shown in the image. </p>
								<img src="http://i.imgur.com/QWZ2rvT.jpg" width="100%" />
							</li>
						<li>
							Once you have that done the integration process is fairly simple. You just need their encoded HWID (provideed in the section below) and the function provided below. A users HWID is not traceable back to either their LoL account or BoL account.</p>
							<pre><code>
function UpdateWeb(create)
	-- ID To associate your script with your account (This is already set to the correct ID)
	local id = {{{ Sentry::getUser()->id }}}

	-- Script name (Change me!)
	local scriptName = "Your Script Name (The same that you added with the add script page)"
	-- If false is passed it will set the script as deactivated in the database (this feature is only halfway working due to lack of consistant callbacks on the endings of games.). If it is true it will create a new record of the script in the database.
	if create then
		local successful, output = os.executePowerShellAsync("(New-Object System.Net.WebClient).DownloadString('http://104.131.230.83/rest/newplayer?id=".. id .."&hwid=".. hwid .. "&scriptName=".. scriptName .. "')")
	else
		local successful, output = os.executePowerShellAsync("(New-Object System.Net.WebClient).DownloadString('http://104.131.230.83/rest/deleteplayer?id=".. id .."&hwid=".. hwid .."&scriptName=".. scriptName .. "')")
	end
	
end
							</code></pre>
						</li>

						<li>
							You now need to use that function in the many callback methods in BoL.

							So far the callbacks I have mine integrated with are

							<pre><code>
function OnLoad()

	-- Define HWID
	hwid = Base64Encode(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION")))
	UpdateWeb(true)
end

function OnBugsplat()
	UpdateWeb(false)
end

function OnUnload()
	UpdateWeb(false)
end

-- Here is one I added to my OnTick to detect the end of the game
if GetGame().isOver then
	UpdateWeb(false)
	-- This is a var where I stop executing what is in my OnTick()
	startUp = false;
end
							</code></pre>
						</li>
						</ol>
						
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>

		</article>
	</div>

@stop

@section('customJS')

<link rel="stylesheet" href="/css/github.css">
<script src="/js/libs/highlight.pack.js"></script>
<script>
	$(document).ready(function() {
	  $('pre code').each(function(i, block) {
	    hljs.highlightBlock(block);
	  });
	});
</script>


@stop


@stop