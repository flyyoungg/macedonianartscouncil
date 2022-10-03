<?php

if ($settings['style'] == 'style-1') :

?>
    <!-- timeline Card 10 -->
    <section class="swp-timeline-area style-10">
        <div class="swp-container">
            <div class="swp-relative swp-timeline-line-height-left">
                <div class="swp-timeline-line-height"></div>
                <div class="swp-container">
                    <?php
                    foreach ($settings['layout_one_timeline'] as $item) : ?>
                        <div class="swp-single-inner-wrap style-10">
                                    <div class="swp-single-inner style-2 swp-angle-left-circle-date">
                                        <div class="year-wrap"><?php echo esc_html($item['year']); ?></div>
                                        <div class="date-wrap">
                                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => ' '], 'i'); ?>
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
                                                    <ul class="swp-meta swp-mb-15">
                                                        <?php if (!empty($item['author'])) : ?>
                                                            <li><i class="far fa-user-circle"></i> <?php echo esc_html($item['author']); ?></li>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['date'])) : ?>
                                                            <li><i class="far fa-calendar-check"></i><?php echo esc_html($item['date']); ?></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                    <p class="content"><?php echo esc_html($item['content']); ?></p>
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
    <!-- timeline Card 10 -->
<?php endif;