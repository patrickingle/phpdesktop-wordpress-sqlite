<?php
/*
Plugin Name: Smarty for Wordpress
Plugin URI: http://www.phkcorp.com?do=wordpress
Description: Adds the Smarty Template Engine to Wordpress for ease of migration of themes
Author: PHK Corporation for enablement
Version: 3.1.30.1
Author URI: http://www.phkcorp.com/
*/

//require_once(dirname(__FILE__)."/libs/Smarty.class.php");
require_once(dirname(__FILE__)."/libs/SmartyBC.class.php");

$s4w_smarty = null;

function smarty_create_tempdir($s4w_smarty) {
    if (is_array($s4w_smarty->template_dir)) {
        foreach ($s4w_smarty->template_dir as $template_dir) {
            if (@file_exists($template_dir) === false) {
                @mkdir($template_dir);
            }
        }
    } else {
        if (file_exists($s4w_smarty->template_dir) === false) {
            mkdir($s4w_smarty->template_dir);
        }
    }
    if (is_array($s4w_smarty->compile_dir)) {
        foreach ($s4w_smarty->compile_dir as $compile_dir) {
            if (@file_exists($compile_dir) === false) {
                @mkdir($compile_dir);
            }
        }
    } else {
        if (file_exists($s4w_smarty->compile_dir) === false) {
            mkdir($s4w_smarty->compile_dir);
        }
    }
    if (is_array($s4w_smarty->config_dir)) {
        foreach ($s4w_smarty->config_dir as $config_dir) {
            if (@file_exists($config_dir) === false) {
                @mkdir($config_dir);
            }
        }
    } else {
        if (file_exists($s4w_smarty->config_dir) === false) {
            mkdir($s4w_smarty->config_dir);
        }
    }
    if (file_exists($s4w_smarty->cache_dir) === false) {
        mkdir($s4w_smarty->cache_dir);
        chmod($s4w_smarty->cache_dir, 0777 );
    }
}

function smarty_get_instance($demo=FALSE)
{
        global $s4w_smarty;

        if (get_option('s4w_smartybc','0') == '1') {
            $s4w_smarty = new Smarty();
        } else {
            $s4w_smarty = new SmartyBC();
        }
        
	$theme_path = smarty_get_themes_path();

	if ($demo === TRUE) {
		$demo_path =  plugin_dir_path( __FILE__ ) . 'demo';

		$s4w_smarty->template_dir = $demo_path . "/templates";
		$s4w_smarty->compile_dir  = $demo_path . "/templates_c";
		$s4w_smarty->config_dir  = $demo_path . "/configs";
		$s4w_smarty->cache_dir  = $demo_path . "/cache";
	} else if (defined('WP_USE_THEMES') && WP_USE_THEMES == true) {
		$s4w_smarty->template_dir = $theme_path . "/templates";
		$s4w_smarty->compile_dir  = $theme_path . "/templates_c";
		$s4w_smarty->config_dir  = $theme_path . "/config";
		$s4w_smarty->cache_dir  = $theme_path . "/cache";
		//$s4w_smarty->plugins_dir[]  = $theme_path . "/plugins";
		//$s4w_smarty->trusted_dir  = $theme_path . "/trusted";
	} else {
	    if (defined('SMARTY_PATH')) {
			$s4w_smarty->template_dir = SMARTY_PATH . "/templates";
			$s4w_smarty->compile_dir  = SMARTY_PATH . "/templates_c";
			$s4w_smarty->config_dir  = SMARTY_PATH . "/config";
			$s4w_smarty->cache_dir  = SMARTY_PATH . "/cache";
			//$s4w_smarty->plugins_dir[]  = SMARTY_PATH . "/plugins";
			//$s4w_smarty->trusted_dir  = SMARTY_PATH . "/trusted";
	    }
	}
        
        smarty_create_tempdir($s4w_smarty);

        $s4w_smarty->smartybc = (get_option('s4w_smartybc','0') == '1' ?  true : false );
	$s4w_smarty->auto_literal = (get_option('s4w_auto_literal','0') == '1' ?  true : false );
	$s4w_smarty->cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_smarty->cache_modified_check = (get_option('s4w_cache_modified_check','0') == '1' ? true : false );
	$s4w_smarty->config_booleanize = (get_option('s4w_config_bolleanized','0') == '1' ? true : false );
	$s4w_smarty->config_overwrite = (get_option('s4w_config_overwrite','0') == '1' ? true : false );
	$s4w_smarty->config_read_hidden = (get_option('s4w_config_read_hidden','0') == '1' ? true : false );
	$s4w_smarty->debugging = (get_option('s4w_debugging','0') == '1' ? true : false );
	$s4w_smarty->force_compile = (get_option('s4w_force_compile','0') == '1' ? true : false );
	$s4w_smarty->php_handling = get_option('s4w_php_handling',0);
	$s4w_smarty->use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? true : false );

	return $s4w_smarty;
}

function smarty_load_template($atts, $content=null, $code="")
{
	extract(shortcode_atts(array(
			'tpl' => '#',
			'name' => '',
			'value' => '',
		), $atts));

	$tpl  = "{$tpl}";
	$name  = "{$name}";
	$value = "{$value}";

        global $s4w_smarty;

        if (get_option('s4w_smartybc','0') == '1') {
            $s4w_smarty = new Smarty();
        } else {
            $s4w_smarty = new SmartyBC();
        }

        $s4w_smarty->assign_by_ref($name,$value);

	$theme_path = smarty_get_themes_path();

	if (defined('WP_USE_THEMES') && WP_USE_THEMES == true) {
		$s4w_smarty->template_dir = $theme_path . "/templates";
		$s4w_smarty->compile_dir  = $theme_path . "/templates_c";
		$s4w_smarty->config_dir  = $theme_path . "/config";
		$s4w_smarty->cache_dir  = $theme_path . "/cache";
		//$s4w_smarty->plugins_dir[]  = $theme_path . "/plugins";
		//$s4w_smarty->trusted_dir  = $theme_path . "/trusted";
	} else {
	    if (defined('SMARTY_PATH')) {
			$s4w_smarty->template_dir = SMARTY_PATH . "/templates";
			$s4w_smarty->compile_dir  = SMARTY_PATH . "/templates_c";
			$s4w_smarty->config_dir  = SMARTY_PATH . "/config";
			$s4w_smarty->cache_dir  = SMARTY_PATH . "/cache";
			//$s4w_smarty->plugins_dir[]  = SMARTY_PATH . "/plugins";
			//$s4w_smarty->trusted_dir  = SMARTY_PATH . "/trusted";
	    }
	}

        smarty_create_tempdir($s4w_smarty);
        
        $s4w_smarty->smartybc = (get_option('s4w_smartybc','0') == '1' ?  true : false );
	$s4w_smarty->auto_literal = (get_option('s4w_auto_literal','0') == '1' ?  true : false );
	$s4w_smarty->cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_smarty->cache_modified_check = (get_option('s4w_cache_modified_check','0') == '1' ? true : false );
	$s4w_smarty->config_booleanize = (get_option('s4w_config_bolleanized','0') == '1' ? true : false );
	$s4w_smarty->config_overwrite = (get_option('s4w_config_overwrite','0') == '1' ? true : false );
	$s4w_smarty->config_read_hidden = (get_option('s4w_config_read_hidden','0') == '1' ? true : false );
	$s4w_smarty->debugging = (get_option('s4w_debugging','0') == '1' ? true : false );
	$s4w_smarty->force_compile = (get_option('s4w_force_compile','0') == '1' ? true : false );
	$s4w_smarty->php_handling = get_option('s4w_php_handling',0);
	$s4w_smarty->use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? true : false );

	$s4w_smarty->display($tpl);
}

function smarty_assign_by_reference($atts, $content=null, $code="")
{
	extract(shortcode_atts(array(
			'name' => '#',
			'value' => '#',
		), $atts));

	$name  = "{$name}";
	$value = "{$value}";

        global $s4w_smarty;

        if (get_option('s4w_smartybc','0') == '1') {
            $s4w_smarty = new Smarty();
        } else {
            $s4w_smarty = new SmartyBC();
        }

	$theme_path = smarty_get_themes_path();

	if (defined('WP_USE_THEMES') && WP_USE_THEMES == true) {
		$s4w_smarty->template_dir = $theme_path . "/templates";
		$s4w_smarty->compile_dir  = $theme_path . "/templates_c";
		$s4w_smarty->config_dir  = $theme_path . "/config";
		$s4w_smarty->cache_dir  = $theme_path . "/cache";
		//$s4w_smarty->plugins_dir[]  = $theme_path . "/plugins";
		//$s4w_smarty->trusted_dir  = $theme_path . "/trusted";
	} else {
	    if (defined('SMARTY_PATH')) {
			$s4w_smarty->template_dir = SMARTY_PATH . "/templates";
			$s4w_smarty->compile_dir  = SMARTY_PATH . "/templates_c";
			$s4w_smarty->config_dir  = SMARTY_PATH . "/config";
			$s4w_smarty->cache_dir  = SMARTY_PATH . "/cache";
			//$s4w_smarty->plugins_dir[]  = SMARTY_PATH . "/plugins";
			//$s4w_smarty->trusted_dir  = SMARTY_PATH . "/trusted";
	    }
	}
        
        smarty_create_tempdir($s4w_smarty);

        $s4w_smarty->smartybc = (get_option('s4w_smartybc','0') == '1' ?  true : false );
	$s4w_smarty->auto_literal = (get_option('s4w_auto_literal','0') == '1' ?  true : false );
	$s4w_smarty->cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_smarty->cache_modified_check = (get_option('s4w_cache_modified_check','0') == '1' ? true : false );
	$s4w_smarty->config_booleanize = (get_option('s4w_config_bolleanized','0') == '1' ? true : false );
	$s4w_smarty->config_overwrite = (get_option('s4w_config_overwrite','0') == '1' ? true : false );
	$s4w_smarty->config_read_hidden = (get_option('s4w_config_read_hidden','0') == '1' ? true : false );
	$s4w_smarty->debugging = (get_option('s4w_debugging','0') == '1' ? true : false );
	$s4w_smarty->force_compile = (get_option('s4w_force_compile','0') == '1' ? true : false );
	$s4w_smarty->php_handling = get_option('s4w_php_handling',0);
	$s4w_smarty->use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? true : false );

	/**
	 * per ticket #79 - function call 'assign_by_ref' is unknown or deprecated
	 *
	 * $s4w_smarty->assign_by_ref($name,$value);
	 */
	$s4w_smarty->assignByRef($name,$value);
}

function smarty_array_assign_by_reference($atts, $content=null, $code="")
{
	extract(shortcode_atts(array(
			'tpl' => '#',
			'name' => '',
			'value' => '',
		), $atts));

	$tpl  = "{$tpl}";

	$t1 = explode(",",$name);
	$t2 = explode(",",$value);

        global $s4w_smarty;

        if (get_option('s4w_smartybc','0') == '1') {
            $s4w_smarty = new Smarty();
        } else {
            $s4w_smarty = new SmartyBC();
        }

	$theme_path = smarty_get_themes_path();

	for($i=0;$i<count($t1);$i++)
	{
		/**
		 * per ticket #79 - function call 'assign_by_ref' is unknown or deprecated
		 *
		 * $s4w_smarty->assign_by_ref($t1[$i],$t2[$i]);
		 */
	    $s4w_smarty->assignByRef($t1[$i],$t2[$i]);
	}

	if (defined('WP_USE_THEMES') && WP_USE_THEMES == true) {
		$s4w_smarty->template_dir = $theme_path . "/templates";
		$s4w_smarty->compile_dir  = $theme_path . "/templates_c";
		$s4w_smarty->config_dir  = $theme_path . "/config";
		$s4w_smarty->cache_dir  = $theme_path . "/cache";
		//$s4w_smarty->plugins_dir[]  = $theme_path . "/plugins";
		//$s4w_smarty->trusted_dir  = $theme_path . "/trusted";
	} else {
	    if (defined('SMARTY_PATH')) {
			$s4w_smarty->template_dir = SMARTY_PATH . "/templates";
			$s4w_smarty->compile_dir  = SMARTY_PATH . "/templates_c";
			$s4w_smarty->config_dir  = SMARTY_PATH . "/config";
			$s4w_smarty->cache_dir  = SMARTY_PATH . "/cache";
			//$s4w_smarty->plugins_dir[]  = SMARTY_PATH . "/plugins";
			//$s4w_smarty->trusted_dir  = SMARTY_PATH . "/trusted";
	    }
	}
        
        smarty_create_tempdir($s4w_smarty);

        $s4w_smarty->smartybc = (get_option('s4w_smartybc','0') == '1' ?  true : false );
	$s4w_smarty->auto_literal = (get_option('s4w_auto_literal','0') == '1' ?  true : false );
	$s4w_smarty->cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_smarty->cache_modified_check = (get_option('s4w_cache_modified_check','0') == '1' ? true : false );
	$s4w_smarty->config_booleanize = (get_option('s4w_config_bolleanized','0') == '1' ? true : false );
	$s4w_smarty->config_overwrite = (get_option('s4w_config_overwrite','0') == '1' ? true : false );
	$s4w_smarty->config_read_hidden = (get_option('s4w_config_read_hidden','0') == '1' ? true : false );
	$s4w_smarty->debugging = (get_option('s4w_debugging','0') == '1' ? true : false );
	$s4w_smarty->force_compile = (get_option('s4w_force_compile','0') == '1' ? true : false );
	$s4w_smarty->php_handling = get_option('s4w_php_handling',0);
	$s4w_smarty->use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? true : false );

	$s4w_smarty->display($tpl);
}

function smarty_test_install($atts, $content=null, $code="")
{
        global $s4w_smarty;

        if (get_option('s4w_smartybc','0') == '1') {
            $s4w_smarty = new Smarty();
        } else {
            $s4w_smarty = new SmartyBC();
        }

	$theme_path = smarty_get_themes_path();

	if (defined('WP_USE_THEMES') && WP_USE_THEMES == true) {
		$s4w_smarty->template_dir = $theme_path . "/templates";
		$s4w_smarty->compile_dir  = $theme_path . "/templates_c";
		$s4w_smarty->config_dir  = $theme_path . "/config";
		$s4w_smarty->cache_dir  = $theme_path . "/cache";
		//$s4w_smarty->plugins_dir[]  = $theme_path . "/plugins";
		//$s4w_smarty->trusted_dir  = $theme_path . "/trusted";
	} else {
	    if (defined('SMARTY_PATH')) {
			$s4w_smarty->template_dir = SMARTY_PATH . "/templates";
			$s4w_smarty->compile_dir  = SMARTY_PATH . "/templates_c";
			$s4w_smarty->config_dir  = SMARTY_PATH . "/config";
			$s4w_smarty->cache_dir  = SMARTY_PATH . "/cache";
			//$s4w_smarty->plugins_dir[]  = SMARTY_PATH . "/plugins";
			//$s4w_smarty->trusted_dir  = SMARTY_PATH . "/trusted";
	    }
	}

        smarty_create_tempdir($s4w_smarty);

        $s4w_smarty->smartybc = (get_option('s4w_smartybc','0') == '1' ?  true : false );
	$s4w_smarty->auto_literal = (get_option('s4w_auto_literal','0') == '1' ?  true : false );
	$s4w_smarty->cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_smarty->cache_modified_check = (get_option('s4w_cache_modified_check','0') == '1' ? true : false );
	$s4w_smarty->config_booleanize = (get_option('s4w_config_bolleanized','0') == '1' ? true : false );
	$s4w_smarty->config_overwrite = (get_option('s4w_config_overwrite','0') == '1' ? true : false );
	$s4w_smarty->config_read_hidden = (get_option('s4w_config_read_hidden','0') == '1' ? true : false );
	$s4w_smarty->debugging = (get_option('s4w_debugging','0') == '1' ? true : false );
	$s4w_smarty->force_compile = (get_option('s4w_force_compile','0') == '1' ? true : false );
	$s4w_smarty->php_handling = get_option('s4w_php_handling',0);
	$s4w_smarty->use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? true : false );

	$s4w_smarty->testInstall();
}

function smarty_load_demo($atts, $content=null, $code="") {
	require dirname(__FILE__).'/demo/index.php';
}

//// Add page to options menu.
function addSmartyManagementPage()
{
    // Add a new submenu under Options:
    add_options_page('Smarty for Wordpress', 'Smarty for Wordpress', 8, 'smartyforwordpress', 'displaySmartyManagementPage');
}

// Display the admin page.
function displaySmartyManagementPage()
{
	if (isset($_POST['save']))
	{
                update_option('s4w_smartybc',(isset($_POST['smarty_bc']) ? '1' : '0'));
		update_option('s4w_auto_literal',(isset($_POST['auto_literal']) ? '1' : '0'));
		update_option('s4w_cache_lifetime',$_POST['cache_lifetime']);
		update_option('s4w_cache_modified_check',(isset($_POST['cache_modified_check']) ? '1' : '0'));
		update_option('s4w_config_bolleanized',(isset($_POST['config_bolleanized']) ? '1' : '0'));
		update_option('s4w_config_overwrite',(isset($_POST['config_overwrite']) ? '1' : '0'));
		update_option('s4w_config_read_hidden',(isset($_POST['config_read_hidden']) ? '1' : '0'));
		update_option('s4w_debugging',(isset($_POST['debugging']) ? '1' : '0'));
		update_option('s4w_force_compile',(isset($_POST['force_compile']) ? '1' : '0'));
		update_option('s4w_php_handling',$_POST['php_handling']);
		update_option('s4w_use_sub_dirs',(isset($_POST['use_sub_dirs']) ? '1' : '0'));
	}

        $s4w_smartybc_checked = (get_option('s4w_smartybc','0') == '1' ? 'checked' : '');
	$s4w_auto_literal_checked = (get_option('s4w_auto_literal','0') == '1' ? 'checked' : '');
	$s4w_cache_lifetime = get_option('s4w_cache_lifetime');
	$s4w_cache_modified_checked = (get_option('s4w_cache_modified_check','0') == '1' ? 'checked' : '');
	$s4w_config_bolleanized_checked = (get_option('s4w_config_bolleanized','0') == '1' ? 'checked' : '');
	$s4w_config_overwrite_checked = (get_option('s4w_config_overwrite','0') == '1' ? 'checked' : '');
	$s4w_config_read_hidden_checked = (get_option('s4w_config_read_hidden','0') == '1' ? 'checked' : '');
	$s4w_debugging_checked = (get_option('s4w_debugging','0') == '1' ? 'checked' : '');
	$s4w_force_compile_checked = (get_option('s4w_force_compile','0') == '1' ? 'checked' : '');

	$s4w_php_handling_selected = get_option('s4w_php_handling',0);
	switch($s4w_php_handling_selected)
	{
		case 1:
			$s4w_php_handling_selected_1 = 'selected';
			break;
		case 2:
			$s4w_php_handling_selected_2 = 'selected';
			break;
		case 3:
			$s4w_php_handling_selected_3 = 'selected';
			break;
		case 4:
			$s4w_php_handling_selected_4 = 'selected';
			break;
	}

	$s4w_use_sub_dirs = (get_option('s4w_use_sub_dirs','0') == '1' ? 'checked' : '');

?>
		<div class="wrap">
			<h2>Smarty for Wordpress</h2>
				<form method="post" action="">
				<fieldset class='options'>
					<legend><h2><u>Settings</u></h2></legend>
					<table>
						<tr>
							<td>Enable SmartyBC</td><td><input type='checkbox' name='smarty_bc' <?php echo $s4w_smartybc_checked; ?> ></td>
						</tr>
						<tr>
							<td>Auto literal</td><td><input type='checkbox' name='auto_literal' <?php echo $s4w_auto_literal_checked; ?> ></td>
						</tr>
						<tr>
							<td>Cache lifetime</td><td><input type='text' name='cache_lifetime' value='<?php echo $s4w_cache_lifetime;?>'></td>
						</tr>
						<tr>
							<td>Cache modified check</td><td><input type='checkbox' name='cache_modified_check' <?php echo $s4w_cache_modified_checked; ?>></td>
						</tr>
						<tr>
							<td>Config booleanized</td><td><input type='checkbox' name='config_bolleanized' <?php echo $s4w_config_bolleanized_checked;?>></td>
						</tr>
						<tr>
							<td>Config overwrite</td><td><input type='checkbox' name='config_overwrite' <?php echo $s4w_config_overwrite_checked;?>></td>
						</tr>
						<tr>
							<td>Config read hidden</td><td><input type='checkbox' name='config_read_hidden' <?php echo $s4w_config_read_hidden_checked;?>></td>
						</tr>
						<tr>
							<td>Debugging</td><td><input type='checkbox' name='debugging' <?php echo $s4w_debugging_checked;?>></td>
						</tr>
						<tr>
							<td>Force compile</td><td><input type='checkbox' name='force_compile' <?php echo $s4w_force_compile_checked;?>></td>
						</tr>
						<tr>
							<td>PHP handling</td><td><select name='php_handling'>
														<option value='1' <?php echo $s4w_php_handling_selected_1;?>>Pass Thru</option>
														<option value='2' <?php echo $s4w_php_handling_selected_2;?>>Quote</option>
														<option value='3' <?php echo $s4w_php_handling_selected_3;?>>Remove</option>
														<option value='4' <?php echo $s4w_php_handling_selected_4;?>>Allow</option>
													 </select></td>
						</tr>
						<tr>
							<td>Use sub-directories</td><td><input type='checkbox' name='use_sub_dirs' <?php echo $s4w_use_sub_dirs;?>></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="save" value="Save"></td>
						</tr>
					</table>
				</fieldset>
				</form>
				<fieldset class='options'>
					<legend><h2><u>Tips &amp; Techniques</u></h2></legend>
                                        <table>
                                            <th>Publication available on Amazon.com - $9.99</th>
                                            <th>Publication available on Barnes & Noble - $5.00</th>
                                            <tr>
                                                <td><a href="http://www.amazon.com/dp/B00K5XTPL2" target="_blank"><img src="http://ecx.images-amazon.com/images/I/41qOnv4Ik5L._BO2,204,203,200_PIsitb-sticker-v3-big,TopRight,0,-55_SX278_SY278_PIkin4,BottomRight,1,22_AA300_SH20_OU01_.jpg"></a></td>
                                                <td><a href="http://www.barnesandnoble.com/w/guide-to-the-smarty-for-wordpress-plugin-patrick-ingle/1123770360?ean=2940158127281" target="_blank"><img src="http://prodimage.images-bn.com/pimages/2940158127281_p0_v1_s192x300.jpg"</a></td>
                                            </tr>
                                        </table>
                                        <p>Preparing your theme to accept Smarty templates:</p>
                                        <ul>
                                        <li>1. Create the four smarty directories (templates,templates_c,config,cache,plugins,trusted) under your theme path</li>
                                        <li>2. Place your Smarty theme files in the templates path</li>
                                        <li>3. Ensure that the templates_c path is writable</li>
                                        <li>4. Create a Wordpress page and enter the following short code: [smarty-display tpl=home.tpl], where home.tpl is your smarty
                                        template located in the templates path</li>
                                        <li>5. If you want to pass a single variable with the template, use [smarty-display tpl=home.tpl name=myVariable value="some value"]</li>
                                        <li>6. If you want to pass multiple variables to the template, use [smarty-array tpl=home.tpl name="my1,my2,my3" value="1,2,'text']</li>
                                        <li>7. View the page and watch the magic happen!<li>
                                        <li><strong>That's It!</strong></li>
                                        </ul>
                                        <p>The <i>home.tpl</i> contains: <code>Hello from Smarty! passing variable myVariable={$myVariable}</code></p>
                                        <p>If you wish to use Smarty in your custom worpress php files, then simply invoke the <b>smarty_get_instance()</b> function
                                        and an instance of the Smarty class will be returned with the directories preset to your current theme.</p>
                                        <p>For example,<br><code>$mySmarty = smarty_get_instance();<br> $mySmarty->assign('name','value');<br>...</code></p>
                                        <p>Providing multiple parameters to your Smarty templates,<br><code>[smarty-array tpl="test.tpl" name="my1,my2,my3" value="1,2,'test'"]</code><br>
                                        <i>Where my1,my2,my3 are the names of your Smarty template variables while value contains the values of these variables to pass to the template, test.tpl.
                                        (my1=1, my2=2, and my3='test')</i></p>
				</fieldset>

				<fieldset class='options'>
					<legend><h2><u>About the Architecture</u></h2></legend>
<p>This plugin is based on Smarty 3.1.30 version. When a stable update to Smarty is released, then this plugin will be updated.</p>
<p>This plugin provides a needed and often requested requirement to assist the migration of Smarty templates to Wordpress-compliant
themes. While the full migration is always preferred, this plugin gives you a fast track to your Smarty migration, as well
as to embed those flagship Smarty templates/plugins within your new Wordpress pages</p>
<p>The first release of this plugin exposes the Smarty display and assign_by_ref functions to
Wordpress as shortcodes.</p>
				</fieldset>
                        
                        <fieldset class="options">
                            <legend><h2><u>Support</u></h2></legend>
                            <p>Support is provided from <a href="https://github.com/patrickingle/smarty-for-wordpress/issues" target="_blank">github.com</a> (opens in new window)</p>
                            <p>You must have a free github.com account to post issue requests.</p>
                        </fieldset>

				<fieldset class='options'>
					<legend><h2><u>Wordpress Development</u></h2></legend>
<p><a href="http://www.phkcorp.com" target="_blank">PHK Corporation</a> is available for custom Wordpress development which includes development of new plugins, modification
of existing plugins, migration of HTML/PSD/Smarty themes to wordpress-compliant <b>seamless</b> themes.</p>
<p>Please email at <a href="mailto:phkcorp2005@gmail.com">phkcorp2005@gmail.com</a></p>
				</fieldset>
		</div>
<?php
}

function smarty_wp_init() {
	if (defined('SMARTY_LOADER') && SMARTY_LOADER != '' && file_exists(SMARTY_LOADER)) {
		require(SMARTY_LOADER);
		exit;
	}
	wp_die('Smarty not configured properly');
}

function smarty_get_themes_path() {
    if (defined('SMARTY_CHILDTHEMES') && SMARTY_CHILDTHEMES == true) {
    	return get_stylesheet_directory();
    }
    return get_theme_root()."/".get_template();
}

add_shortcode('smarty-display', 'smarty_load_template');
add_shortcode('smarty-load', 'smarty_load_template');
add_shortcode('smarty-assign-by-ref', 'smarty_assign_by_reference');
add_shortcode('smarty-array','smarty_array_assign_by_reference');
add_shortcode('smarty-load-multiple','smarty_array_assign_by_reference');
add_shortcode('smarty-test','smarty_test_install');
add_shortcode('smarty-demo','smarty_load_demo');

add_action('admin_menu', 'addSmartyManagementPage');

if (WP_USE_THEMES == false &&
	!is_admin() &&
	!in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )) {
	add_action('init','smarty_wp_init');
}

?>