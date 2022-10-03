<?php
/*
 * list one template
*/
if ($settings['style'] == 'style-3') :

?>

    <!-- timeline Card 15 -->
    <section class="swp-timeline-area style-15">
        <div class="swp-container">

            <div class="swp-relative">
                <div class="swp-timeline-line-height-top"></div>
                <div class="swp-timeline-slider-1 owl-nav-style-icon owl-carousel">
                    <?php
                    foreach ($settings['layout_two_timeline'] as $item) : ?>
                        <div class="item">
                            <div class="swp-single-inner-wrap style-15">
                                <div class="swp-single-inner style-2 swp-angle-left-circle swp-angle-left-circle-date">
                                    <?php if (!empty($item['year'])) : ?>
                                        <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                                    <?php endif; ?>
                                    <div class="thumb">
                                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr(swp_timeline_get_thumbnail_alt($item['image']['id'])); ?>">
                                    </div>
                                    <div class="content-box">
                                        <?php if (!empty($item['date'])) : ?>
                                            <div class="date"><?php echo esc_html($item['date']); ?></div>
                                        <?php endif; ?>
                                        <h5 class="inner-title">
                                            <?php if (!empty($item['url']['url'])) : ?>
                                                <a href="<?php echo esc_url($item['url']['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                                            <?php else : ?>
                                                <?php echo esc_html($item['title']); ?>
                                            <?php endif; ?>
                                        </h5>
                                        <p class="content"><?php echo esc_html($item['content']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- timeline Card 15 -->

<?php endif;
