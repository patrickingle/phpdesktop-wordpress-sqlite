<?php

	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	
	add_action('admin_menu', array('server_info', 'servinfo_admin_actions'));

	add_action('admin_head', array('server_info', 'infohouse_css_styles'));
	
	add_action( 'wp_dashboard_setup', array('server_info', 'server_info_add_dashboard_widgets' ));
	
?>