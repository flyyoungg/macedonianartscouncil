<?php

if ($settings['style'] == 'style-3') :

?>
    <!-- timeline Card 12 -->
    <section class="swp-timeline-area style-12">
        <div class="swp-timeline-line-height"></div>
        <div class="swp-container">
            <?php $i = 1;
            foreach ($settings['layout_three_timeline'] as $item) : ?>
                <?php if ($i % 2 == 0) : ?>
                    <div class="swp-single-inner-wrap style-12 style-50-left">
                        <div class="swp-single-inner style-1 swp-angle-left swp-angle-left-circle-date">
                            <?php if (!empty($item['year'])) : ?>
                                <div class="year-wrap-2"><span><?php echo esc_html($item['year']); ?></span></div>
                            <?php endif; ?>
                            <?php if (!empty($item['year'])) : ?>
                                <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                            <?php endif; ?>
                            <div class="date-wrap">
                                <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => ' '], 'i'); ?>
                            </div>
                            <div class="content-box">
                                <ul class="swp-meta swp-mb-15">
                                    <?php if (!empty($item['author'])) : ?>
                                        <li><i class="far fa-user-circle"></i> <?php echo esc_html($item['author']); ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($item['date'])) : ?>
                                        <li><i class="far fa-calendar-check"></i> <?php echo esc_html($item['date']); ?></li>
                                    <?php endif; ?>
                                </ul>
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
                <?php else : ?>
                    <div class="swp-single-inner-wrap style-12">
                        <div class="swp-row">
                            <div class="swp-col-sm-6 order-lg-1">
                                <div class="swp-single-inner style-1 swp-angle-right swp-angle-right-circle-date">
                                    <?php if (!empty($item['year'])) : ?>
                                        <div class="year-wrap-2"><span><?php echo esc_html($item['year']); ?></span></div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['date'])) : ?>
                                        <div class="year-wrap"><?php echo esc_html($item['date']); ?></div>
                                    <?php endif; ?>
                                    <div class="date-wrap">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <div class="content-box">
                                        <ul class="swp-meta swp-mb-15">
                                            <li><i class="far fa-user-circle"></i> <?php echo esc_html($item['author']); ?></li>
                                            <?php if (!empty($item['date'])) : ?>
                                                <li><i class="far fa-calendar-check"></i> <?php echo esc_html($item['date']); ?></li>
                                            <?php endif; ?>
                                        </ul>
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
    <!-- timeline Card 12 -->
<?php endif;
