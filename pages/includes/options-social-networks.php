<h3 class="col-xs-12 strong">Social networks Urls</h3>
<?php
	$social_fields = array(
		'fb' => array(
			'name' => 'Facebook',
			'field' => 'FbUrl',
			'icon' => 'fa-facebook'
		),
		'tw' => array(
			'name' => 'Twitter',
			'field' => 'TwUrl',
			'icon' => 'fa-twitter'
		),
		'ln' => array(
			'name' => 'Linked In',
			'field' => 'LiUrl',
			'icon' => 'fa-linkedin'
		),
		'gp' => array(
			'field' => 'GpUrl',
			'name' => 'Google Plus',
			'icon' => 'fa-google-plus'
		),
		'pi' => array(
			'field' => 'PiUrl',
			'name' => 'PInterest',
			'icon' => 'fa-pinterest'
		),
		'ig' => array(
			'name' => 'Instagram',
			'field' => 'IgUrl',
			'icon' => 'fa-instagram'
		),
		'yt' => array(
			'name' => 'YouTube',
			'field' => 'YtUrl',
			'icon' => 'fa-youtube'
		)
	)
?>
<?php foreach ($social_fields as $social_field_key => $social_field) { ?>
<div class="form-group">
	<label class="col-sm-2 control-label" for="url-<?php echo $social_field_key ?>"><?php echo $social_field['name'] ?></label>
	<div class="col-sm-10 input-group">
		<span class="input-group-addon"><i class="fa fa-fw <?php echo $social_field['icon'] ?>"></i></span>
		<input class="f-width form-control" type="url" id="url-<?php echo $social_field_key ?>"
		       data-bind="value: Options.<?php echo $social_field['field'] ?>"
		       placeholder="Enter <?php echo $social_field['name'] ?> Url"/>
	</div>
</div>
<?php } ?>