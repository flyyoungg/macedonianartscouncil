<?php
/**
 * The style "default" of the Portfolio
 *
 * @package ThemeREX Addons
 * @since v1.5
 */

$args = get_query_var('trx_addons_args_sc_portfolio');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$link = !empty($meta['link']) ? $meta['link'] : get_permalink();

if (!empty($args['slider'])) {
	?><div class="slider-slide swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'], !empty($args['columns_tablet']) ? $args['columns_tablet'] : '', !empty($args['columns_mobile']) ? $args['columns_mobile'] : '')); ?>"><?php
}
?>
<div data-post-id="<?php the_ID(); ?>" class="sc_portfolio_item<?php
	if (isset($args['hide_excerpt']) && (int)$args['hide_excerpt'] > 0) echo ' without_content';
?>"<?php trx_addons_add_blog_animation('portfolio', $args); ?>>
	<?php
	// Featured image or icon
	if ( has_post_thumbnail()) {
		trx_addons_get_template_part(
			'templates/tpl.featured.php',
			'trx_addons_args_featured',
			apply_filters(
				'trx_addons_filter_args_featured',
				array(
					'class' => 'sc_portfolio_item_thumb',
					'hover' => 'zoomin',
					'link' => $link,
					'thumb_size' => apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size($args['columns'] > 2 ? 'medium' : 'big'), 'portfolio-default')
				),
				'portfolio-default'
			)
		);
	}
	?>	
	<div class="sc_portfolio_item_info">
		<div class="sc_portfolio_item_header">
			<h4 class="sc_portfolio_item_title entry-title"><a href="<?php echo esc_url($link); ?>"<?php if (!empty($meta['link'])) echo ' target="_blank"'; ?>><?php the_title(); ?></a></h4>
			<div class="sc_portfolio_item_subtitle"><?php trx_addons_show_layout(trx_addons_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY));?></div>
		</div><?php
		if (!isset($args['hide_excerpt']) || (int)$args['hide_excerpt']==0) {
			?><div class="sc_portfolio_item_content"><?php the_excerpt(); ?></div><?php
			if (!empty($args['more_text'])) {
				?><div class="sc_portfolio_item_button sc_item_button"><a href="<?php echo esc_url($link); ?>"<?php if (!empty($meta['link'])) echo ' target="_blank"'; ?> class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_link_classes', 'sc_button sc_button_simple', 'sc_portfolio', $args)); ?>"><?php echo esc_html($args['more_text']); ?></a></div><?php
			}
		}
	?></div>
</div><?php
if (!empty($args['slider']) || $args['columns'] > 1) {
	?></div><?php
}
