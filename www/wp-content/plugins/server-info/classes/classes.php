<?php

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	class server_info {
	
		public static function infohouse_css_styles() {
		
		echo '<style>';
		
		include_once PLUGIN_DIR . 'assets/css/style.css';
		
		echo '</style>';
		 
		}
	
		public static function servinfo_admin_actions() {
			
			add_menu_page('Server Info', 'Server Info', 'administrator', 'server_info_display', array('server_info', 'display_infohouse_page'));
			
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
				
				</tr>
				
				<tr class="gray">
				
				<td><h5>MySQL Version:</h5></td>
				
				<td><p><?php mysql_connect(DB_HOST, DB_USER, DB_PASSWORD); echo mysql_get_server_info(); ?></p></td>
				
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
		
		public static function server_info_add_dashboard_widgets() {

		wp_add_dashboard_widget(
					 'serverinfo_dashboard_widget',
					 'Server Info',
					 array('server_info', 'server_info_dashboard_widget')
			);
		}
		
		public static function server_info_dashboard_widget() { ?>

				<table class="table infohouse_table dashboard_inf_table">
				
				<tr>
				
				<td><h5>Operating System:</h5></td>
				
				<td><p><?php echo php_uname("s"); ?></p></td>
				
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
				
				<td><h5>PHP Version:</h5></td>
				
				<td><p><?php echo phpversion(); ?></p></td>
				
				</tr>
				
				<tr>
				
				<td colspan="2" class="view-more-info"><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=server_info_display' ); ?>" ?>View More Information</a></td>
				
				</tr>
				
				</table> <?php
				
		}
	
	}

?>
