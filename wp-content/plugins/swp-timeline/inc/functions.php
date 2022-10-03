<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}
/*
 * @package timeline builder
 * since 1.0.0
 * 
*/

add_image_size('swp_timeline_image_468×320', 468, 320, true);

function swp_timeline_get_thumbnail_alt($thumbnail_id)
{
  return get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
}


if (!function_exists('swp_timeline_get_template')) :
  function swp_timeline_get_template($template_name = null)
  {
    $template_path = apply_filters('swp-timeline-elementor/template-path', 'elementor-templates/');
    $template = locate_template($template_path . $template_name);
    if (!$template) {
      $template = SWP_TIMELINE_ELEMENTOR  . '/templates/' . $template_name;
    }
    if (file_exists($template)) {
      return $template;
    } else {
      return false;
    }
  }
endif;


//select category
function swp_timeline_post_category()
{

  $terms = get_terms(array(
    'taxonomy'       => 'category',
    'hide_empty'     => false,
    'posts_per_page' => -1,
  ));

  $category_list = [];
  foreach ($terms as $post) {
    $category_list[$post->term_id] = [$post->name];
  }

  return $category_list;
}


//select tag
function swp_timeline_post_tag()
{

  $terms = get_terms(array(
    'taxonomy'       => 'post_tag',
    'hide_empty'     => false,
    'posts_per_page' => -1,
  ));

  $tag_list = [];
  foreach ($terms as $post) {
    $tag_list[$post->term_id] = [$post->name];
  }

  return $tag_list;
}

/*
* post tag
*/

function swp_timeline_post_date()
{
  $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
  if (get_the_time('U') !== get_the_modified_time('U')) {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
  }

  $time_string = sprintf(
    $time_string,
    esc_attr(get_the_date(DATE_W3C)),
    esc_html(get_the_date()),
    esc_attr(get_the_modified_date(DATE_W3C)),
    esc_html(get_the_modified_date())
  );

  $posted_on = sprintf(
    /* translators: %s: post date. */
    esc_html_x('%s', 'post date', 'swp-timeline'), // phpcs:ignore WordPress.WP.I18n.NoEmptyStrings
    '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
  );

  echo '<span class="date">' . $posted_on . '</span>'; // WPCS: XSS OK.
}
