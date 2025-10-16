<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DD Parallax Background functionality for Elementor containers
 */
class DD_Container_Background_Effects {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'elementor/element/container/section_background_overlay/after_section_end', array( $this, 'add_controls' ), 10, 2 );
		add_action( 'elementor/frontend/container/before_render', array( $this, 'before_container_render' ), 10, 1 );
		add_action( "elementor/container/print_template", array( $this, 'render_container_template' ), 10, 2 );
	}

	/**
	 * Add DD Paralax Background controls to container
	 *
	 * @param \Elementor\Controls_Stack $element
	 * @param array $args
	 * @return void
	 */
	public function add_controls( $element, $args ) {
		$element->start_controls_section(
			'section_dd_container_background_effects',
			[
				'label' => esc_html__( 'DD Background Effects', 'drive-design-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$element->add_control(
				'dd_background_effect',
				[
					'label' => esc_html__( 'Effect', 'drive-design-widgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'' => esc_html__( 'None', 'drive-design-widgets' ),
						'parallax_gallery' => esc_html__( 'Parallax Gallery', 'drive-design-widgets' ),
					],
					'default' => '',
				]
			);

			$this->get_parallax_gallery_controls( $element );

		$element->end_controls_section();
	}

	/**
	 * Get parallax gallery controls
	 *
	 * @param \Elementor\Controls_Stack $element
	 * @return void
	 */
	private function get_parallax_gallery_controls( $element ) {
		$element->start_controls_tabs( 'dd_paralax_tabs', [
			'condition' => [
				'dd_background_effect' => 'parallax_gallery',
			],
		] );

			// Content Tab
			$element->start_controls_tab(
				'dd_paralax_gallery_tab',
				[
					'label' => esc_html__( 'Content', 'drive-design-widgets' ),
				]
			);

				$element->add_control(
					'dd_paralax_gallery_images',
					[
						'label' => esc_html__( 'Images', 'drive-design-widgets' ),
						'type' => \Elementor\Controls_Manager::GALLERY,
						// 'frontend_available' => true,
						// 'of_type' => 'slideshow',
						'show_label' => false,
					]
				);

				$element->add_control(
					'dd_paralax_gallery_rows',
					[
						'label' => esc_html__( 'Rows Count', 'drive-design-widgets' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 10,
						'default' => 3,
						'selectors' => [
							'{{WRAPPER}} .dd-paralax-background' => '--dd-paralax-galleryrows: {{SIZE}};',
						],
					]
				);

				$element->add_control(
					'dd_paralax_gallery_columns',
					[
						'label' => esc_html__( 'Columns Count', 'drive-design-widgets' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 30,
						'default' => 10,
						'selectors' => [
							'{{WRAPPER}} .dd-paralax-background' => '--dd-paralax-gallery-columns: {{SIZE}};',
						],
					]
				);

			$element->end_controls_tab();

			// Style Tab
			$element->start_controls_tab(
				'dd_paralax_gallery_style_tab',
				[
					'label' => esc_html__( 'Style', 'drive-design-widgets' ),
				]
			);

				$element->add_control(
					'dd_paralax_gallery_row_gap',
					[
						'label' => esc_html__( 'Row Gap', 'drive-design-widgets' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
							'custom' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .dd-paralax-background' => '--dd-paralax-gallery-row-gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$element->add_control(
					'dd_paralax_gallery_column_gap',
					[
						'label' => esc_html__( 'Column Gap', 'drive-design-widgets' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .dd-paralax-background' => '--dd-paralax-gallery-column-gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

			$element->end_controls_tab();

		$element->end_controls_tabs();
	}

	/**
	 * Before container render
	 *
	 * @param \Elementor\Element_Base $element
	 * @return void
	 */
	public function before_container_render( $element ) {
		$settings = $element->get_settings_for_display();
		if ( 'parallax_gallery' !== $settings['dd_background_effect'] ) {
			return;
		}

		// Output the div with a unique ID for this container
		$container_id = $element->get_id();
		echo '<div class="dd-paralax-background" data-container-id="' . esc_attr( $container_id ) . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>';

		if ( 'boxed' === $settings['content_width'] ) {
			// Add JavaScript to move the div inside e-con-inner after the container is rendered
			?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const containerId = '<?php echo esc_js( $container_id ); ?>';
					const paralaxDiv = document.querySelector('.dd-paralax-background[data-container-id="' + containerId + '"]');
					const container = document.querySelector('.elementor-element-' + containerId);
					
					if (paralaxDiv && container) {
						const conInner = container.querySelector('.e-con-inner');
						if (conInner) {
							// Move the paralax div as the first child of e-con-inner
							conInner.insertBefore(paralaxDiv, conInner.firstChild);
						}
					}
				});
			</script>
			<?php
		} else {
			// Add JavaScript to move the div inside the container after the container is rendered
			?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const containerId = '<?php echo esc_js( $container_id ); ?>';
					const paralaxDiv = document.querySelector('.dd-paralax-background[data-container-id="' + containerId + '"]');
					const container = document.querySelector('.elementor-element-' + containerId);
					
					if (paralaxDiv && container) {
						container.prepend(paralaxDiv);
					}
				});
			</script>
			<?php
		}
	}

	/**
	 * Render container template
	 *
	 * @param string $template
	 * @param \Elementor\Element_Base $element
	 * @return string
	 */
	public function render_container_template( $template, $element ) {
		ob_start();
		?>
		<# 
		
		// console.log('settings', settings);

		if ( 'parallax_gallery' === settings.dd_background_effect ) {

			#>
			<div class="dd-paralax-background" data-container-id="{{ view.container.id }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
			<#  

			var paralaxImages = settings.dd_paralax_gallery_images;
			console.log('paralaxImages', paralaxImages);


			if ( 'boxed' === settings.content_width ) {

				// console.log('view', view);

				setTimeout(function() {
					var paralaxDiv = view.$el.find('.dd-paralax-background[data-container-id="' + view.container.id + '"]');
					view.$childViewContainer.prepend(paralaxDiv);
				})
			} 
		}
		#>
		<?php
		$template_injection = ob_get_clean();

		return $template_injection . $template;
	}
}
