<?php
/*
 * list one template
*/
if ($settings['style'] == 'style-1') :

?>
    <!-- timeline Card 1 -->
    <section class="swp-timeline-area style-1">
        <div class="swp-timeline-line-height"></div>

        <div class="swp-container">
            <?php $i = 1;
            foreach ($settings['layout_one_timeline'] as $item) : ?>
                <?php if ($i % 2 == 0) : ?>
                    <div class="swp-single-inner-wrap style-1">
                        <div class="swp-row">
                            <div class="swp-col-sm-6 order-md-12">
                                <div class="swp-single-inner">
                                    <div class="thumb">
                                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr(swp_timeline_get_thumbnail_alt($item['image']['id'])); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="swp-col-sm-6 order-md-1">
                                <div class="swp-single-inner style-1 swp-angle-right">
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
                        </div>
                    </div>
                <?php else : ?>
                    <div class="swp-single-inner-wrap style-1">
                        <div class="swp-row">
                            <div class="swp-col-sm-6">
                                <div class="swp-single-inner">
                                    <div class="thumb">
                                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr(swp_timeline_get_thumbnail_alt($item['image']['id'])); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="swp-col-sm-6">
                                <div class="swp-single-inner style-1 swp-angle-left">
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
                        </div>
                    </div>
                <?php endif; ?>
            <?php $i++;
            endforeach; ?>
        </div>
    </section>
    <!-- timeline Card 1 -->
<?php endif;
