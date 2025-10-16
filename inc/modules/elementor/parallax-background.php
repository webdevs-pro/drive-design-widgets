<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DD Parallax Background functionality for Elementor containers
 */
class DD_Parallax_Background {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add DD Paralax Background controls to container
		add_action( 'elementor/element/container/section_background_overlay/after_section_end', array( $this, 'add_dd_paralax_background_controls' ), 10, 2 );

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
	public function add_dd_paralax_background_controls( $element, $args ) {
		$element->start_controls_section(
			'section_dd_paralax_background',
			[
				'label' => esc_html__( 'DD Paralax Background', 'elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'dd_paralax_enable',
			[
				'label' => esc_html__( 'Enable Paralax Effect', 'elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementor' ),
				'label_off' => esc_html__( 'No', 'elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$element->start_controls_tabs( 'dd_paralax_tabs', [
			'condition' => [
				'dd_paralax_enable' => 'yes',
			],
		] );

		// Content Tab
		$element->start_controls_tab(
			'dd_paralax_content_tab',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
			]
		);

		$element->add_control(
			'dd_paralax_images',
			[
				'label' => esc_html__( 'Images', 'elementor' ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				// 'frontend_available' => true,
				// 'of_type' => 'slideshow',
				'show_label' => false,
			]
		);

		$element->add_control(
			'dd_paralax_rows',
			[
				'label' => esc_html__( 'Rows Count', 'elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'default' => 3,
			]
		);

		$element->add_control(
			'dd_paralax_columns',
			[
				'label' => esc_html__( 'Columns Count', 'elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 30,
				'default' => 10,
			]
		);

		$element->end_controls_tab();

		// Style Tab
		$element->start_controls_tab(
			'dd_paralax_style_tab',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
			]
		);

		// Future style controls can be added here
		// For example: opacity, blend modes, animation speed, etc.

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	/**
	 * Before container render
	 *
	 * @param \Elementor\Element_Base $element
	 * @return void
	 */
	public function before_container_render( $element ) {
		$settings = $element->get_settings_for_display();
		if ( 'yes' !== $settings['dd_paralax_enable'] ) {
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

		if ( 'yes' === settings.dd_paralax_enable ) {

			#>
			<div class="dd-paralax-background" data-container-id="{{ view.container.id }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
			<#  

			var paralaxImages = settings.dd_paralax_images;
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
