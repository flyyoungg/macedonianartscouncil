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
class Swp_Timeline_Style_Blog_three extends Widget_Base
{

    public function get_name()
    {
        return 'swp-timeline-blog-three';
    }

    public function get_title()
    {
        return esc_html__('Blog Style Three', 'swp-timeline');
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

        $this->add_control(
            'ppr',
            [
                'label'   => esc_html__('Amount of post to display', 'swp-timeline'),
                'type'    => Controls_Manager::TEXT,
                'default' => 2
            ]
        );

        $this->add_control(
            'select_cat',
            [
                'label'    => esc_html__('Select Category', 'swp-timeline'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => swp_timeline_post_category(),

            ]
        );


        $this->add_control(
            'exclude_cat',
            [
                'label'    => esc_html__('Exclude Category', 'swp-timeline'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => swp_timeline_post_category(),
            ]
        );

        $this->add_control(
            'select_tag',
            [
                'label'    => esc_html__('Select tag', 'swp-timeline'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => swp_timeline_post_tag(),
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Order by', 'swp-timeline'),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'author' => esc_html__('Author', 'swp-timeline'),
                    'title'  => esc_html__('Title', 'swp-timeline'),
                    'date'   => esc_html__('Date', 'swp-timeline'),
                    'rand'   => esc_html__('Random', 'swp-timeline'),
                ),
                'default' => 'date'

            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'swp-timeline'),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'desc' => esc_html__('DESC', 'swp-timeline'),
                    'asc'  => esc_html__('ASC', 'swp-timeline'),
                ),
                'default' => 'desc'

            ]
        );

        $this->add_control(
            'content_excerpt_length',
            [
                'label'   => esc_html__('Content Excerpt Length', 'swp-timeline'),
                'type'    => Controls_Manager::TEXT,
                'default' => 15,
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

        $this->add_control(
            'date_and_month_bg_color',
            [
                'label' => esc_html__('Date And Month Background Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-angle-left-circle-date .date-wrap' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-1', 'style-2']
                ]
            ]
        );

        $this->add_control(
            'year_color',
            [
                'label' => esc_html__('Year Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-angle-left-circle-date .year-wrap' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'style' => ['style-2', 'style-3']
                ]
            ]
        );

        //year typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'year_typography',
                'label'          => esc_html__('Year Typography', 'swp-timeline'),
                'selector'       => '{{WRAPPER}} .swp-angle-left-circle-date .year-wrap',
                'condition' => [
                    'style' => ['style-2', 'style-3']
                ]
            ]
        );


        $this->add_control(
            'circle_color',
            [
                'label' => esc_html__('Circle Color', 'swp-timeline'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swp-single-inner-wrap.style-15 .swp-angle-left-circle:before,
                    .swp-single-inner-wrap.style-15 .swp-angle-left-circle:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .owl-nav-style-icon .owl-nav button' => 'color: {{VALUE}}'
                ],
                'condition' => [
                    'style' => 'style-3'
                ]
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
    }

    protected function render()
    {

        $settings = $this->get_settings();

        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

        $args  = array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $settings['ppr'],
            'paged' => $paged,
        );

        $args['orderby'] = $settings['orderby'];
        $args['order']   = $settings['order'];
        if (!empty($settings['exclude_cat'])) {
            $args['category__not_in'] = $settings['exclude_cat'];
        }


        if (!empty($settings['select_cat'])) {
            $catgory             = implode(', ', $settings['select_cat']);
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => array_values($settings['select_cat'])
            );
        }

        if (!empty($settings['select_tag'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'id',
                'terms'    => array_values($settings['select_tag'])
            );
        }

        $posts_query = new \WP_Query($args);

        include swp_timeline_get_template('blog-style-three-layout-one.php');
        include swp_timeline_get_template('blog-style-three-layout-two.php');
        include swp_timeline_get_template('blog-style-three-layout-three.php');
    }
}

plugin::instance()->widgets_manager->register(new Swp_Timeline_Style_Blog_three());
