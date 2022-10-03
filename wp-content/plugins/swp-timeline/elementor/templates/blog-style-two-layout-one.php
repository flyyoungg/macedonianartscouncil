<?php
/*
 * list one template
*/
if ($settings['style'] == 'style-1') :

?>
    <!-- timeline Card 5 -->
    <section class="swp-timeline-area style-5">
        <div class="swp-timeline-line-height"></div>
        <div class="swp-container">
            <?php
            $i  = 1;
            if ($posts_query->have_posts()) :
                while ($posts_query->have_posts()) : $posts_query->the_post();
                    $src       = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', false, '');
            ?>
                    <?php if ($i % 2 == 0) : ?>
                        <div class="swp-single-inner-wrap style-1">
                            <div class="swp-row">
                                <div class="swp-col-sm-6">
                                    <div class="swp-single-inner">
                                        <div class="thumb">
                                            <?php the_post_thumbnail('swp_timeline_image_468×320'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="swp-col-sm-6">
                                    <div class="swp-single-inner style-1 swp-angle-left swp-angle-left-circle-date">
                                        <div class="date-wrap">
                                            <?php the_time('d') ?> <br> <?php the_time('M') ?>
                                        </div>
                                        <div class="content-box">
                                            <div class="date"><?php swp_timeline_post_date(); ?></div>
                                            <h5 class="inner-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                            <p class="content"><?php echo esc_html(wp_trim_words(get_the_content(), $settings['content_excerpt_length'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="swp-single-inner-wrap style-1">
                            <div class="swp-row">
                                <div class="swp-col-sm-6 order-md-12">
                                    <div class="swp-single-inner">
                                        <div class="thumb">
                                            <?php the_post_thumbnail('swp_timeline_image_468×320'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="swp-col-sm-6 order-md-1">
                                    <div class="swp-single-inner style-1 swp-angle-right swp-angle-right-circle-date">
                                        <div class="date-wrap">
                                            <?php the_time('d') ?> <br> <?php the_time('M') ?>
                                        </div>
                                        <div class="content-box">
                                            <div class="date"><?php swp_timeline_post_date(); ?></div>
                                            <h5 class="inner-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                            <p class="content"><?php echo esc_html(wp_trim_words(get_the_content(), $settings['content_excerpt_length'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
            <?php $i++;
                endwhile;
                wp_reset_postdata();
            endif; ?>
        </div>
    </section>
    <!-- timeline Card 5 -->
<?php endif;
