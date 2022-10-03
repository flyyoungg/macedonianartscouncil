<?php

if ($settings['style'] == 'style-4') :

?>
    <!-- timeline Card 8 -->
    <section class="swp-timeline-area style-8">
        <div class="swp-container">
            <div class="swp-relative swp-timeline-line-height-left">
                <div class="swp-timeline-line-height"></div>
                <div class="swp-container">
                    <?php
                    foreach ($settings['layout_three_timeline'] as $item) : ?>
                        <div class="swp-single-inner-wrap style-1">
                            <div class="swp-row">
                                <div class="swp-col-lg-8">
                                    <div class="swp-single-inner style-2 swp-angle-left-circle-date">
                                        <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                                        <div class="date-wrap">
                                            <?php echo esc_html($item['short_date']); ?> <br> <?php echo esc_html($item['short_month']); ?>
                                        </div>
                                        <div class="swp-row">
                                            <div class="swp-col-lg-5">
                                                <div class="swp-thumb-wrap">
                                                    <div class="thumb" style="background-image: url('<?php echo esc_url($item['image']['url']); ?>');">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swp-col-lg-7 align-self-center">
                                                <div class="content-box">
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
                            </div>
                        </div>
                    <?php
                    endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- timeline Card 8 -->
<?php endif;
