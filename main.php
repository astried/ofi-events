<?php
/**
 * Plugin Name: OFI Events Plugin
 * Plugin URI:  https://example.com/simple-hello-plugin
 * Description: A very simple WordPress plugin that demonstrates a shortcode to print "Hello".
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-hello-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly.
// This is a crucial security measure to prevent direct access to your plugin file,
// which could expose sensitive information or allow malicious execution.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Function to handle the 'hello_shortcode' shortcode.
 *
 * @param array $atts Shortcode attributes. Not used in this simple example,
 * but included for completeness.
 * @return string The HTML output for the shortcode.
 */
function shp_hello_shortcode_callback( $atts ) {
    // Sanitize and escape the output.
    // For a simple string like "Hello", esc_html() is appropriate to ensure
    // no malicious scripts or HTML are injected if the string were dynamic.
    return esc_html__( 'Hello from the shortcode!', 'simple-hello-plugin' );
}

// Register the shortcode.
// The first parameter is the shortcode tag (e.g., [hello_shortcode]).
// The second parameter is the callback function that generates the output.
add_shortcode( 'hello_shortcode', 'shp_hello_shortcode_callback' );

/*
 * Security Considerations Implemented:
 *
 * 1.  Prevent Direct Access: The `if ( ! defined( 'ABSPATH' ) ) { exit; }`
 * line at the top prevents direct execution of the plugin file.
 *
 * 2.  Output Escaping/Sanitization: `esc_html__()` is used to ensure that
 * the output is safe for HTML display, preventing cross-site scripting (XSS)
 * vulnerabilities if the output were dynamic. The `__` function is for
 * internationalization (i18n), which is good practice.
 *
 * 3.  Function/Variable Prefixing: All custom functions and variables
 * (e.g., `shp_hello_shortcode_callback`) are prefixed with `shp_`
 * (Simple Hello Plugin) to avoid naming conflicts with other plugins or themes.
 *
 * 4.  No Unnecessary Capabilities/Permissions: This plugin doesn't perform
 * any actions that require elevated user capabilities, reducing the attack surface.
 *
 * 5.  No Database Interactions: This simple plugin doesn't interact with the
 * database, thus avoiding potential SQL injection vulnerabilities.
 * If it did, prepared statements would be crucial.
 *
 * 6.  No Nonces Needed: For a simple shortcode that only outputs static text,
 * nonces (security tokens) are not required as there are no form submissions
 * or state-changing actions.
 */
if( ! defined( 'OFI_EVENT_DIR_URL' ) )
	define( 'OFI_EVENT_DIR_URL', plugin_dir_url( __FILE__ ) );

if( ! defined( 'OFI_EVENT_PATH_URL' ) )
	define( 'OFI_EVENT_PATH_URL', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', array ( 'OFI_EVENT', 'init' ), 9 );
register_activation_hook( __FILE__, array ( 'OFI_EVENT', 'active_trigger' ) );

if ( !class_exists( 'OFI_EVENT' ) )
{
class OFI_EVENT
{
	static function init()
	{
        include_once "admin/admin-main.php";
        include_once "admin/admin-new.php";
        include_once "admin/admin-ajax.php";

        include_once "shortcodes/shortcode-display-frontend.php";
        include_once "shortcodes/shortcode-details-frontend.php";

        add_action( 'admin_enqueue_scripts', array( __CLASS__ , 'load_admin_scripts') );        
    }

    static function load_admin_scripts($hook)
    {
        if( strpos( $hook, 'ofi_event' ) !== false )
		{
            $ajax_url =  admin_url( 'admin-ajax.php' );

            wp_enqueue_style( 'ofi-event-ts', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );

            //Style for datepicker
            wp_enqueue_style( 'jquery-ui-style', "https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" );
            
            wp_enqueue_script('ofi-event-admin-js',  plugins_url( "/assets/script-admin.js", __FILE__ ),
                                array('jquery', 'jquery-ui-datepicker'), null, true );

            wp_localize_script('ofi-event-admin-js', 'ofievent_Ajax',
                                array( 'url' => $ajax_url,
                                    'nonce' => wp_create_nonce('ofievent_admin_nonce')
                                    )
                                );

            //wp_enqueue_style('ofi-event',  plugins_url( "/assets/bootstrap-5.0.2/css/bootstrap.css", __FILE__ ) );
        
        }
    }

	static function active_trigger()
	{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$charset_collate 	= $wpdb->get_charset_collate();

        $sql_tbl = "";
        $sql_tbl .= "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ofi_events`( ";
        $sql_tbl .= " `id` int(11) NOT NULL AUTO_INCREMENT, ";
        $sql_tbl .= " `title` varchar(100) NOT NULL, ";
        $sql_tbl .= " `description` text DEFAULT NULL, ";
        $sql_tbl .= " `location` varchar(100) DEFAULT NULL, ";
        $sql_tbl .= " `latitude` text DEFAULT NULL, ";        
        $sql_tbl .= " `longitude` text DEFAULT NULL, ";        
        $sql_tbl .= " `date` char(15) DEFAULT NULL, ";
        $sql_tbl .= " `time` varchar(5) DEFAULT NULL, ";
        $sql_tbl .= " `timezone` varchar(50) DEFAULT NULL, ";
        $sql_tbl .= " `author` int DEFAULT NULL, ";
        $sql_tbl .= " PRIMARY KEY (`id`) ";
        $sql_tbl .= " )$charset_collate;";

		dbDelta( $sql_tbl );        
    }

}
}