<?php
/*
Plugin Name: Phpinfo
Plugin URI: http://wordpress.designpraxis.at
Description: Prints out your webservers php settings and WordPress environmental information. Important for posting within various WordPress support forums.
Version: 1.1
Author: Roland Rust
Author URI: http://wordpress.designpraxis.at
*/

add_action('init', 'dprx_phpinfo_init_locale',98);
function dprx_phpinfo_init_locale() {
	$locale = get_locale();
	$mofile = dirname(__FILE__) . "/locale/".$locale.".mo";
	load_textdomain('dprx_phpinfo', $mofile);
}

add_action('admin_menu', 'dprx_phpinfo_add_admin_pages');

function dprx_phpinfo_add_admin_pages() {
	add_options_page('Phpinfo', 'Phpinfo', 10, __FILE__, 'dprx_phpinfo_manage_page');
}

function dprx_phpinfo_manage_page() {
	$info = array();
	$info['max_execution_time'] = __("Php scripts are allowed to run for","dprx_phpinfo")." ".ini_get('max_execution_time')." ".__("seconds","dprx_phpinfo");
	if (ini_get('max_input_time') == -1) {
		$info['max_input_time'] = __("Your webserver will wait for form data for","dprx_phpinfo")." ".ini_get('max_execution_time')." ".__("seconds","dprx_phpinfo");
	} else {
		$info['max_input_time'] = __("Your webserver won't wait for form data longer than","dprx_phpinfo")." ".ini_get('max_input_time')." ".__("seconds","dprx_phpinfo");
	}
	if ((ini_get('file_uploads') == 1) || (ini_get('file_uploads') == "On")) {
		$info['file_uploads'] = __("File uploads are enabled","dprx_phpinfo");
	} else {
		$info['file_uploads'] = __("File uploads are disabled","dprx_phpinfo");
	}
	$info['upload_max_filesize'] = __("File Uploads are limited to","dprx_phpinfo")." ".ini_get('upload_max_filesize');
	$info['post_max_size'] = __("Post data is limited to","dprx_phpinfo")." ".ini_get('post_max_size');
	if ((ini_get('register_globals') == 1) || (ini_get('register_globals') == "On")) {
		$info['register_globals'] = __("Global variables are registered","dprx_phpinfo");
	} else {
		$info['register_globals'] = __("Global variables are not registered","dprx_phpinfo");
	}
	if ((ini_get('safe_mode') == 1) || (ini_get('safe_mode') == "On")) {
		$info['safe_mode'] = __("Safe Mode is On","dprx_phpinfo");
	} else {
		$info['safe_mode'] = __("Safe Mode is Off","dprx_phpinfo");
	}
	if ((ini_get('allow_url_fopen') == 1) || (ini_get('allow_url_fopen') == "On")) {
		$info['allow_url_fopen'] = __("Remote files can be opended by fopen()","dprx_phpinfo");
	} else {
		$info['allow_url_fopen'] = __("Remote files can be not opended by fopen()","dprx_phpinfo");
	}
	
	if ((ini_get('eaccelerator.enable') == 1)) {
		$info['eaccelerator.enable'] = __("eAccelerator is enabled","dprx_phpinfo");
	} else {
		$info['eaccelerator.enable'] = __("eAccelerator is not enabled","dprx_phpinfo");
	}
	?>
	<div class=wrap>
		<h2><?php _e('Phpinfo') ?></h2>
		<table class="widefat" id="bkpwp_manage_backups_table">
		<thead>
		<tr>
			<th scope="col"><?php _e("Configuration","dprx_phpinfo"); ?></th>
			<th scope="col"><?php _e("php_ini","dprx_phpinfo"); ?></th>
			<th scope="col"><?php _e("value","dprx_phpinfo"); ?></th>
		</tr>
		</thead>
		<?php
		foreach($info as $key => $value) {
		?><tr>
			<td>
			<?php echo $value; ?>
			</td>
			<td>
			<?php echo $key; ?>
			</td>
			<td>
			<?php echo ini_get($key); ?>
			</td>
		</tr><?php
		}
		?>
		</table>
		<p><b><?php _e("Copy the contents of the field below for posting within support forums","dprx_phpinfo"); ?></b></p>
		<textarea style="width:100%; height: 260px;"><?php
		_e("PHP information for","dprx_phpinfo");
		echo " ";
		bloginfo("url");
		echo "\n\n";
		foreach($info as $key => $value) {
		?><?php echo $key; ?>: <?php echo ini_get($key)."\n"; ?><?php
		}
		echo "\n\n";
		_e("Other information:","dprx_phpinfo");
		echo "\n\n";
		?>PHP Server API: <?php echo php_sapi_name()."\n"; ?><?php
		?>WordPress Version: <?php echo $GLOBALS['wp_version']."\n"; ?><?php
		?>WordPress Blog URI: <?php echo get_bloginfo("url")."\n"; ?><?php
		?>WordPress Installation URI: <?php echo get_bloginfo("wpurl")."\n"; ?><?php
		?>WordPress Theme: <?php echo str_replace(get_bloginfo("wpurl"),"",get_bloginfo("template_directory"))."\n"; ?><?php
		?>WordPress Permalink Structure: <?php echo get_option('permalink_structure')."\n"; ?><?php
		if (is_writable(ABSPATH."wp-content")) {
		?>WordPress wp-content directory is writable<?php echo "\n";
		} else {
		?>WordPress wp-content directory  is not writable<?php echo "\n";
		}
		$gzip = get_option('gzipcompression');
		if (!empty($gzip)) {
		?>WordPress uses Gzip Compression<?php echo "\n";
		} else {
		?>WordPress does not use Gzip Compression<?php echo "\n";
		}
		if (WP_CACHE == true) {
		?>WP-Cache is running on this installation of WordPress<?php echo "\n";
		} else {
		?>WP-Cache is not running on this installation of WordPress<?php echo "\n";
		}
		if ( ! function_exists('imagecreatefromstring') ) {
		?>GD library is not installed<?php echo "\n";
		} else {
		?>GD library is installed<?php echo "\n";
		}
		?>PHP Version: <?php echo phpversion()."\n"; ?><?php
		?>MySQL Version: <?php echo mysql_get_server_info()."\n"; ?><?php
		?>Browser used: <?php echo $_SERVER['HTTP_USER_AGENT']."\n"; ?><?php
		?></textarea>
	</div>
	<div class="wrap">
		<p>
		<?php _e("Running into Troubles? Features to suggest?","dprx_phpinfo"); ?>
		<a href="http://wordpress.designpraxis.at/">
		<?php _e("Drop me a line","dprx_phpinfo"); ?> &raquo;
		</a>
		</p>
		<div style="display: block; height:30px;">
			<div style="float:left; font-size: 16px; padding:5px 5px 5px 0;">
			<?php _e("Do you like this Plugin?","dprx_phpinfo"); ?>
			<?php _e("Consider to","dprx_phpinfo"); ?>
			</div>
			<div style="float:left;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="rol@rm-r.at">
			<input type="hidden" name="no_shipping" value="0">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="tax" value="0">
			<input type="hidden" name="lc" value="AT">
			<input type="hidden" name="bn" value="PP-DonationsBF">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Please donate via PayPal!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			</div>
		</div>
	</div>
	<?php
	//phpinfo();
}
?>
