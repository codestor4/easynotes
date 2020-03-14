<?php
/**
 * @package WP-EasyNotes
 */

/*
Plugin Name: WP-EasyNotes
Plugin URI: https://omkarbhagat.com/
Description: A CRUD demo to create, edit and delete notes via the REST API. Use [easynotes] shortcode on any page.
Version: 1.0.0
Author: Omkar Bhagat
Author URI: https://omkarbhagat.com/
License: GPLv2 or later
Text Domain: easynotes
*/

// Define constants.
define( 'EASYNOTES_VERSION', '1.0.0' );
define( 'EASYNOTES_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'EASYNOTES_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'EASYNOTES_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Register the main shortcode
add_shortcode( 'easynotes', 'en_main_shortcode' );
function en_main_shortcode() {
	return en_display_easynotes();
}

// Display the plugin interface
function en_display_easynotes() {
	get_easynotes_template_part('notes');
}

// Register the notes CPT
function en_post_types() {
	register_post_type( 'easynotes', array(
		'show_in_rest' => true,
		'supports' => array( 'title', 'editor' ),
		'public' => false,
		'show_ui' => true,
		'labels' => array(
			'name' => 'Easy Notes',
			'add_new_item' => 'Add New Note',
			'edit_item' => 'Edit Note',
			'all_items' => 'All Notes',
			'singular_name' => 'Note',
		),
		'menu_icon' => 'dashicons-welcome-write-blog',
	));
}
add_action( 'init', 'en_post_types' );

/**
 * Get template part (for templates in loops).
 * Based on WPJM's code.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function get_easynotes_template_part($slug, $template_path = '', $default_path = '') {
    if (!$template_path) {
        $template_path = 'easynotes';
    }
    if (!$default_path) {
        $default_path = EASYNOTES_PLUGIN_DIR . '/templates/';
    }
    $template = '';
    // Get default slug-name.php
    if (!$template && file_exists($default_path . "{$slug}.php")) {
        $template = $default_path . "{$slug}.php";
    }
    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/easynotes/slug.php
    if (!$template) {
        $template = locate_template(array("{$slug}.php", "{$template_path}/{$slug}.php"));
    }
    if ($template) {
        load_template($template, false);
    }
}

// Enqueue Scripts
add_action('wp_enqueue_scripts', 'en_notes_files');
function en_notes_files() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,700');
  wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style( 'easynotes_main_styles', plugin_dir_url( __FILE__ ) . '/style.css');
  wp_enqueue_script( 'easynotes_main_scripts', plugin_dir_url( __FILE__ ) . '/scripts.js', array( 'jquery' ), EASYNOTES_VERSION, true);
  wp_localize_script( 'easynotes_main_scripts', 'easynotesData', array(
  	'root_url' => get_site_url(),
  	'nonce' => wp_create_nonce('wp_rest'),
  ));
}
