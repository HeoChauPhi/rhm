<?php
// View List
add_shortcode( 'view_list', 'rhm_view_list' );
function rhm_view_list($attrs) {
  extract(shortcode_atts (array(
    'name'        => '',
    'post_type'   => '',
    'per_page'    => -1,
    'cat_id'      => '',
    'custom_fields' => '',
    'use_pagination' => '',
    'pagination_type' => '',
    'current_paged' => '',
    'filter_select' => 1,
    'show_popup_file' => '',
  ), $attrs));

  ob_start();
    global $paged;
    global $post;
    if (!isset($paged) || !$paged){
      $paged = $current_paged;
    }

    $filter_array = array();
    $meta_query = array('relation' => 'OR',);

    if($custom_fields){
      $fields = explode("+", $custom_fields);
      foreach ($fields as $item) {
        $item_exp = explode('//value//', $item);
        $item_slug_exp = explode('//slug//', $item_exp[0]);
        $item_slug = $item_slug_exp[1];
        //$item_vals = str_replace(" ", "", $item_exp[1]);
        $item_val = $item_exp[1];

        $filter_array['key'] = $item_slug;
        $filter_array['value'] = $item_exp[1];
        $filter_array['compare'] = '=';
        array_push($meta_query, $filter_array);
      }
    }

    $context = Timber::get_context();
    if($custom_fields) {
      $args = array(
        'post_type'       => $post_type,
        'posts_per_page'  => $per_page,
        'cat'             => $cat_id,
        'post_status'          => 'publish',
        'paged' => $paged,
        //'meta_key'   => 'type',
        //'orderby'    => 'meta_value_num',
        //'order'      => 'ASC',
        'meta_query' => $meta_query,
      );
    } else {
      $args = array(
        'post_type'       => $post_type,
        'posts_per_page'  => $per_page,
        'cat'             => $cat_id,
        'post_status'          => 'publish',
        'paged' => $paged,
      );
    }

    query_posts($args);
    $posts = Timber::get_posts($args);
    $context['posts'] = $posts;

    $args_pagi = array(
      'base' => get_pagenum_link(1) . '%_%',
      'format' => 'page/%#%',
    );

    switch ($name) {
      case 'media-press-releases':
        $context['filter_item'] = Timber::get_posts(array(
          'post_type'       => $post_type,
          'posts_per_page'  => -1,
          'post_status'          => 'publish',
        ));
        $context['filter_select'] = $filter_select;
        break;
    }

    $context['pager_base_url'] = get_pagenum_link(1);
    $context['pagination_type'] = $pagination_type;
    $context['use_pagination'] = $use_pagination;
    $context['show_popup_file'] = $show_popup_file;
    $context['pagination'] = Timber::get_pagination($args_pagi);

    try {
    Timber::render( array( 'view-' . $name . '.twig', 'views.twig'), $context );
    } catch (Exception $e) {
      echo 'Could not find a twig file for Shortcode Name: ' . $name;
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

add_shortcode( 'view_tax_terms', 'rhm_view_tax_terms' );
function rhm_view_tax_terms($attrs) {
  extract(shortcode_atts (array(
    'name'        => '',
  ), $attrs));

  ob_start();

    echo $name;

    try {
    Timber::render('view-' . $name . '.twig', $context );
    } catch (Exception $e) {
      echo 'Could not find a twig file for Shortcode Name: view-' . $name;
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Ovride Counter Per Day plugin, shortcode for counter perday in http://www.tomsdimension.de/wp-plugins/count-per-day#readme-arbitrary
add_shortcode( 'rhm_count_per_day', 'create_rhm_count_per_day' );
function create_rhm_count_per_day() {
  ob_start();
    $context = Timber::get_context();
    try {
    Timber::render('box-count_per_day.twig', $context );
    } catch (Exception $e) {
      echo 'Could not find a twig file for Shortcode Name: box-count_per_day';
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Share this page
add_shortcode( 'rhm_share_this', 'create_rhm_share_this' );
function create_rhm_share_this($attrs) {
  extract(shortcode_atts (array(
  ), $attrs));

  ob_start();
    /*global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));*/
    $current_url = get_permalink();

    $theme_options = get_option('rhm_board_settings');
    $facebook   = $theme_options['rhm_facebook_social'];
    $googleplus = $theme_options['rhm_googleplus_social'];
    $twitter    = $theme_options['rhm_twitter_social'];

    $context = Timber::get_context();

    if( $facebook ) {
      //$context['facebook'] = 'http://www.facebook.com/sharer/sharer.php?u=' . $current_url;
      $context['facebook'] = 'href="https://www.facebook.com/sharer/sharer.php?u=' . $current_url . '&t=TITLE" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600\');return false;" target="_blank" title="Share on Facebook"';
    }

    if( $twitter ) {
      //$context['twitter'] = 'http://www.facebook.com/sharer/sharer.php?u=' . $current_url;
      $context['twitter'] = 'href="https://twitter.com/share?url=' . $current_url . '&via=TWITTER_HANDLE&text=TEXT" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600\');return false;" target="_blank" title="Share on Twitter"';
    }

    if( $googleplus ) {
      //$context['googleplus'] = 'http://www.facebook.com/sharer/sharer.php?u=' . $current_url;
      $context['googleplus'] = 'href="https://plus.google.com/share?url=' . $current_url . '" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480\');return false;" target="_blank" title="Share on Google+"';
    }

    try {
    Timber::render('box-share-this.twig', $context );
    } catch (Exception $e) {
      echo 'Could not find a twig file for Shortcode Name: box-share-this';
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}
