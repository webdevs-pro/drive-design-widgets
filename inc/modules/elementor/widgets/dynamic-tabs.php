<?php

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DD_Dynamic_Tabs_Widget extends Elementor\Widget_Base {

	public function get_name() {
		return 'dd-dynamic-tabs';
	}

	public function get_title() {
		return 'Dynamic Tabs';
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return ['drive-design'];
	}

	public function get_keywords() {
		return ['popup', 'placeholder'];
	}

	public function get_style_depends() {
		return ['dd-dynamic-tabs'];
	}

   public function get_script_depends() {
		return [ 'dd-dynamic-tabs' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_items', [
			'label' => 'Tabs',
		] );

         $repeater = new \Elementor\Repeater();

            $repeater->add_control( 'title', [
               'label' => 'Tab Title',
               'label_block' => true,
               'type' => \Elementor\Controls_Manager::TEXT,
               'default' => 'Tab Title',
               'ai' => [
                  'active' => false,
               ],
            ] );

            $repeater->add_control( 'content_type', [
               'label' => 'Tab Content Type',
               'label_block' => true,
               'type' => \Elementor\Controls_Manager::SELECT,
               'default' => 'text',
               'options' => [
                  'text' => 'Text / HTML',
                  'template' => 'Template',
               ],
            ] );

            $document_types = ElementorPro\Plugin::elementor()->documents->get_document_types( [
               'show_in_library' => true,
            ] );

            $repeater->add_control( 'template_id', [
               'label' => 'Choose Template',
               'type' => QueryControlModule::QUERY_CONTROL_ID,
               'label_block' => true,
               'autocomplete' => [
                  'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
                  'query' => [
                     'meta_query' => [
                        [
                           'key' => '_elementor_template_type',
                           'value' => array_keys( $document_types ),
                           'compare' => 'IN',
                        ],
                     ],
                  ],
               ],
               'condition' => [
                  'content_type' => 'template',
               ],
            ] );

            $repeater->add_control( 'content', [
               'label' => 'Content',
               'label_block' => true,
               'type' => \Elementor\Controls_Manager::WYSIWYG,
               'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
               'condition' => [
                  'content_type' => 'text',
               ],
               'ai' => [
                  'active' => false,
               ],
            ] );

            $repeater->add_control( 'cv_popover', [
               'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
               'label' => 'Tab Conditional Visibility',
               'label_off' => 'Default',
               'label_on' => 'Custom',
               'return_value' => 'yes',
            ] );
            
            $repeater->start_popover();

               $repeater->add_control( 'cv_action', [
                  'label' => 'Action',
                  'label_block' => true,
                  'type' => \Elementor\Controls_Manager::SELECT,
                  'default' => 'show',
                  'options' => [
                     'show' => 'Show Tab If',
                     'hide' => 'Hide Tab If',
                  ],
               ] );

               $repeater->add_control( 'cv_condition', [
                  'label' => 'Type',
                  'label_block' => true,
                  'type' => \Elementor\Controls_Manager::SELECT,
                  'options' => [
                     'post_meta' => 'Post Meta Field',
                  ],
                  'label_block' => true,
                  'default' => 'post_meta',
               ] );

               $repeater->add_control( 'cv_meta_name', [
                  'label' => 'Post Meta Name',
                  'label_block' => true,
                  'type' => \Elementor\Controls_Manager::TEXT,
                  'label_block' => true,
                  'condition' => [
                     'cv_condition' => 'post_meta',
                  ],
                  'ai' => [
                     'active' => false,
                  ],
               ] );

               $repeater->add_control( 'cv_operator', [
                  'label' => 'Operator',
                  'label_block' => true,
                  'type' => \Elementor\Controls_Manager::SELECT,
                  'options' => [
                     'equal' => 'Is Equal',
                     'not_equal' => 'Is Not equal',
                     'empty' => 'Is Empty',
                     'not_empty' => 'Is Not empty',
                  ],
                  'label_block' => true,
                  'default' => 'equal',
                  'condition' => [
                     'cv_condition' => 'post_meta',
                  ],
               ] );

               $repeater->add_control( 'cv_meta_value', [
                  'type' => \Elementor\Controls_Manager::TEXT,
                  'placeholder' => 'Post Meta Value',
                  'label_block' => true,
                  'condition' => [
                     'cv_operator' => ['equal','not_equal'],
                  ],
               ] );
            
            $repeater->end_popover();

         $this->add_control( 'tabs', [
            'label' => 'Tabs',
            'type'         => \Elementor\Controls_Manager::REPEATER,
            'fields'       => $repeater->get_controls(),
            'item_actions' => [
               'add'       => true,
               'duplicate' => true,
               'remove'    => true,
               'sort'      => true,
            ],
            'title_field'  => '{{{title}}}',
            'show_label'   => true,
            'frontend_available' => true,
         ] );

         $this->add_responsive_control( 'tabs_justify', [
            'label' => 'Justify',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'start' => [
                  'title' => 'Start',
                  'icon' => "eicon-align-start-h",
               ],
               'center' => [
                  'title' => 'Center',
                  'icon' => 'eicon-align-center-h',
               ],
               'end' => [
                  'title' => 'End',
                  'icon' => "eicon-align-end-h",
               ],
               'stretch' => [
                  'title' => 'Stretch',
                  'icon' => 'eicon-align-stretch-h',
               ],
            ],
            'selectors_dictionary' => [
               'start' => '--tabs-heading-justify-content: flex-start; --tabs-title-flex-grow: 0;',
               'center' => '--tabs-heading-justify-content: center; --tabs-title-flex-grow: 0;',
               'end' => '--tabs-heading-justify-content: flex-end; --tabs-title-flex-grow: 0;',
               'stretch' => '--tabs-heading-justify-content: initial; --tabs-title-flex-grow: 1;',
            ],
            'selectors' => [
               '{{WRAPPER}}' => '{{VALUE}}',
            ],
         ] );
   
         $this->add_responsive_control( 'title_alignment', [
            'label' => 'Align Title',
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'start' => [
                  'title' => 'Start',
                  'icon' => 'eicon-text-align-left',
               ],
               'center' => [
                  'title' => 'Center',
                  'icon' => 'eicon-text-align-center',
               ],
               'end' => [
                  'title' => 'End',
                  'icon' => 'eicon-text-align-right',
               ],
            ],
            'selectors_dictionary' => [
               'start' => '--tabs-title-text-align: start;',
               'center' => '--tabs-title-text-align: center;',
               'end' => '--tabs-title-text-align: end;',
            ],
            'selectors' => [
               '{{WRAPPER}}' => '{{VALUE}}',
            ],
            'condition' => [
               'tabs_justify' => 'stretch',
            ]
         ] );

         $this->add_responsive_control( 'tabs_title_space_between', [
            'label' => 'Gap Between Tabs',
            'type' => Controls_Manager::SLIDER,
            'range' => [
               'px' => [
                  'min' => 0,
                  'max' => 400,
               ],
            ],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
            'size_units' => [ 'px' ],
            'selectors' => [
               '{{WRAPPER}}' => '--tabs-title-gap: {{SIZE}}{{UNIT}}',
            ],
         ] );

		$this->end_controls_section();



















      $this->start_controls_section( 'section_buttons_style', [
			'label' => 'Buttons',
         'tab'   => Controls_Manager::TAB_STYLE,
		] );

         $this->add_group_control( Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'tabs_title_typography',
            'label'    => 'Typography',
            'selector' => '{{WRAPPER}} .dd-tab-button',
         ] );

         $this->start_controls_tabs( 'tabs_title_style' );

            // Button normal
            $this->start_controls_tab( 'tabs_title_normal', [
               'label' => 'Normal',
            ] );


               $this->add_control( 'tabs_title_color', [
                  'label'     => 'Color',
                  'type'      => Controls_Manager::COLOR,
                  'default'   => '#FFF',
                  'selectors' => [
                     '{{WRAPPER}} .dd-tab-button:not(:hover)' => 'color: {{VALUE}};',
         
                  ],
               ] );

               $this->add_group_control( Group_Control_Background::get_type(), [
                  'name' => 'tabs_title_background_color',
                  'types' => [ 'classic', 'gradient' ],
                  'exclude' => [ 'image' ],
                  'selector' => '{{WRAPPER}} .dd-tab-button:not(:hover)',
                  'fields_options' => [
                     'background' => [
                        'default' => 'classic',
                     ],
                     'color' => [
                        'label' => 'Background Color',
                        'default' => '#eef1f5',
                        'selectors' => [
                           '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Border::get_type(), [
                  'name' => 'tabs_title_border',
                  'selector' => ".dd-tab-button:not(:hover)",
                  'fields_options' => [
                     'color' => [
                        'label' => 'Border Color',
                     ],
                     'width' => [
                        'label' => 'Border Width',
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
                  'name' => 'tabs_title_box_shadow',
                  'label' => 'Shadow',
                  'separator' => 'after',
                  'selector' => ".dd-tab-button:not( :hover )",
               ] );

            $this->end_controls_tab();


            // Button hover
            $this->start_controls_tab( 'tabs_title_hover', [
               'label' => 'Hover',
            ] );

               $this->add_control( 'tabs_title_color_hover', [
                  'label'     => 'Color',
                  'type'      => Controls_Manager::COLOR,
                  'default'   => '#FFF',
                  'selectors' => [
                     '{{WRAPPER}} .dd-tab-button:hover' => 'color: {{VALUE}};',
         
                  ],
               ] );

               $this->add_group_control( Group_Control_Background::get_type(), [
                  'name' => 'tabs_title_background_color_hover',
                  'types' => [ 'classic', 'gradient' ],
                  'exclude' => [ 'image' ],
                  'selector' => ".dd-tab-button:hover",
                  'fields_options' => [
                     'background' => [
                        'default' => 'classic',
                     ],
                     'color' => [
                        'default' => '#15356d',
                        'label' => 'Background Color',
                        'selectors' => [
                           '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Border::get_type(), [
                  'name' => 'tabs_title_border_hover',
                  'selector' => ".dd-tab-button:hover",
                  'fields_options' => [
                     'color' => [
                        'label' => 'Border Color',
                     ],
                     'width' => [
                        'label' => 'Border Width',
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
                  'name' => 'tabs_title_box_shadow_hover',
                  'label' => 'Shadow',
                  'separator' => 'after',
                  'selector' => ".dd-tab-button:hover",
               ] );

               $this->add_control( 'tabs_title_transition_duration', [
                  'label' => 'Transition Duration' . ' (s)',
                  'type' => Controls_Manager::SLIDER,
                  'selectors' => [
                     '{{WRAPPER}}' => '--tabs-title-transition: {{SIZE}}s',
                  ],
                  'range' => [
                     'px' => [
                        'max' => 3,
                        'step' => 0.1,
                     ],
                  ],
                  'default' => [
                     'px' => 0.2,
                  ],
               ] );

            $this->end_controls_tab();

            $this->start_controls_tab( 'tabs_title_active', [
               'label' => 'Active',
            ] );

               $this->add_control( 'tabs_title_colo_active', [
                  'label'     => 'Color',
                  'type'      => Controls_Manager::COLOR,
                  'default'   => '#FFF',
                  'selectors' => [
                     '{{WRAPPER}} .dd-tab-button.active' => 'color: {{VALUE}};',
         
                  ],
               ] );

               $this->add_group_control( Group_Control_Background::get_type(), [
                  'name' => 'tabs_title_background_color_active',
                  'types' => [ 'classic', 'gradient' ],
                  'exclude' => [ 'image' ],
                  'selector' => ".dd-tab-button.active",
                  'fields_options' => [
                     'background' => [
                        'default' => 'classic',
                     ],
                     'color' => [
                        'default' => '#15356d',
                        'label' => 'Background Color',
                        'selectors' => [
                           '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Border::get_type(), [
                  'name' => 'tabs_title_border_active',
                  'selector' => ".dd-tab-button.active",
                  'fields_options' => [
                     'color' => [
                        'label' => 'Border Color',
                     ],
                     'width' => [
                        'label' => 'Border Width',
                     ],
                  ],
               ] );

               $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
                  'name' => 'tabs_title_box_shadow_active',
                  'label' => 'Shadow',
                  'selector' => ".dd-tab-button.active",
               ] );

            $this->end_controls_tab();

         $this->end_controls_tabs();

         $this->add_responsive_control( 'tabs_title_border_radius', [
            'label' => 'Border Radius',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            'separator' => 'before',
            'default'    => [
               'top'      => '0',
               'bottom'   => '0',
               'left'     => '0',
               'right'    => '0',
               'unit'     => 'px',
               'isLinked' => true,
            ],
            'selectors' => [
               '{{WRAPPER}} .dd-tab-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ] );

         $this->add_responsive_control( 'padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
            'default'    => [
               'top'      => '8',
               'bottom'   => '8',
               'left'     => '24',
               'right'    => '24',
               'unit'     => 'px',
               'isLinked' => true,
            ],
            'selectors' => [
               '{{WRAPPER}} .dd-tab-button' => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
         ] );

      $this->end_controls_section();


















	}


   protected function render() {
      $settings = $this->get_settings_for_display();

      $tab_buttons_html = '';
      $tabs_content_html = '';
      $tab_index = 1;

      foreach ( $settings['tabs'] as $tab ) {
         // Check tab visibility condition
         $condition = $this->check_condition( $tab );
         if ( ! $condition ) {
            continue;
         }

         $active_class = $tab_index === 1 ? ' active' : ''; // Add 'active' class to the first tab
         $tab_index ++;

         // Render tab buttons
         ob_start();
            echo '<div class="dd-tab-button' . $active_class . '">';
               echo $tab['title'];
            echo '</div>';
         $tab_buttons_html .= ob_get_clean();

         // Render tabs content
         ob_start();
            echo '<div class="dd-tab-content' . $active_class . '">';
               switch ( $tab['content_type'] ) {
                  case 'text':
                     echo $tab['content'];
                     break;

                  case 'template':
                     if ( 'publish' !== get_post_status( $tab['template_id'] ) ) {
                        return;
                     }

                     echo '<div class="elementor-template">';
                        echo ElementorPro\Plugin::elementor()->frontend->get_builder_content_for_display( $tab['template_id'] );
                     echo '</div>';
                     break;
               }
            echo '</div>';
         $tabs_content_html .= ob_get_clean();


      }

      // Print tabs
      echo '<div class="dd-tab-buttons">' . $tab_buttons_html . '</div>';
      echo '<div class="dd-tabs-content">' . $tabs_content_html . '</div>';
   }


   private function check_condition( $tab ) {
      if ( $tab['cv_popover'] != 'yes' ) {
         return true;
      }

      // Render all tabs in editor
      if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
         return true;
      }

      $condition = true;

      switch ( $tab['cv_condition'] ) {
         case 'post_meta':
            if ( $tab['cv_meta_name'] ) {
               $meta_field_value = get_post_meta( get_the_ID(), $tab['cv_meta_name'], true );
            
               switch ( $tab['cv_operator'] ) {
                  case 'equal':
                     if ( $tab['cv_meta_value'] == $meta_field_value ) {
                        $condition = true;
                     } else {
                        $condition = false;
                     }
                     break;
                  
                  case 'not_equal':
                     if ( $tab['cv_meta_value'] != $meta_field_value ) {
                        $condition = true;
                     } else {
                        $condition = false;
                     }
                     break;
                  
                  case 'empty':
                     if ( ! $meta_field_value || empty( $meta_field_value ) ) {
                        $condition = true;
                     } else {
                        $condition = false;
                     }
                     break;
                  
                  case 'not_empty':
                     if ( $meta_field_value || ! empty( $meta_field_value ) ) {
                        $condition = true;
                     } else {
                        $condition = false;
                     }
                     break;
               }
            }

            break; // cv_condition
      }


      if ( $condition && $tab['cv_action'] == 'show' ) {
         return true;
      } elseif ( $condition && $tab['cv_action'] == 'hide' ) {
         return false;
      }


      if ( ! $condition && $tab['cv_action'] == 'show' ) {
         return false;
      } elseif ( ! $condition && $tab['cv_action'] == 'hide' ) {
         return true;
      }


      return $condition;
   }
  

	public function render_plain_content() {}

}