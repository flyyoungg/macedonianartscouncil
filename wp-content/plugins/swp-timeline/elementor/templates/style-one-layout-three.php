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
                foreach ($settings['layout_one_timeline'] as $item) : ?>
                    <div class="swp-single-inner-wrap style-1">
                                <div class="swp-single-inner style-1 swp-angle-left swp-angle-left-circle">
                                    <div class="content-box">
                                        <div class="date"><?php echo esc_html($item['date']); ?></div>
                                        <h5 class="inner-title"> <?php if (!empty($item['url']['url'])) : ?>
                                                <a href="<?php echo esc_url($item['url']['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                                            <?php else : ?>
                                                <?php echo esc_html($item['title']); ?>
                                            <?php endif; ?></a>
                                        </h5>
                                        <p class="content"><?php echo esc_html($item['content']); ?></p>
                                    </div>
                                </div>
                    </div>
                <?php
                endforeach; ?>
            </div>
        </div>
    </section>
    <!-- timeline Card 4 -->
<?php endif;