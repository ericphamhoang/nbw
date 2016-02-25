<?php /* ADMIN OPTION PAGE */ ?>
<h1><span class="strong">NBW</span> settings</h1>
<br/>
<div id="NBW-wrapper" class="innerR">
	<div data-role="tabstrip">
		<ul>
			<li class="k-state-active">Basic details</li>
			<li>Social network Urls</li>
		</ul>
		<div><?php include_once __DIR__ . '/includes/options-basic-details.php'?></div>
		<div><?php include_once __DIR__ . '/includes/options-social-networks.php'?></div>
	</div>
	<hr/>
	<div class="form-group">
		<div class="col-sm-offset-2">
			<button id="btn-save" class="btn btn-primary" data-bind="events: {click: events.save_clicked}">
				<i class="fa fa-fw fa-save"></i>&emsp;
				<span class="strong">Save changes</span>
			</button>
		</div>
	</div>
	<hr/>
	<i class="light text-muted small">Copyright &copy; <?php echo date_format(new \DateTime(), 'Y') ?> - Nothing But Web</i>
</div>
<?php /* INITIATING JAVASCRIPT */ ?>
<script id="page-post-template" type="text/x-kendo-template">
	# if (data.id) { #
	<span class="strong">#: data.title #</span> <i><span class="light">(#: data.name #)</span></i>
	# } else { # #: data.title # # } #
</script>
<script lang="javascript">
    jQuery(document).ready(function(){
        NBW.Admin.init(jQuery("#NBW-wrapper"), <?php echo json_encode($model); ?>);
    })
</script>