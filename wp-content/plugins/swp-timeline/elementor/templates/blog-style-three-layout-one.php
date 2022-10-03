<?php
/*
 * list one template
*/
if ($settings['style'] == 'style-1') :

?>
    <!-- timeline Card 13 -->
    <section class="swp-timeline-area style-13">
        <div class="swp-container">

            <div class="swp-relative">
                <div class="swp-timeline-line-height-top"></div>
                <div class="swp-timeline-slider-1 owl-nav-style-icon owl-carousel">
                    <?php
                    if ($posts_query->have_posts()) :
                        while ($posts_query->have_posts()) : $posts_query->the_post();
                    ?>
                            <div class="item">
                                <div class="swp-single-inner-wrap style-13">
                                    <div class="swp-single-inner style-2 swp-angle-left-circle-date">
                                        <div class="date-wrap">
                                            <?php the_time('d') ?> <br> <?php the_time('M') ?>
                                        </div>
                                        <div class="thumb">
                                            <?php the_post_thumbnail('swp_timeline_image_468×320'); ?>
                                        </div>
                                        <div class="content-box">
                                            <div class="date"><?php swp_timeline_post_date(); ?></div>
                                            <h5 class="inner-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                            <p class="content"><?php echo esc_html(wp_trim_words(get_the_content(), $settings['content_excerpt_length'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- timeline Card 13 -->
<?php endif;
