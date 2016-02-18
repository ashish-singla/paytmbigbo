<?php
/**
 * Main access to the Code-Library.
 * Access via function `lib2()`.
 *
 * Inspired by Jigsaw plugin by Jared Novack (http://jigsaw.upstatement.com/)
 *
 * @since  1.0.0
 */
class TheLib_2_0_3_Core extends TheLib_2_0_3 {

	/**
	 * Interface to the array component.
	 *
	 * @since 1.1.5
	 * @api
	 *
	 * @var   TheLib_Array
	 */
	public $array = null;

	/**
	 * Interface to the Debug component.
	 *
	 * @since 1.1.0
	 * @api
	 *
	 * @var   TheLib_Debug
	 */
	public $debug = null;

	/**
	 * Interface to the HTML component.
	 *
	 * @since 1.1.0
	 * @api
	 *
	 * @var   TheLib_Html
	 */
	public $html = null;

	/**
	 * Interface to the Net component.
	 *
	 * @since 1.1.0
	 * @api
	 *
	 * @var   TheLib_Net
	 */
	public $net = null;

	/**
	 * Interface to the session component.
	 *
	 * @since 1.1.5
	 * @api
	 *
	 * @var   TheLib_Session
	 */
	public $session = null;

	/**
	 * Interface to the updates component.
	 *
	 * @since 1.1.5
	 * @api
	 *
	 * @var   TheLib_Updates
	 */
	public $updates = null;

	/**
	 * Interface to the UI component.
	 *
	 * @since 1.1.5
	 * @api
	 *
	 * @var   TheLib_Ui
	 */
	public $ui = null;

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 * @internal
	 */
	public function __construct() {
		parent::__construct();

		self::$core = $this;

		// A List of all components.
		$components = array(
			'array',
			'debug',
			'html',
			'net',
			'session',
			'updates',
			'ui',
		);

		// Create instances of each component.
		foreach ( $components as $component ) {
			if ( ! property_exists( $this, $component ) ) { continue; }

			$class_name = str_replace( 'Core', ucfirst( $component ), __CLASS__ );
			$this->$component = new $class_name();
		}
	}

	/**
	 * Short way to load the textdomain of a plugin.
	 *
	 * @since  1.0.0
	 * @api
	 *
	 * @param  string $domain Translations will be mapped to this domain.
	 * @param  string $rel_dir Path to the dictionary folder; relative to WP_PLUGIN_DIR.
	 */
	public function translate_plugin( $domain, $rel_dir ) {
		if ( did_action( 'plugins_loaded' ) && 'plugins_loaded' != current_filter() ) {
			_doing_it_wrong(
				'translate_plugin',
				'This function must be called before/inside the hook "plugins_loaded".',
				'2.0.3'
			);
		}

		$this->_add( 'textdomain', compact( 'domain', 'rel_dir' ) );

		$this->add_action( 'plugins_loaded', '_translate_plugin_callback' );
	}

	/**
	 * Create function callback for load textdomain (for PHP <5.3 only)
	 *
	 * @since  1.0.1
	 * @internal
	 */
	public function _translate_plugin_callback() {
		$items = $this->_get( 'textdomain' );
		foreach ( $items as $item ) {
			extract( $item ); // domain, rel_dir

			$loaded = load_plugin_textdomain( $domain, false, $rel_dir );
		}
	}

	/**
	 * Checks if the provided value evaluates to a boolean TRUE.
	 *
	 * Following values are considered true:
	 *  - Boolean: true
	 *  - Number: anything except 0
	 *  - Strings: true, yes, on (case insensitive)
	 *
	 * @since  1.1.0
	 * @api
	 *
	 * @param  mixed $value A value that will be evaluated into a boolean.
	 * @return bool True if the specified $value evaluated to true.
	 */
	public function is_true( $value ) {
		if ( false === $value || null === $value || '' === $value ) {
			return false;
		} elseif ( true === $value ) {
			return true;
		} elseif ( is_numeric( $value ) ) {
			$value = intval( $value );
			return $value != 0;
		} elseif ( is_string( $value ) ) {
			$value = strtolower( trim( $value ) );
			return in_array(
				$value,
				array( 'true', 'yes', 'on', '1' )
			);
		}
		return false;
	}

	/**
	 * Converts a number from any base to another base.
	 * The from/to base values can even be non-numeric values.
	 *
	 * @since  2.0.2
	 * @api
	 *
	 * @param  string $number A number in the base_from base.
	 * @param  string $base_from List of characters
	 *         E.g. 0123456789 to convert from decimal.
	 * @param  string $base_to List of characters to use as destination base.
	 *         E.g. 0123456789ABCDEF to convert to hexadecimal.
	 * @return string The converted number
	 */
	public function convert( $number, $base_from = '0123456789', $base_to = '0123456789ABCDEF' ) {
		if ( $base_from == $base_to ) {
			// No conversion needed.
			return $number;
		}

		$retval = '';
		$number_len = strlen( $number );

		if ( '0123456789' == $base_to ) {
			// Convert a value to normal decimal base.

			$arr_base_from = str_split( $base_from, 1 );
			$arr_number = str_split( $number, 1 );
			$base_from_len = strlen( $base_from );
			$retval = 0;
			for ( $i = 1; $i <= $number_len; $i += 1 ) {
				$retval = bcadd(
					$retval,
					bcmul(
						array_search( $arr_number[$i - 1], $arr_base_from ),
						bcpow( $base_from_len, $number_len - $i )
					)
				);
			}
		} else {
			// Convert a value to a NON-decimal base.

			if ( '0123456789' != $base_from ) {
				// Base value is non-decimal, convert it to decimal first.
				$base10 = $this->convert( $number, $base_from, '0123456789' );
			} else {
				// Base value is decimal.
				$base10 = $number;
			}

			$arr_base_to = str_split( $base_to, 1 );
			$base_to_len = strlen( $base_to );
			if ( $base10 < strlen( $base_to ) ) {
				$retval = $arr_base_to[$base10];
			} else {
				while ( 0 != $base10 ) {
					$retval = $arr_base_to[bcmod( $base10, $base_to_len )] . $retval;
					$base10 = bcdiv( $base10, $base_to_len, 0 );
				}
			}
		}

		return $retval;
	}

};
