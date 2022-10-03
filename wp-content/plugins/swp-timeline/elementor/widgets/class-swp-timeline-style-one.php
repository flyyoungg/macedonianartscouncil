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
class Swp_Timeline_Style_One extends Widget_Base
{

    public function get_name()
    {
        return 'swp-timeline--general';
    }

    public function get_title()
    {
        return esc_html__('Style One', 'swp-timeline');
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
            'date',
            [
                'label' => esc_html__('Date', 'swp-timeline'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Date', 'swp-timeline'),
                'default' => esc_html__('25 January 2022', 'swp-timeline')
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
            'date_color',
            [
                'label' => esc_html__('Date Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner .content-box .date' => 'color: {{VALUE}}',
                ],
            ]
        );


        //date typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'date_typography',
                'label'          => esc_html__('Date Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-single-inner .content-box .date',
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
            'hover_border_color',
            [
                'label' => esc_html__('Hover Border Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner-wrap:hover .swp-single-inner.style-1.swp-angle-left,
                    {{WRAPPER}} .swp-single-inner-wrap:hover .swp-angle-left:after,
                    {{WRAPPER}} .swp-single-inner-wrap:hover .swp-single-inner.style-1.swp-angle-right,
                    {{WRAPPER}} .swp-single-inner-wrap:hover .swp-angle-right:after' => 'border-color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'timeline_circle_color',
            [
                'label' => esc_html__('Timeline Circle Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-angle-right-circle:before, {{WRAPPER}} .swp-angle-left-circle:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-2', 'style-3']
                ]

            ]
        );

        $this->add_control(
            'timeline_circle_hover_color',
            [
                'label' => esc_html__('Timeline Circle Hover Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-angle-right-circle:hover:before, {{WRAPPER}} .swp-angle-left-circle:hover:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-2', 'style-3']
                ]

            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings();

        include swp_timeline_get_template('style-one-layout-one.php');
        include swp_timeline_get_template('style-one-layout-two.php');
        include swp_timeline_get_template('style-one-layout-three.php');
    }
}

plugin::instance()->widgets_manager->register(new Swp_Timeline_Style_One());
