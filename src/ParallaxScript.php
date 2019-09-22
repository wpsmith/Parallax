<?php
/**
 * Parallax Script Class File
 *
 * Adds Parallax support.
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\Scripts
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\WP\Parallax;

use WPS\WP\Scripts\Script;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\ParallaxScript' ) ) {
	/**
	 * Class ParallaxScript
	 * @package WPS\WP\Parallax
	 */
	class ParallaxScript extends Script {

		/**
		 * Script Handle.
		 *
		 * @var string
		 */
		public $handle = 'parallax';

		/**
		 * ParallaxScript constructor.
		 *
		 * @param array $args Array of script args.
		 *
		 * @throws \Exception
		 */
		protected function __construct( $args = array() ) {
			$suffix = self::get_suffix();
			$dir_url = self::get_dir_url( __DIR__ );

			$args = wp_parse_args( $args, array(
				'handle'         => $this->handle,
				'deps'           => array( 'jquery' ),
				'src'            => "{$dir_url}assets/js/jquery.parallax{$suffix}.js",
				'file'           => trailingslashit( __DIR__ ) . "assets/js/jquery.parallax{$suffix}.js",
			) );

			parent::__construct( $args );
		}
	}
}
