<?php

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;


use DOMDocument;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class DD_Navigation_Menu_Tree_Widget extends Widget_Base {

	public function get_name() {
		return 'dd-navigation-menu-tree';
	}

	public function get_title() {
		return 'Navigation Menu Tree';
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return [ 'drive-design' ];
	}

	public function get_style_depends() {
		return ['dd-navigation-menu-tree'];
	}

   public function get_script_depends() {
		return [ 'dd-navigation-menu-tree' ];
	}


	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function register_controls() {

		// MAIN SECTION
		$this->start_controls_section( 'section_content', [
			'label' => 'Settings',
		] );

			$menus = $this->get_available_menus();

			$this->add_control( 'dd_menu_tree_name', [
				'label' => 'Menu',
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $menus,
				'default' => !empty($menus) ? array_keys( $menus )[0] : '',
			] );

			$this->add_control( 'dd_taxonomy_unfold', [
				'label' => 'Unfold tree',
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => 'None',
					'current' => 'To current item',
					'all' => 'All',
				],
				'prefix_class' => 'dd-unfold-',
			] );
		$this->end_controls_section(); // end main


		// STYLE SECTION 
		$this->start_controls_section( 'section_style', [
			'label' => 'Style',
			'tab' => Controls_Manager::TAB_STYLE,
		] );


			// FONT
			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name' => 'dd_menu_tree_typography',
				'label' => 'Normal font',
				'selector' => '{{WRAPPER}} .dd-navigation-tree li',
			] );






			// TABS
			$this->start_controls_tabs( 'dd_menu_tree_text_style' );

				// TAB NORMAL
				$this->start_controls_tab( 'dd_menu_tree_normal', [
					'label' => 'Normal',
				] );

					$this->add_control( 'dd_menu_tree_color', [
						'label' => 'Text Color',
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree .menu-item a' => 'color: {{VALUE}};',
						],
					] );
				$this->end_controls_tab();

				// TAB HOVER
				$this->start_controls_tab( 'dd_menu_tree_hover', [
					'label' => 'Hover',
				] );

					$this->add_control( 'dd_menu_tree_hover_color', [
						'label' => 'Text Color',
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree .menu-item a:hover' => 'color: {{VALUE}};',
						],
					] );

					$this->add_control( 'dd_menu_tree_hover_underline', [
						'label' => 'Underline',
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => 'Yes',
						'label_off' => 'No',
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree .menu-item a:hover' => 'text-decoration: underline'
						],
					] );

					$this->add_control( 'dd_menu_tree_hover_bold', [
						'label' => 'Bold',
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => 'Yes',
						'label_off' => 'No',
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree .menu-item a:hover' => 'font-weight: bold'
						],
					] );

				$this->end_controls_tab();

				// TAB ACTIVE
				$this->start_controls_tab( 'dd_menu_tree_active', [
					'label' => 'Active',
				] );

					$this->add_control( 'dd_menu_tree_active_color', [
						'label' => 'Text Color',
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree li.current-menu-item > a' => 'color: {{VALUE}};',
						],
					] );

					$this->add_control( 'dd_menu_tree_active_underline', [
						'label' => 'Underline',
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => 'Yes',
						'label_off' => 'No',
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree li.current-menu-item > a' => 'text-decoration: underline'
						],
					] );

					$this->add_control( 'dd_menu_tree_active_bold', [
						'label' => 'Bold',
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => 'Yes',
						'label_off' => 'No',
						'selectors' => [
							'{{WRAPPER}} .dd-navigation-tree li.current-menu-item > a' => 'font-weight: bold'
						],
					] );

				$this->end_controls_tab();

			$this->end_controls_tabs();





			// VERTICAL SPACING
			$this->add_responsive_control( 'dd_menu_tree_vertical_spacing', [
				'label' => 'Vertical spacing',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree li a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			] );




			// LEFT INDENT
			$this->add_responsive_control( 'dd_menu_tree_left_gap', [
				'label' => 'Sublevel Left Gap',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.5,
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree li ul' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			] );






			// ICON
			// $this->add_control( 'dd_menu_tree_icon', [
			// 	'label' => 'Icon',
			// 	'type' => \Elementor\Controls_Manager::SELECT,
			// 	'default' => 'eicon-plus-square-o',
			// 	'options' => [
			// 		'fa-square-plus' => 'Plus',
			// 		'eicon-caret-right' => 'Caret',
			// 		'eicon-chevron-right' => 'Shevron',
			// 	],
			// 	'separator' => 'before',
			// ] );

			$this->add_control( 'dd_menu_tree_icon', [
				'label' => 'Icon',
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-caret-right',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'caret-right',
						'chevron-right',
					],
				],
			] );

			$this->add_control( 'dd_menu_tree_icon_color', [
				'label' => 'Icon Color',
				'type' => Controls_Manager::COLOR,
				'default' => '#999',
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree .sub-toggler' => 'color: {{VALUE}};',
				],
			] );



			// LINE
			$this->add_control( 'dd_menu_tree_line_heading', [
				'label' => 'Guides',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->add_control( 'dd_menu_tree_line_type', [
				'label' => 'Line Style',
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'dotted',
				'options' => [
					'none' => 'none',
					'solid' => 'solid',
					'dashed' => 'dashed',
					'dotted' => 'dotted',
				],
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree li ul' => 'border-left-style: {{VALUE}};',
				],
			] );

			$this->add_control( 'dd_menu_tree_line_color', [
				'label' => 'Line Color',
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree li ul' => 'border-left-color: {{VALUE}};',
				],
			] );

			$this->add_responsive_control( 'dd_menu_tree_line_width', [
				'label' => 'Line Width',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dd-navigation-tree li ul' => 'border-left-width: {{SIZE}}{{UNIT}};',
				],
			] );

		$this->end_controls_section(); // end style
	}

	protected function render() {

		$available_menus = $this->get_available_menus();
		if ( ! $available_menus ) {
			return;
		}

		// get list of terms
		$settings = $this->get_settings_for_display();

		$menu_id = 'dd_menu_tree_' . $this->get_id();

		$args = [
			'echo' => false,
			'menu' => $settings['dd_menu_tree_name'],
			'container' => '',
			'menu_id' => $menu_id,
			
		];

		$html = wp_nav_menu( $args );
			


		// DOM object to manipulate results of wp_list_categories()
		$DOM = new DOMDocument();
		$DOM->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$menu = $DOM->getElementById($menu_id);
		$uls = $menu->getElementsByTagName('ul');

		foreach($uls as $ul){
			
			$parent = $ul->parentNode;
			$firstChild = $parent->firstChild;


			$toggleSpan = $DOM->createDocumentFragment();

			ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['dd_menu_tree_icon'], [ 'aria-hidden' => 'true' ] );
			$icon_html = ob_get_clean();

			if ($settings['dd_taxonomy_unfold'] == 'all') {
				$toggleSpan->appendXML('<span class="sub-toggler opened">' . $icon_html . '</span>');
			} else {
				$toggleSpan->appendXML('<span class="sub-toggler">' . $icon_html . '</span>');
			}
			
			$parent->insertBefore($toggleSpan, $firstChild);

		}

		$html=$DOM->saveHTML();

			


		// output
		echo '<div class="dd-navigation-tree dd-navigation-wrapper">' . $html . '</div>';		

	}

}


