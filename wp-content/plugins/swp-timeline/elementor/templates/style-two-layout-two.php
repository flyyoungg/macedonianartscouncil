<?php

if ($settings['style'] == 'style-2') :

?>
    <!-- timeline Card 6 -->
    <section class="swp-timeline-area style-6">
        <div class="swp-container">
            <div class="swp-relative swp-timeline-line-height-left">
                <div class="swp-timeline-line-height"></div>
                <div class="swp-container">
                    <?php
                    foreach ($settings['layout_one_timeline'] as $item) : ?>
                        <div class="swp-single-inner-wrap style-1">
                                    <div class="swp-single-inner style-2 swp-angle-left-circle-date">
                                        <div class="date-wrap">
                                            <?php echo esc_html($item['short_date']); ?> <br> <?php echo esc_html($item['short_month']); ?>
                                        </div>
                                        <div class="thumb">
                                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr(swp_timeline_get_thumbnail_alt($item['image']['id'])); ?>">
                                        </div>
                                        <div class="content-box">
                                            <div class="date"><?php echo esc_html($item['date']); ?></div>
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
                    <?php
                    endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- timeline Card 6 -->
<?php endif;