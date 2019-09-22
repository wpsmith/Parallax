<?php
/**
 * Parallax Class
 *
 * Sets up Parallax within WordPress.
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\WP\Parallax
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\WP\Parallax;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Customizer' ) ) {
	/**
	 * Class Customizer
	 * @package WPS\WP\Parallax
	 */
	class Customizer {

		/**
		 * Section ID.
		 *
		 * @var string
		 */
		private $section_id;

		/**
		 * Setting prefix.
		 *
		 * @var string
		 */
		private $setting_prefix;

		/**
		 * Sections.
		 *
		 * @var array
		 */
		private $sections;

		/**
		 * Parallax constructor.
		 *
		 * @param string $section_id Section ID.
		 * @param array $sections Array of sections.
		 */
		public function __construct( $section_id, $sections, $file = '' ) {
			if ( ! genesis_is_customizer() ) {
				return;
			}

			usort( $sections, function ( $a, $b ) {
				return strcmp( $a['id'], $b['id'] );
			} );

			$this->section_id     = self::sanitize_name( $section_id );
			$this->sections       = $sections;
			$this->setting_prefix = $this->section_id . '_';
			add_action( 'customize_register', array( $this, 'register_bg_sections' ) );

			ParallaxScript::get_instance();
		}

		/**
		 * Sanitizes the name.
		 *
		 * @param string $name Name.
		 *
		 * @return string Sanitized name.
		 */
		public static function sanitize_name( $name ) {

			return str_replace( '-', '_', sanitize_title_with_dashes( $name ) );

		}

		/**
		 * Registers a background sections.
		 *
		 * @param \WP_Customize_Manager $wp_customize WP Customerizer.
		 * @param array $section A section array.
		 */
		public function register_bg_section( $wp_customize, $section ) {
			$id      = self::sanitize_name( $section['id'] );
			$setting = $this->setting_prefix . 'setting_' . $id;

			// Create section.
			$section_id = $this->section_id . '_' . $id;
			$wp_customize->add_section(
				$section_id,
				array(
					'title' => __( 'Background for ' . $section['name'], 'wps' ),
					'panel' => $this->section_id,
				)
			);

			/** COLOR SETTING & CONTROL */

			// Create color setting.
			$wp_customize->add_setting(
				$setting . '_color',
				array(
					'default'           => '',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			// Background Color Control.
			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					$id . '_color',
					array(
						'label'       => __( 'Background Color', 'wps' ),
						'settings'    => $setting . '_color',
						'section'     => $section_id,
						'description' => sprintf(
							'<p>%s:</p><code>WPS\WP\Parallax\get_bg_open( "widget-id", "color");<br/>/* DO SOMETHING */ <br/>WPS\WP\Parallax\get_bg_close();</code>',
							__( "To use on the frontend", 'wps' )
						),
					)
				)
			);

			/** IMAGE SETTING & CONTROL */

			// Create image setting.
			$wp_customize->add_setting(
				$setting . '_image',
				array(
					'default'           => '',
					'sanitize_callback' => __CLASS__ . '::sanitize_bgi',
				)
			);

			// Image Control.
			$wp_customize->add_control(
				new \WP_Customize_Image_Control(
					$wp_customize,
					$id . '_image',
					array(
						'label'       => __( 'Background Image', 'wps' ),
						'settings'    => $setting . '_image',
						'section'     => $section_id,
						'description' => sprintf(
							'<p>%s:</p><code>WPS\WP\Parallax\get_bg_open( "widget-id", "image");<br/>/* DO SOMETHING */ <br/>WPS\WP\Parallax\get_bg_close();</code>',
							__( "To use on the frontend", 'wps' )
						),
					)
				)
			);
		}

		/**
		 * Registers background sections.
		 *
		 * @param \WP_Customize_Manager $wp_customize WP Customerizer.
		 */
		public function register_bg_sections( $wp_customize ) {

			$wp_customize->add_panel(
				$this->section_id,
				array(
					'title'       => __( 'Home Background Settings', 'wps' ),
					'description' => '',
					'priority'    => 202,
				)
			);

			foreach ( (array) $this->sections as $section ) {

				$this->register_bg_section( $wp_customize, $section );
			}
		}

		/**
		 * Sanitizes bgi.
		 *
		 * @param string $image Image file name or path.
		 * @param \WP_Customize_Setting $settings Settings.
		 *
		 * @return mixed
		 */
		public static function sanitize_bgi( $image, $settings ) {

			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
				'bmp'          => 'image/bmp',
				'tif|tiff'     => 'image/tiff',
				'ico'          => 'image/x-icon',
			);
			$file  = wp_check_filetype( $image, $mimes );

			return ( $file['ext'] ? $image : $settings->default );

		}

	}
}
