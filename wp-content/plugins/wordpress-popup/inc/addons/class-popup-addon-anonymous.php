<?php
/*
Addon Name:  Anonymous loading method
Plugin URI:  http://premium.wpmudev.org/project/the-pop-over-plugin/
Description: Yet another loading method.
Author:      Ve (Incsub)
Author URI:  http://premium.wpmudev.org
Type:        Setting
Version:     1.0
*/

class IncPopupAddon_AnonyousLoading {

	const METHOD = 'anonymous';

	/**
	 * Initialize the addon
	 *
	 * @since  4.6
	 */
	static public function init() {
		if ( is_admin() ) {
			// Called from the PopUp Settings screen.
			add_filter(
				'popup-settings-loading-method',
				array( __CLASS__, 'settings' )
			);
		}
	}

	/**
	 * Filter that returns a modified version of the loading methods
	 * (displayed in the settings page)
	 *
	 * @since  4.6
	 * @param  array $loading_methods
	 * @return array
	 */
	static public function settings( $loading_methods ) {
		$pro_only = ' - ' . __( 'PRO Verson', PO_LANG );

		$loading_methods[] = (object) array(
			'id'    => self::METHOD,
			'label' => __( 'Anonymous Script', PO_LANG ) . $pro_only,
			'info'  => __(
				'Drastically increase the chance to bypass ad-blockers. ' .
				'Loads PopUp like WordPress AJAX, but the URL to the ' .
				'JavaScript file is masked. ', PO_LANG
			),
			'disabled' => true,
		);
		return $loading_methods;
	}
}

IncPopupAddon_AnonyousLoading::init();