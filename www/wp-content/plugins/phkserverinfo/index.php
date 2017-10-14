<?php
/*
 * Plugin Name: PHK Corporation Server Info & Status
 * Description: Shows the current drive space used and total space available, and the size of each database tables referenced using the WPDB object and what your database size classisfication (small, large, or big)
 * Version: 1.0
 * Plugin URI: https://#
 * Author: PHK Corporation
 * Author URI: https://phkcorp.com
 */

/**
 * Disk Status Class
 *
 * http://pmav.eu/stuff/php-disk-status/
 *
 * v1.0 - 17/Oct/2008
 * v1.1 - 22/Ago/2009 (Exceptions added.)
 */
class DiskStatus {

    const RAW_OUTPUT = true;

    private $diskPath;

    function __construct($diskPath) {
        $this->diskPath = $diskPath;
    }

    public function totalSpace($rawOutput = false) {
        $diskTotalSpace = @disk_total_space($this->diskPath);

        if ($diskTotalSpace === FALSE) {
            throw new Exception('totalSpace(): Invalid disk path.');
        }

        return $rawOutput ? $diskTotalSpace : $this->addUnits($diskTotalSpace);
    }

    public function freeSpace($rawOutput = false) {
        $diskFreeSpace = @disk_free_space($this->diskPath);

        if ($diskFreeSpace === FALSE) {
            throw new Exception('freeSpace(): Invalid disk path.');
        }

        return $rawOutput ? $diskFreeSpace : $this->addUnits($diskFreeSpace);
    }

    public function usedSpace($precision = 1) {
        try {
            return round((100 - ($this->freeSpace(self::RAW_OUTPUT) / $this->totalSpace(self::RAW_OUTPUT)) * 100), $precision);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getDiskPath() {
        return $this->diskPath;
    }

    private function addUnits($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

}

function phkservernfo_dashboard_widgets() {
    global $wp_meta_boxes;

    wp_add_dashboard_widget('phkservernfo_help_widget', 'Disk Usage', 'phkservernfo_dashboard_help');
    wp_add_dashboard_widget('phkservernfo_mysqldb_widget', 'MySQL Database Usage', 'phkservernfo_dashboard_mysqldbusage');
    wp_add_dashboard_widget('phkservernfo_mysimplespace_widget', 'My Simple Space (by PHK Corporation)', 'phkservernfo_dashboard_mysimplespace');
}

function phkservernfo_dashboard_help() {
    try {
        $diskStatus = new DiskStatus('/');

        $freeSpace = $diskStatus->freeSpace();
        $totalSpace = $diskStatus->totalSpace();
        $barWidth = ($diskStatus->usedSpace() / 100) * 400;
    } catch (Exception $e) {
        echo 'Error (' . $e->getMessage() . ')';
        exit();
    }
    ?>
    <style>
        .disk {
            border: 4px solid black;
            width: 400px;
            padding: 2px;
        }

        .used {
            display: block;
            background: red;
            text-align: right;
            padding: 0 0 0 0;
        }
    </style>
    <div class="disk">
        <div class="used" style="width: <?php echo $barWidth; ?>px"><?php echo $diskStatus->usedSpace(); ?>%&nbsp;</div>
    </div>
    Free: <?php echo $freeSpace; ?> (of <?php echo $totalSpace; ?>)
    <?php
}

/**
 * Data Classifications:
 * Small = < 10GB (10000MB)
 * Large = 10GB < 1TB
 * Big = > 1TB (1000000MB)
 * From: https://www.quora.com/How-much-data-is-Big-Data-Is-there-classification-for-various-levels-of-Big-Data-by-amount-of-data-processed-or-other-constraints-like-for-example-throughput-What%E2%80%99s-the-minimum-data-size-which-still-qualifies-as-a-Big-Data%E2%80%9D
 *
 * @global type $wpdb
 */
// SELECT table_schema "dbname", Round(Sum(data_length + index_length) / 1024 / 1024, 1) "dbsize" FROM information_schema.tables GROUP BY table_schema
function phkservernfo_dashboard_mysqldbusage() {
    global $wpdb;

	$dbfile = DB_DIR . DB_FILE;

	$dbname = DB_FILE;
	$dbsize = phkservernfo_formatSizeUnits(filesize($dbfile));

    $total = 0;

    //$results = $wpdb->get_results('SELECT table_schema "dbname", Round(Sum(data_length + index_length) / 1024 / 1024, 1) "dbsize" FROM information_schema.tables GROUP BY table_schema', true);
    //echo '<pre>'; print_r($results); echo '</pre>';
    echo '<table>';
    //foreach ($results as $db) {
        $total += $dbsize;
        echo '<tr><td>' . $dbname . '</td><td>' . $dbsize . ' </td></tr>';
    //}
    echo '<tr><td colspan="2">';
    if ($total < 10000) {
        $fromLargeData = round(($total / 10000) * 100, 0);
        echo 'Total Database size: ' . $total . ' an is classified as <b>Small Data</b>.<br/>You are ' . $fromLargeData . '% towards becoming <b>Medium Data</b>';
    } elseif ($total > 10000 && $total < 1000000) {
        $fromBigData = round(($total / 1000000) * 100, 0);
        echo 'Total Database size: ' . $total . ' an is classified as <b>Medium Data</b><br/>You are ' . $fromBigData . '% towards becoming <b>Big Data</b>';
    } elseif ($total > 1000000) {
        echo 'Total Database size: ' . $total . ' an is classified as <b>Big Data</b>';
    }
    echo '</td></tr>';
    echo '<tr><td colspan="2"><img src="' . plugins_url('database-size-class.png', __FILE__) . '" width="100%"></td></tr>';
    if ($total > 10000) {
        echo '<tr><td colspan="2"><a href="https://www.qubole.com/resources/big-data-vendors-comparison/" target="_blank">Comparison of Big Data Service Providers</a></td></tr>';
    }
    echo '</table>';
}

function phkservernfo_formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

function phkservernfo_mss_dir_size($path) {
    $diskStatus = new DiskStatus($path);
    return $diskStatus->totalSpace();
}

function phkservernfo_dashboard_mysimplespace() {
    global $wpdb;

    $phpversion = PHP_VERSION;

    $memory_limit = ini_get('memory_limit');
    $memory_usage = function_exists('memory_get_usage') ? round(memory_get_usage(), 2) : 0;

    // Get Memory
    if (!empty($memory_usage) && !empty($memory_limit)) {
        $memory_percent = round($memory_usage / $memory_limit * 100, 0);
    }

    // Upload Directory
    $uploads = wp_upload_dir();

    // Setup Home Path for Later Usage
    if (get_home_path() === "/")
        $homepath = ABSPATH;
    else
        $homepath = get_home_path();

    $homepathStatus = phkservernfo_mss_dir_size($homepath);

    $subfolder = strrpos( get_site_url(), '/', 8 ); // Starts after http:// or https:// to find the last slash

    // Determines if site is using a subfolder, such as /wp
    if ( isset( $subfolder ) && $subfolder != "" ) {
            $remove = substr( get_site_url(), strrpos( get_site_url(), '/' ), strlen( get_site_url() ) );
            $home = str_replace ( $remove, '', $homepath ); // Strips out subfolder to avoid duplicate folder in path
    } else {
            $home = $homepath;
    }

    // Get Database Size
	$dbfile = DB_DIR . DB_FILE;

	$dbname = DB_FILE;
	$dbsize = filesize($dbfile);

    // PHP version, memory, database size and entire site usage (may include not WP items)
    $topitems = array(
        'PHP Version' => $phpversion . ' ' . ( PHP_INT_SIZE * 8 ) . ' ' . __('Bit OS', 'my-simple-space'),
        'Memory' => __('Total: ', 'my-simple-space') . $memory_limit . ' ' . __('Used: ', 'my-simple-space') . size_format($memory_usage, 2),
        'Database' => size_format($dbsize, 2)
    );

    foreach ($topitems as $name => $value) {
        echo '<p class="halfspace"><span class="spacedark">' . $name . '</span>: ' . $value . '</p>';
    }
    /*
    echo '<div class="halfspace">
<p><span class="spacedark">' . __('Contents', 'my-simple-space') . '</span></p>';

    $content = parse_url(content_url());
    $content = $home . ltrim($content['path'], "/");
    $plugins = str_replace(plugin_basename(__FILE__), '', __FILE__);

    // WP Content and selected subfolders
    $contents = array(
        "wp-content" => $content,
        "plugins" => $plugins,
        "themes" => get_theme_root(),
        "uploads" => $uploads['basedir'],
    );

    foreach ($contents as $name => $value) {

        $name = __($name, 'my-simple-space'); // Make translatable
        if (false === ( get_transient($value) ))
            echo '<span class="spacedark">' . $name . '</span>: ' . size_format(phkservernfo_mss_dir_size($value), 2) . '<br />';
        else
            echo '<span class="spacedark">' . $name . '</span>: ' . size_format(get_transient($value), 2) . '<br />';
    }

    echo '</div>';

    // WordPress Admin and Includes folders

    $wpadmin = parse_url(get_admin_url());
    $wpadmin = $home . ltrim($wpadmin['path'], '/');
    $wpincludes = parse_url(includes_url());
    $wpincludes = $home . ltrim($wpincludes['path'], '/');

    echo '<div class="halfspace">
<p><b>Other WP Folders</b></p>';

    // wp-admin and wp-includes folders
    $folders = array(
        "wp-admin" => $wpadmin,
        "wp-includes" => $wpincludes
    );

    foreach ($folders as $name => $value) {

        $name = __($name, 'my-simple-space'); // Make translatable

        if (false === ( get_transient($value) ))
            echo '<span class="spacedark">' . $name . '</span>: ' . size_format(phkservernfo_mss_dir_size($value), 2) . '<br />';
        else
            echo '<span class="spacedark">' . $name . '</span>: ' . size_format(get_transient($value), 2) . '<br />';
    }

    echo '</div>';
    */
}

if (is_multisite()) {
    add_action('wp_network_dashboard_setup', 'phkservernfo_dashboard_widgets');
} else {
    add_action('wp_dashboard_setup', 'phkservernfo_dashboard_widgets');
}
?>