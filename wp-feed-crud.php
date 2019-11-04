<?php 
/*
 Plugin Name: Wp feed CRUD
 Version: 0.1
 Plugin URI: 
 Author: 
 Description: Express your blog through a unique representation
 of your post archives.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

global $wp_version;
$exit_msg = 'Require WordPress 2.6 or newer. <br/> Upgrading_WordPress">Please update!';
if (version_compare($wp_version, "2.6", "<"))
{
    exit($exit_msg);
}

require_once(  __DIR__  . '/classes/feed.class.php' );

// Avoid name collisions.
if ( !class_exists('WpFeedCRUD') ) {

    class WpFeedCRUD
    {
        // this variable will hold url to the plugin
        var $plugin_url;
        // Initialize the plugin
        public function __construct()
        {
            // Add Javascript and CSS for admin screens
            add_action('admin_enqueue_scripts', array($this,'enqueueAdmin'));

            // Add Javascript and CSS for front-end display
            //add_action('wp_enqueue_scripts', array($this,'enqueue'));

            add_action('admin_menu', array($this, 'addMenu') );

            //register ajax call
            add_action( 'wp_ajax_wpfcrud_ajax_call', array($this, 'importData') );

        }

            /*
        * Actions perform on activation of plugin
        */
        public function wpfcrud_install() {
        }

        /*
        * Actions perform on de-activation of plugin
        */
        public function wpfcrud_uninstall() {
        }

        public function addMenu() {
            add_menu_page('Wordpress Feed CRUD', //page title
                    'WP Feed CRUD', //menu title
                    'manage_options', //capabilities
                    'wp-feed-crud', //menu slug
                    array($this, 'templateRender') //function
            );
            add_submenu_page(
					'wp-feed-crud',
					'Update from file1', //page title
					'Update from file', // wp submenu title
					'manage_options', 
					'update-from-file',
					array($this,'updateFromFileTemplate')
            );
        }

        public function updateFromFileTemplate() {

			$feed = new Feed();
        	include_once( __DIR__ . '/templates/updatefromfile.php');
        }
        public function templateRender() {
            include_once( __DIR__ . '/templates/settings.php');
        }

        public function enqueueAdmin(){
            wp_enqueue_script( 'wpfcrud_custom_script', plugins_url( 'assets/js/wpfcrud_admin_custom.js', __FILE__ ), array('jquery'), null );

            // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script( 'wpfcrud_custom_script', 'ajax_object',
                    array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }

        public function importData() {
            $feed = new Feed();
            $feed->getFeed('http://casino.everymatrix.com/jsonfeeds/mix/maxbet_com?types=game');
            wp_die();
        }
    }

}


// create new instance of the class
$wpfeedcrud = new WpFeedCRUD();

if (isset($wpfeedcrud))
{
     // register the activation function by passing the reference
     register_activation_hook( __FILE__, array($wpfeedcrud,
     'install') );
}