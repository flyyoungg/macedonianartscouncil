<?php
/*
 * list one template
*/
if ($settings['style'] == 'style-3') :

?>
    <!-- timeline Card 4 -->
    <section class="swp-timeline-area style-4">
        <div class="swp-container swp-relative">
            <div class="swp-relative swp-timeline-line-height-left">
                <div class="swp-timeline-line-height"></div>
                <?php
                $i  = 1;
                if ($posts_query->have_posts()) :
                    while ($posts_query->have_posts()) : $posts_query->the_post();
                ?>
                        <div class="swp-single-inner-wrap style-1">
                                    <div class="swp-single-inner style-1 swp-angle-left swp-angle-left-circle">
                                        <div class="content-box">
                                            <div class="date"><?php swp_timeline_post_date(); ?></div>
                                            <h5 class="inner-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                            <p class="content"><?php echo esc_html(wp_trim_words(get_the_content(), $settings['content_excerpt_length'])); ?></p>
                                        </div>
                                    </div>
                        </div>
                <?php $i++;
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </section>
    <!-- timeline Card 4 -->
<?php endif;