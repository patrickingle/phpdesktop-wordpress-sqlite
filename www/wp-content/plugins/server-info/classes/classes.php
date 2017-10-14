<?php

defined('ABSPATH') or die('No script kiddies please!');

class server_info
{

    public static function infohouse_css_styles()
    {

        wp_enqueue_style( 'style-name', get_bloginfo('url') . '/wp-content/plugins/server-info/assets/css/style.css' );

    }

    public static function servinfo_admin_actions()
    {

        add_options_page(

            'Server Information',

            'Server Info',

            'manage_options',

            'server_info_display',

            array('server_info', 'display_infohouse_page')

        );

    }

    public static function display_infohouse_page()
    {
        global $wpdb;
        ?>

        <div class="wrap server-info">

            <h2 class="infohouse_heading">Server Information</h2>

            <hr/>

            <p>Server Info plugin shows the general information about the hosting server your WordPress site is
                currently hosted on. You can find this information helpful for many purposes like performance improvements and so on.</p>

            <br/>

            <div class="infohouse_settings_page">

                <div class="table-responsive">

                    <table class="table infohouse_table">

                        <tr>

                            <th colspan="2"><h3>Hosting Server Info</h3></th>

                        </tr>

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

                            <td>
                                <p><?php

                                    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

                                    echo mysqli_get_server_info($connection); ?>

                                </p>
                            </td>

                        </tr>

                        <tr>

                            <td><h5>CGI Version:</h5></td>

                            <td><p><?php echo $_SERVER['GATEWAY_INTERFACE']; ?></p></td>

                        </tr>

                        <?php $uptime = exec("uptime", $system);

                        if (!empty($uptime)) {

                        ?>

                        <tr class="gray">

                            <td><h5>System Uptime:</h5></td>

                            <td><p><?php echo $uptime; ?></p></td>

                        </tr>

                        <?php } ?>

                    </table>

                    <table class="table infohouse_table">

                        <tr>

                            <th colspan="2"><h3>WordPress Info</h3></th>

                        </tr>

                        <tr>

                            <td><h5>Active Theme:</h5></td>

                            <td><?php

                                $active_theme = wp_get_theme();

                                echo esc_html($active_theme->get('Name'));

                                ?></td>

                        </tr>

                        <tr class="gray">

                            <td><h5>Active Plugins:</h5></td>

                            <td><?php

                                $active_plugins = get_option('active_plugins');

                                echo '<ul>';

                                foreach($active_plugins as $key => $value) {

                                    $string = explode('/',$value);

                                    echo '<li>'.$string[0] .'</li>';

                                }

                                echo '</ul>';

                                ?></td>

                        </tr>

                        <tr>

                            <td><h5>Database Hostname:</h5></td>

                            <td><?php echo DB_HOST; ?></td>

                        </tr>

                        <tr class="gray">

                            <td><h5>Database Username:</h5></td>

                            <td><?php echo DB_USER; ?></td>

                        </tr>

                        <tr>

                            <td><h5>Database Name:</h5></td>

                            <td><?php echo DB_NAME; ?></td>

                        </tr>

                        <tr class="gray">

                            <td><h5>Database Charset:</h5></td>

                            <td><?php echo DB_CHARSET; ?></td>

                        </tr>

                        <?php

                        $db_collate = DB_COLLATE;

                        if (!empty($db_collate)) {

                            ?>

                            <tr>

                                <td><h5>Database Collation:</h5></td>

                                <td><?php echo DB_COLLATE; ?></td>

                            </tr>

                        <?php } ?>

                        <?php

                        $wp_debug = WP_DEBUG;

                        if (!empty($wp_debug)) {

                            ?>

                            <tr>

                                <td><h5>WordPress Debugging:</h5></td>

                                <td><?php

                                    if ($wp_debug = 1) {

                                        echo "Enabled";

                                    } else {

                                        echo "Disabled";

                                    }

                                    ?></td>

                            </tr>

                        <?php } ?>

                        <tr class="gray">

                            <td><h5>WordPress Memory Limit:</h5></td>

                            <td><p><?php echo WP_MEMORY_LIMIT; ?></p></td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <?php
    }

    public static function server_info_add_dashboard_widgets()
    {

        wp_add_dashboard_widget(
            'serverinfo_dashboard_widget',
            'Server Info',
            array('server_info', 'server_info_dashboard_widget')
        );
    }

    public static function server_info_dashboard_widget()
    { ?>

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

                <td colspan="2" class="view-more-info"><a class="button button-primary"
                                                          href="<?php echo admin_url('options-general.php?page=server_info_display'); ?>"
                                                          ?>View More Information</a></td>

            </tr>

        </table> <?php

    }

}

?>