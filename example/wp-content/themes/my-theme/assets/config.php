<?php
$css_url = get_template_directory_uri() . '/assets/css';
$js_url  = get_template_directory_uri() . '/assets/js';

$assets = array();

$assets['css'] = array(
	array(
		'name' => 'styles',
		'url' => $css_url . '/styles.css',
		'enqueue' => true,
	),
);

$assets['js'] = array(
	array(
		'name' => 'app',
		'url' => $js_url . '/app.js',
		'enqueue' => true,
	),
);

$assets['group'] = array(
	array(
		'name' => 'page-front-page',
		'css' => array(
			'url' => $css_url . '/pages/front-page.css',
			'dependencies' => array( 'styles' ),
		),
		'js' => array(
			'url' => $js_url . '/pages/front-page.js',
			'dependencies' => array( 'app' ),
		),
		'dependencies' => array( 'components-slider' ),
	),
	array(
		'name' => 'components-slider',
		'css'  => array(
			'url' => $css_url . '/components/slider.css',
		),
		'js'   => array(
			'url' => $js_url . '/components/slider.js',
		),
	),
);
