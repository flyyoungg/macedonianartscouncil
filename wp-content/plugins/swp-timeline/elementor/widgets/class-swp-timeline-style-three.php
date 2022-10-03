<?php

namespace Elementor;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


/**
 *
 * swp-timeline elementor  widget.
 *
 * @since 1.0
 */
class Swp_Timeline_Style_Three extends Widget_Base
{

    public function get_name()
    {
        return 'swp-timeline-three';
    }

    public function get_title()
    {
        return esc_html__('Style Three', 'swp-timeline');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return ['swp-timeline'];
    }

    protected function _register_controls()
    {

        // general settings
        $this->start_controls_section(
            'layout_settings',
            [
                'label' => esc_html__('General Settings', 'swp-timeline'),
            ]
        );


        $this->add_control(
            'style',
            [
                'label'   => esc_html__('Select Style', 'swp-timeline'),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'style-1' => esc_html__('Style 1', 'swp-timeline'),
                    'style-2'  => esc_html__('Style 2', 'swp-timeline'),
                    'style-3'  => esc_html__('Style 3', 'swp-timeline'),

                ),
                'default' => 'style-1'
            ]
        );

        $this->end_controls_section(); // End general settings


        // general settings
        $this->start_controls_section(
            'layout_one_content',
            [
                'label' => esc_html__('Content', 'swp-timeline'),
            ]
        );

        $layout_one_timeline = new \Elementor\Repeater();

        $layout_one_timeline->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add title', 'swp-timeline'),
                'default' => esc_html__('Default Title', 'swp-timeline')
            ]
        );


        $layout_one_timeline->add_control(
            'url',
            [
                'label' => __('Url', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('#', 'swp-timeline'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'show_label' => false,
            ]
        );

        $layout_one_timeline->add_control(
            'author',
            [
                'label' => esc_html__('Author Name', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Author Name', 'swp-timeline'),
                'default' => esc_html__('John', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_one_timeline->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Date', 'swp-timeline'),
                'default' => esc_html__('25 January 2022', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_one_timeline->add_control(
            'year',
            [
                'label' => esc_html__('Year', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Year', 'swp-timeline'),
                'default' => esc_html__('2021', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_one_timeline->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add Content', 'swp-timeline'),
                'default' => esc_html__('Default Content', 'swp-timeline')
            ]
        );


        $layout_one_timeline->add_control(
            'icon',
            [
                'label' => __('Icon', 'brote-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-home',
                    'library' => 'custom-icon',
                ],
            ]
        );

        $layout_one_timeline->add_control(
            'image',
            [
                'label' => __('Image', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );


        $this->add_control(
            'layout_one_timeline',
            [
                'label' => esc_html__('Timeline Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $layout_one_timeline->get_controls(),
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'style' =>  'style-1'
                ]
            ]
        );


        $layout_two_timeline = new \Elementor\Repeater();

        $layout_two_timeline->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add title', 'swp-timeline'),
                'default' => esc_html__('Default Title', 'swp-timeline')
            ]
        );


        $layout_two_timeline->add_control(
            'url',
            [
                'label' => __('Url', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('#', 'swp-timeline'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'show_label' => false,
            ]
        );


        $layout_two_timeline->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Date', 'swp-timeline'),
                'default' => esc_html__('25 January 2022', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_two_timeline->add_control(
            'year',
            [
                'label' => esc_html__('Year', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Year', 'swp-timeline'),
                'default' => esc_html__('2021', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_two_timeline->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add Content', 'swp-timeline'),
                'default' => esc_html__('Default Content', 'swp-timeline')
            ]
        );


        $layout_two_timeline->add_control(
            'checklist',
            [
                'label' => esc_html__('Check List', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::CODE,
                'placeholder' => esc_html__('Add Content', 'swp-timeline'),
                'default' => wp_kses_post(__('<i class="fa fa-check"></i> Web Design Trend', 'swp-timeline'))
            ]
        );


        $layout_two_timeline->add_control(
            'icon',
            [
                'label' => __('Icon', 'brote-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-home',
                    'library' => 'custom-icon',
                ],
            ]
        );


        $this->add_control(
            'layout_two_timeline',
            [
                'label' => esc_html__('Timeline Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $layout_two_timeline->get_controls(),
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'style' =>  'style-2'
                ]
            ]
        );

        $layout_three_timeline = new \Elementor\Repeater();

        $layout_three_timeline->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add title', 'swp-timeline'),
                'default' => esc_html__('Default Title', 'swp-timeline')
            ]
        );


        $layout_three_timeline->add_control(
            'url',
            [
                'label' => __('Url', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('#', 'swp-timeline'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'show_label' => false,
            ]
        );

        $layout_three_timeline->add_control(
            'author',
            [
                'label' => esc_html__('Author', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Author Name', 'swp-timeline'),
                'default' => esc_html__('John', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_three_timeline->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Date', 'swp-timeline'),
                'default' => esc_html__('25 January 2022', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_three_timeline->add_control(
            'year',
            [
                'label' => esc_html__('Year', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Year', 'swp-timeline'),
                'default' => esc_html__('2021', 'swp-timeline'),
                'label_block' => true
            ]
        );

        $layout_three_timeline->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Add Content', 'swp-timeline'),
                'default' => esc_html__('Default Content', 'swp-timeline')
            ]
        );


        $layout_three_timeline->add_control(
            'icon',
            [
                'label' => __('Icon', 'brote-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-home',
                    'library' => 'custom-icon',
                ],
            ]
        );


        $this->add_control(
            'layout_three_timeline',
            [
                'label' => esc_html__('Timeline Content', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $layout_three_timeline->get_controls(),
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'style' =>  'style-3'
                ]
            ]
        );


        $this->end_controls_section(); // End general settings

        //title style
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title Style', 'swp-timeline'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner .content-box .inner-title a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Title Hover Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner .content-box .inner-title:hover a' => 'color: {{VALUE}}',
                ],
            ]
        );

        //title typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'label'          => esc_html__('Title Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-single-inner .content-box .inner-title a',
            ]
        );

        $this->end_controls_section();


        //date style
        $this->start_controls_section(
            'date_style',
            [
                'label' => esc_html__('Date Style', 'swp-timeline'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Meta Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-meta li,{{WRAPPER}} .swp-meta li' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-1', 'style-3']
                ]
            ]
        );


        //Meta typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'meta_typography',
                'label'          => esc_html__('Meta Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-meta li,{{WRAPPER}} .swp-meta li',
                'condition' => [
                    'style' => ['style-1', 'style-3']
                ]
            ]
        );

        $this->add_control(
            'year_color',
            [
                'label' => esc_html__('Year Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner-wrap.style-10 .swp-angle-left-circle-date .year-wrap, {{WRAPPER}}  .swp-single-inner-wrap.style-12 .swp-angle-right-circle-date .year-wrap-2 span' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-1', 'style-3']
                ]
            ]
        );


        $this->add_control(
            'bg_year_color',
            [
                'label' => esc_html__('Year Background Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner-wrap.style-12 .swp-angle-left-circle-date .year-wrap-2 span,{{WRAPPER}} .swp-single-inner-wrap.style-12 .swp-angle-right-circle-date .year-wrap-2 span ' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => 'style-3'
                ]
            ]
        );

        //Meta typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'year_typography',
                'label'          => esc_html__('Year Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-single-inner-wrap.style-10 .swp-angle-left-circle-date .year-wrap,{{WRAPPER}}  .swp-single-inner-wrap.style-12 .swp-angle-right-circle-date .year-wrap-2 span',
                'condition' => [
                    'style' => ['style-1', 'style-3']
                ]
            ]
        );


        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Date Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner .content-box .date,{{WRAPPER}} .swp-single-inner-wrap.style-12 .swp-angle-right-circle-date .year-wrap' => 'color: {{VALUE}}',
                ],
            ]
        );


        //date typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'date_typography',
                'label'          => esc_html__('Date Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-single-inner .content-box .date, {{WRAPPER}} .swp-single-inner-wrap.style-12 .swp-angle-right-circle-date .year-wrap',
            ]
        );

        $this->end_controls_section();

        //content style
        $this->start_controls_section(
            'content_style',
            [
                'label' => esc_html__('Content Style', 'swp-timeline'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Content Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner .content-box p' => 'color: {{VALUE}}',
                ],

            ]
        );


        //content typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'content_typography',
                'label'          => esc_html__('Content Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-single-inner .content-box p',

            ]
        );

        $this->end_controls_section();

        //Other style
        $this->start_controls_section(
            'other_style',
            [
                'label' => esc_html__('Other Style', 'swp-timeline'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__('Icon Bg Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner-wrap.style-10 .swp-angle-left-circle-date .date-wrap,
                    {{WRAPPER}} .swp-angle-right-circle-date .date-wrap' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-1', 'style-2', 'style-3']
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings();

        include swp_timeline_get_template('style-three-layout-one.php');
        include swp_timeline_get_template('style-three-layout-two.php');
        include swp_timeline_get_template('style-three-layout-three.php');
    }
}

plugin::instance()->widgets_manager->register(new Swp_Timeline_Style_Three());