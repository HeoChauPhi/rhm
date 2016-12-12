<?php
/*
 *  Author: Framework | @Framework
 *  URL: wordfly.com | @wordfly
 *  Custom functions, support, custom post types and more.
 */

// Theme setting
require_once('init/theme-init.php');
require_once('init/theme-shortcode.php');
require_once('init/options/option.php');

/* Custom for theme */
//echo get_stylesheet_directory_uri();

if(!is_admin()) {
  // Add scripts
  function rhm_libs_scripts() {
    wp_register_script('prettify', get_stylesheet_directory_uri() . '/dist/js/libs/prettify.js', array('jquery'), FALSE, '1.0.0', TRUE);
    wp_enqueue_script('prettify');

    wp_register_script('googlemap', get_stylesheet_directory_uri() . '/dist/js/libs/gmaps.js', array('jquery'), FALSE, '0.4.24', TRUE);
    wp_enqueue_script('googlemap');

    wp_register_script('lib-slick', get_stylesheet_directory_uri() . '/dist/js/libs/slick.min.js', array('jquery'), FALSE, '1.6.0', TRUE);
    wp_enqueue_script('lib-slick');

    wp_register_script('lib-matchHeight', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.matchHeight-min.js', array('jquery'), FALSE, '0.7.0', TRUE);
    wp_enqueue_script('lib-matchHeight');

    wp_register_script('lib-fancybox', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.fancybox.pack.js', array('jquery'),  FALSE, '2.1.5', TRUE);
    wp_enqueue_script('lib-fancybox');

    wp_register_script('script', get_stylesheet_directory_uri() . '/dist/js/script.js', FALSE, '1.0.0', TRUE);
    wp_localize_script( 'script', 'paginationAjax', array( 'ajaxurl' => admin_url('admin-ajax.php' )));
    wp_enqueue_script('script');
  }
  add_action('wp_print_scripts', 'rhm_libs_scripts');

  // Add stylesheet
  function rhm_styles() {
    $styles = get_stylesheet_directory_uri() . '/dist/css/styles.css';
    wp_register_style('slick', get_stylesheet_directory_uri() . '/dist/css/slick.css', array(), '1.6.0', 'all');
    wp_enqueue_style('slick');
    wp_register_style('slick-theme', get_stylesheet_directory_uri() . '/dist/css/slick-theme.css', array(), '1.6.0', 'all');
    wp_enqueue_style('slick-theme');

    wp_register_style('theme-style', $styles, array(), '1.0', 'all');
    wp_enqueue_style('theme-style');
  }
  add_action('wp_enqueue_scripts', 'rhm_styles');
}

// Add admin script
function rhm_admin_scripts() {
  wp_register_script('lib-moment', get_stylesheet_directory_uri() . '/dist/js/admin-libs/moment.js', array('jquery'), '2.13.0');
  wp_enqueue_script('lib-moment');

  wp_register_script('lib-datetimepicker', get_stylesheet_directory_uri() . '/dist/js/admin-libs/bootstrap-datetimepicker.min.js', array('jquery'), '4.17.37');
  wp_enqueue_script('lib-datetimepicker');

  wp_register_script('admin-script', get_stylesheet_directory_uri() . '/dist/js/admin-script.js', array('jquery'), '1.0.0');
  wp_enqueue_script('admin-script');
}
add_action('admin_init', 'rhm_admin_scripts');

function rhm_script_admin_head() {
  echo '<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBe7jwJlR9vwG5KN56gaKf6WCjCJaOPI1Y"></script>';
}
add_action( 'admin_head', 'rhm_script_admin_head' );

// Add admin script
function rhm_admin_styles() {
  wp_register_style('admin-style', get_stylesheet_directory_uri() . '/dist/css/admin.css', array(), '1.0', 'all');
  wp_enqueue_style('admin-style');
}
add_action('admin_init', 'rhm_admin_styles');

/* Add custom post type */
function rhm_create_custom_post_types() {
  // Member
  register_post_type( 'member', array(
    'labels' => array(
      'name' => __( 'Members', 'rhm_theme' ),
      'singular_name' => __( 'Member', 'rhm_theme' )
    ),
    'public' => true,
    'has_archive' => false,
    'menu_position' => 20,
    'rewrite' => array('slug' => 'members'),
    'supports' => array( 'title' ),
  ));

  // Specialized
  register_post_type( 'specialized', array(
    'labels' => array(
      'name' => __( 'Specializeds', 'rhm_theme' ),
      'singular_name' => __( 'Specialized', 'rhm_theme' )
    ),
    'public' => true,
    'has_archive' => false,
    'menu_position' => 22,
    'rewrite' => array('slug' => 'specializeds'),
    'supports' => array( 'title', 'editor'),
  ));

  // Schedule
  register_post_type( 'schedule', array(
    'labels' => array(
      'name' => __( 'Schedules', 'rhm_theme' ),
      'singular_name' => __( 'Schedule', 'rhm_theme' )
    ),
    'public' => true,
    'has_archive' => false,
    'menu_position' => 23,
    'rewrite' => array('slug' => 'schedules'),
    'supports' => array( 'title'),
  ));
}
add_action( 'init', 'rhm_create_custom_post_types' );

/* Add custom Taxonomy */
function rhm_create_custom_taxonomy() {
  $labels_specialized = array(
    'name' => __('Specialized Areas', 'rhm_theme'),
    'singular' => __('Specialized Areas', 'rhm_theme'),
    'menu_name' => __('Specialized Areas', 'rhm_theme')
  );
  $args_specialized = array(
    'labels'                     => $labels_specialized,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy('specialized_areas', array('specialized'), $args_specialized);
}
add_action( 'init', 'rhm_create_custom_taxonomy', 0 );
