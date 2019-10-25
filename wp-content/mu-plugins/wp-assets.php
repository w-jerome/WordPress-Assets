<?php
/**
 * Plugin Name: WP Assets
 * Description: Easy way to add assets css and js
 * Author: Jérôme Wohlschlegel <jerome.wohlschlegel@gmail.com>
 * Version: 1.0.0
 * Author URI: https://www.w-jerome.fr/
 * License: MIT
 *
 * @package WP Assets
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'H4ck3r ?' );
}

/**
 * WPAssets
 *
 * @category Class
 */
class WPAssets {

    /**
     * Singleton instance
     *
     * @var $instance
     */
    private static $instance = null;

    /**
     * Registered css
     *
     * @var array $registered_css
     */
    private $registered_css = array();

    /**
     * Registered js
     *
     * @var array $registered_js
     */
    private $registered_js = array();

    /**
     * Enqueued css
     *
     * @var array $enqueued_css
     */
    private $enqueued_css = array();

    /**
     * Enqueued js
     *
     * @var array $enqueued_js
     */
    private $enqueued_js = array();

    /**
     * __construct
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_register_assets' ) );
    }

    /**
     * Get instance
     *
     * @return $instance
     */
    public static function getInstance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new WPAssets();
        }

        return self::$instance;
    }

    /**
     * Get asset by type
     */
    private function get_asset( string $type = '', string $asset_name = '' ) {
        if ( ! in_array( $type, array( 'css', 'js' ) ) || empty( $asset_name ) ) {
            return null;
        }

        $registered_type = ( 'css' === $type ) ? $this->registered_css : $this->registered_js;

        foreach ( $registered_type as &$asset ) {
            if ( $asset_name === $asset['name'] ) {
                return $asset;
            }
        }

        return null;
    }

    /**
     * Register asset
     */
    private function register_asset( string $type = '', array $asset = array() ) {
        if ( ! in_array( $type, array( 'css', 'js' ) ) || empty( $asset['name'] ) || empty( $asset['url'] ) ) {
            return null;
        }

        $asset['dependencies'] = ( ! empty( $asset['dependencies'] ) ) ? $asset['dependencies'] : array();
        $asset['version']      = ( ! empty( $asset['version'] ) ) ? $asset['version'] : null;
        $asset['enqueue']      = ( is_bool( $asset['enqueue'] ) ) ? $asset['enqueue'] : false;

        if ( 'css' === $type ) {
            $asset['media'] = ( ! empty( $asset['media'] ) ) ? $asset['media'] : 'all';
        }

        if ( 'js' === $type ) {
            $asset['footer'] = ( is_bool( $asset['footer'] ) ) ? $asset['footer'] : true;
        }

        $registered_type = ( 'css' === $type ) ? 'registered_css' : 'registered_js';
        $this->$registered_type[] = $asset;

        if ( $asset['enqueue'] ) {
            $enqueued_type = ( 'css' === $type ) ? 'enqueued_css' : 'enqueued_js';
            $this->$enqueued_type[] = $asset;
        }

        return $asset;
    }

    /**
     * Register css alias
     */
    public function register_css( array $asset = array() ) {
        return $this->register_asset( 'css', $asset );
    }

    /**
     * Register js alias
     */
    public function register_js( array $asset = array() ) {
        return $this->register_asset( 'js', $asset );
    }

    /**
     * Register group alias
     */
    public function register_group( array $group = array() ) {
        if ( empty( $group['name'] ) ) {
            return null;
        }

        // Set default group values
        $group['css']          = ( ! empty( $group['css'] ) ) ? $group['css'] : array();
        $group['js']           = ( ! empty( $group['js'] ) ) ? $group['js'] : array();
        $group['dependencies'] = ( ! empty( $group['dependencies'] ) ) ? $group['dependencies'] : array();
        $group['version']      = ( ! empty( $group['version'] ) ) ? $group['version'] : null;
        $group['enqueue']      = ( is_bool( $group['enqueue'] ) ) ? $group['enqueue'] : false;

        // Set assets name
        $group['css']['name'] = $group['name'];
        $group['js']['name']  = $group['name'];

        // Set assets dependencies
        $group['css']['dependencies'] = ( ! empty( $group['css']['dependencies'] ) ) ? $group['css']['dependencies'] : array();
        $group['js']['dependencies']  = ( ! empty( $group['js']['dependencies'] ) ) ? $group['js']['dependencies'] : array();

        $group['css']['dependencies'] = array_values( array_unique( array_merge( $group['css']['dependencies'], $group['dependencies'] ), SORT_REGULAR ) );
        $group['js']['dependencies']  = array_values( array_unique( array_merge( $group['js']['dependencies'], $group['dependencies'] ), SORT_REGULAR ) );

        // Set assets verion
        $group['css']['version'] = $group['version'];
        $group['js']['version']  = $group['version'];

        // Set assets enqueue
        $group['css']['enqueue'] = $group['enqueue'];
        $group['js']['enqueue']  = $group['enqueue'];

        $this->register_asset( 'css', $group['css'] );
        $this->register_asset( 'js', $group['js'] );

        return $group;
    }

    /**
     * Enqueue asset
     */
    private function enqueue_asset( string $type = '', $asset = array() ) {
        if ( ! in_array( $type, array('css', 'js') ) || empty( $asset ) ) {
            return null;
        }

        if ( is_string( $asset ) ) {
            $asset = array(
                'name' => $asset,
            );
        }

        if ( empty( $asset['name'] ) ) {
            return null;
        }

        $is_registered = false;

        if ( empty( $asset['url'] ) ) {
            $asset = &$this->get_asset( $type, $asset['name'] );

            if ( empty( $asset ) ) {
                return null;
            }

            $is_registered = true;
        }

        $asset['enqueue'] = true;

        if ( ! $is_registered ) {
            $registered_type          = ( 'css' === $type ) ? 'registered_css' : 'registered_js';
            $this->$registered_type[] = $asset;
        }

        $enqueued_type          = ( 'css' === $type ) ? 'enqueued_css' : 'enqueued_js';
        $this->$enqueued_type[] = $asset;

        return $asset;
    }

    /**
     * Enqueue asset alias css
     */
    public function enqueue_css( $asset = array() ) {
        return $this->enqueue_asset( 'css', $asset );
    }

    /**
     * Enqueue asset alias js
     */
    public function enqueue_js( $asset = array() ) {
        return $this->enqueue_asset( 'js', $asset );
    }

    /**
     * Enqueue asset alias group
     */
    public function enqueue_group( $group = array() ) {
        if ( empty( $group ) ) {
            return null;
        }

        if ( is_string( $group ) ) {
            $group = array(
                'name' => $group,
            );
        }

        if ( empty( $group['name'] ) ) {
            return null;
        }

        if ( ! empty( $group['css'] ) && ! empty( $group['css']['url'] ) && ! empty( $group['js'] ) && ! empty( $group['js']['url'] ) ) {
            $group['enqueue'] = true;
            $this->register_group( $group );

            return $group;
        }

        $asset_css = $this->enqueue_asset( 'css', $group['name'] );
        $asset_js  = $this->enqueue_asset( 'js', $group['name'] );

        $asset_css = ( ! empty( $asset_css ) ) ? $asset_css : array();
        $asset_js  = ( ! empty( $asset_js ) ) ? $asset_js : array();

        unset( $asset_css['name'] );
        unset( $asset_js['name'] );

        $group['css'] = $asset_css;
        $group['js']  = $asset_js;

        return $group;
    }

    /**
     * Enqueue render
     */
    public function enqueue_register_assets() {
        foreach ( $this->registered_css as $asset ) {
            wp_register_style( $asset['name'], $asset['url'], $asset['dependencies'], $asset['version'], $asset['media'] );
        }

        foreach ( $this->registered_js as $asset ) {
            wp_register_script( $asset['name'], $asset['url'], $asset['dependencies'], $asset['version'], $asset['footer'] );
        }

        foreach ( $this->enqueued_css as $asset ) {
            wp_enqueue_style( $asset['name'] );
        }

        foreach ( $this->enqueued_js as $asset ) {
            wp_enqueue_script( $asset['name'] );
        }
    }
}

$wp_assets = WPAssets::getInstance();
