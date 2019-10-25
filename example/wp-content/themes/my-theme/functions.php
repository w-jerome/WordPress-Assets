<?php
/**
 * My Theme
 */

function initAssets() {
	global $wp_assets;

	$assets_config_path = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'config.php';

	if ( file_exists( $assets_config_path ) ) {

		foreach ( $assets['css'] as $asset ) {
			$wp_assets->register_css( $asset );
		}

		foreach ( $assets['js'] as $asset ) {
			$wp_assets->register_js( $asset );
		}

		foreach ( $assets['group'] as $asset ) {
			$wp_assets->register_group( $asset );
		}
	}
}

initAssets();
