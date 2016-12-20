<?php
/*
 * Plugin Name: sqlite-server-info
 * Plugin URI: https://github.com/patrickingle/phpdesktop-wordpress-sqlite
 * Description: Extends server-info plugin to show sqlite status
 * Author: PHK Corporation
 * Version: 1.0
 * Author URI: http://phkcorp.com/
*/

if (class_exists('server_info')) {


	class sqlite_server_info {
		public static function servinfo_admin_actions() {

			add_menu_page(
				'Server Info',
				'Server Info',
				'administrator',
				'server_info_display',
				array('sqlite_server_info', 'display_infohouse_page')
			);

		}

		public static function display_infohouse_page()
		{
			global $wpdb;
			?>
			<div class="wrap">
			<h2 class="infohouse_heading">Server Info</h2>
			<p>Server Info plugin shows you the general information about the hosting server your WordPress site is currently hosted on. You can find this information helpful for many purposes.</p>
			<br />
			<div class="infohouse_settings_page">
				<div class="table-responsive">
				<table class="table infohouse_table">
				<tr>
				<td><h5>Operating System:</h5></td>
				<td><p><?php echo php_uname('s'); ?></p></td>
				</tr>
				<tr class="gray">
				<td><h5>Server IP:</h5></td>
				<td><p><?php echo $_SERVER['SERVER_ADDR']; ?></p></td>
				</tr>
				<tr>
				<td><h5>Server Hostame:</h5></td>
				<td><p><?php echo php_uname('n'); ?></p></td>
				</tr>
				<tr class="gray">
				<td><h5>Server Protocol:</h5></td>
				<td><p><?php echo $_SERVER['SERVER_PROTOCOL']; ?></p></td>
				</tr>
				<tr>
				<td><h5>Server Administrator:</h5></td>
				<td><p><?php echo $_SERVER['SERVER_ADMIN']; ?></p></td>
				</tr>
				<tr class="gray">
				<td><h5>Server Web Port:</h5></td>
				<td><p><?php echo $_SERVER['SERVER_PORT']; ?></p></td>
				</tr>
				<tr>
				<td><h5>PHP Version:</h5></td>
				<td><p><?php echo phpversion(); ?></p></td>
				<tr class="gray">
				<td><h5>MySQL Version:</h5></td>
				<td><p>Not connected to MySQL</p></td>
				</tr>
				</tr>
				<tr class="gray">
				<td><h5>Sqlite Version:</h5></td>
				<td><p><?php echo $wpdb->db_version(); ?></p></td>
				</tr>
				<tr>
				<tr class="gray">
				<td><h5>CGI Version:</h5></td>
				<td><p><?php echo $_SERVER['GATEWAY_INTERFACE']; ?></p></td>
				</tr>
				<tr>
				<td><h5>System Uptime:</h5></td>
				<td><p><?php echo exec("uptime", $system); ?></p></td>
				</tr>
				<tr>
				<td><h5>WordPress Memory Limit:</h5></td>
				<td><p><?php echo WP_MEMORY_LIMIT; ?></p></td>
				</tr>
				</table>
				</div>
			</div>
			<?php
		}
	}

	remove_action('admin_menu',array('server_info', 'servinfo_admin_actions'));
	add_action('admin_menu', array('sqlite_server_info', 'servinfo_admin_actions'));
}
?>