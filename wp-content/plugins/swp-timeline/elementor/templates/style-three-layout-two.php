<?php

if ($settings['style'] == 'style-2') :

?>
    <!-- timeline Card 11 -->
    <section class="swp-timeline-area style-11">
        <div class="swp-timeline-line-height"></div>
        <div class="swp-container">
            <?php $i = 1;
            foreach ($settings['layout_two_timeline'] as $item) : ?>
                <?php if ($i % 2 == 0) : ?>
                    <div class="swp-single-inner-wrap style-11 style-50-left">
                        <div class="swp-single-inner style-1 swp-angle-left swp-angle-left-circle-date">
                            <?php if (!empty($item['year'])) : ?>
                                <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                            <?php endif; ?>
                            <div class="date-wrap">
                                <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => ' '], 'i'); ?>
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
                                <?php if (!empty($item['checklist'])) : ?>
                                    <ul class="swp-single-list-inner style-check swp-mt-15">
                                        <?php echo wp_kses_post($item['checklist']); ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="swp-single-inner-wrap style-11">
                        <div class="swp-row">
                            <div class="swp-col-sm-6 order-lg-1">
                                <div class="swp-single-inner style-1 swp-angle-right swp-angle-right-circle-date">
                                    <?php if (!empty($item['year'])) : ?>
                                        <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                                    <?php endif; ?>
                                    <div class="date-wrap">
                                        <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => ' '], 'i'); ?>
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
                                        <?php if (!empty($item['checklist'])) : ?>
                                            <ul class="swp-single-list-inner style-check swp-mt-15">
                                                <?php echo wp_kses_post($item['checklist']); ?>
                                            </ul>
                                        <?php endif; ?>
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
    <!-- timeline Card 11 -->
<?php endif;
