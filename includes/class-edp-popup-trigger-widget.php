<?php
/**
 * Dynamic Popup Trigger Widget.
 *
 * Displays a button/link that opens Elementor popup content in a modal.
 * When placed inside a Loop Grid or Loop Carousel item, the popup content
 * is rendered with the current loop item's post context (dynamic content).
 *
 * @package Elementor_Dynamic_Popup
 */

namespace EDP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class Popup_Trigger_Widget.
 */
class Popup_Trigger_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'edp_popup_trigger';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Dynamic Popup Trigger', 'elementor-dynamic-popup' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-lightbox-expand';
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'popup', 'modal', 'loop', 'dynamic', 'lightbox' );
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'edp-frontend' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'edp-frontend' );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'elementor-dynamic-popup' ),
			)
		);

		$popups = $this->get_available_popups();
		$popup_options = array( '' => esc_html__( '— Select Popup —', 'elementor-dynamic-popup' ) );
		foreach ( $popups as $id => $title ) {
			$popup_options[ $id ] = $title;
		}

		$this->add_control(
			'popup_id',
			array(
				'label'   => esc_html__( 'Popup', 'elementor-dynamic-popup' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $popup_options,
				'default' => '',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => esc_html__( 'Button Text', 'elementor-dynamic-popup' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View Details', 'elementor-dynamic-popup' ),
				'placeholder' => esc_html__( 'Enter button text', 'elementor-dynamic-popup' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'use_link',
			array(
				'label'   => esc_html__( 'Use as link style', 'elementor-dynamic-popup' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label' => esc_html__( 'Icon', 'elementor-dynamic-popup' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'elementor-dynamic-popup' ),
					'right' => esc_html__( 'After', 'elementor-dynamic-popup' ),
				),
				'condition' => array(
					'icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'elementor-dynamic-popup' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'elementor-dynamic-popup' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'elementor-dynamic-popup' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'elementor-dynamic-popup' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default' => 'left',
			)
		);

		$this->end_controls_section();

		// Style section.
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Button', 'elementor-dynamic-popup' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'elementor-dynamic-popup' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .edp-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .edp-trigger',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal.
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'elementor-dynamic-popup' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .edp-trigger' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .edp-trigger' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .edp-trigger',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'elementor-dynamic-popup' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .edp-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'elementor-dynamic-popup' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .edp-trigger:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .edp-trigger:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'elementor-dynamic-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .edp-trigger:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get available Elementor popups.
	 *
	 * @return array Array of popup_id => title.
	 */
	private function get_available_popups() {
		$popups = array();

		$query = new \WP_Query(
			array(
				'post_type'      => 'elementor_library',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'   => '_elementor_template_type',
						'value' => 'popup',
					),
				),
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post ) {
				$popups[ $post->ID ] = $post->post_title;
			}
		}

		wp_reset_postdata();

		return $popups;
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$popup_id = (int) $settings['popup_id'];

		if ( ! $popup_id ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="edp-placeholder">' . esc_html__( 'Select a popup from the widget settings.', 'elementor-dynamic-popup' ) . '</div>';
			}
			return;
		}

		$post_id = get_the_ID();
		if ( ! $post_id ) {
			$post_id = 0;
		}

		$button_text = $settings['button_text'];
		$use_link    = ! empty( $settings['use_link'] );
		$icon        = $settings['icon'];
		$icon_align  = $settings['icon_align'];
		$align       = $settings['align'];

		$tag = $use_link ? 'a' : 'button';
		$trigger_atts = array(
			'class'               => 'edp-trigger elementor-button',
			'role'                => 'button',
			'data-edp-popup-id'   => $popup_id,
			'data-edp-post-id'    => $post_id,
			'tabindex'            => '0',
		);
		if ( $use_link ) {
			$trigger_atts['href']        = '#';
			$trigger_atts['aria-label']  = esc_attr( $button_text );
		}

		$icon_html = '';
		if ( ! empty( $icon['value'] ) ) {
			$icon_html = '<span class="edp-trigger-icon edp-icon-' . esc_attr( $icon_align ) . '">';
			\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
			$icon_html .= '</span>';
		}

		$text_html = '<span class="edp-trigger-text">' . esc_html( $button_text ) . '</span>';

		$inner = 'right' === $icon_align
			? $text_html . $icon_html
			: $icon_html . $text_html;

		$wrapper_style = '';
		if ( $align ) {
			$wrapper_style = 'text-align: ' . esc_attr( $align ) . ';';
		}

		?>
		<div class="edp-trigger-wrapper" style="<?php echo esc_attr( $wrapper_style ); ?>">
			<?php
			echo '<' . esc_html( $tag );
			foreach ( $trigger_atts as $key => $val ) {
				if ( '' !== $val ) {
					echo ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
				}
			}
			echo '>';
			echo wp_kses_post( $inner );
			echo '</' . esc_html( $tag ) . '>';

			// Output popup content (rendered with current post context).
			$this->render_popup_content( $popup_id, $post_id );
			?>
		</div>
		<?php
	}

	/**
	 * Render popup content for the current loop item.
	 *
	 * The content is rendered with the current post context, so dynamic tags
	 * (Post Title, Featured Image, etc.) resolve to the loop item's data.
	 *
	 * @param int $popup_id Popup template post ID.
	 * @param int $post_id  Post ID for context (current loop item).
	 */
	private function render_popup_content( $popup_id, $post_id ) {
		$document = \Elementor\Plugin::$instance->documents->get( $popup_id );
		if ( ! $document || ! $document->is_built_with_elementor() ) {
			return;
		}

		// Ensure correct post context for dynamic tags. When inside a Loop Grid,
		// $post is usually already set by Elementor. We only swap if it differs.
		$original_post = null;
		$current_post_id = isset( $GLOBALS['post']->ID ) ? (int) $GLOBALS['post']->ID : 0;
		if ( $post_id && $current_post_id !== (int) $post_id ) {
			$original_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
			$post_object   = get_post( $post_id );
			if ( $post_object ) {
				$GLOBALS['post'] = $post_object;
				setup_postdata( $post_object );
			}
		}

		$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $popup_id, true );

		if ( $original_post ) {
			$GLOBALS['post'] = $original_post;
			wp_reset_postdata();
		}

		if ( empty( trim( $content ) ) ) {
			return;
		}

		?>
		<div class="edp-popup-content" data-edp-popup-id="<?php echo esc_attr( $popup_id ); ?>" data-edp-post-id="<?php echo esc_attr( $post_id ); ?>" hidden>
			<div class="edp-popup-inner">
				<?php echo $content; ?>
			</div>
		</div>
		<?php
	}
}
