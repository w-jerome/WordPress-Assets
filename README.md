# WordPress Assets

WordPress Hook helper for render CSS and Javascript

## Installation

### WordPress Vanilla

Copy the structure of this repot into your WordPress. To have the following file: `/wp-content/mu-plugins/wp-assets.php`. You can also refer to the `example` folder of the repository.

## Usage

### Assets lifecycle

The life cycle of a `asset` goes through 2 states, `register` and `enqueue`:
- `register` allows us to register an asset, which allows us to display it later
- `enqueue` allows you to display a registered asset
It is best to save the assets used on the site so that you can view them when you need them.

WP Asset also allows you to display assets directly without having to register them first. It will save it before displaying it, which allows it to stay in the native WordPress hook (see advanced method).

### Register css/js assets

```php
$wp_assets = WPAssets::getInstance();

$css_url = get_template_directory_uri() . '/assets/css';
$js_url = get_template_directory_uri() . '/assets/js';

// Asset - css
$wp_assets->register_css(
    array(
        'name'         => 'styles',
        'url'          => $css_url . '/styles.css',
        'enqueue'      => true,
    )
);

// Asset - javascript
$wp_assets->register_js(
    array(
        'name'         => 'app',
        'url'          => $js_url . '/app.js',
        'enqueue'      => true,
    )
);
```

### Register group assets

```php
$wp_assets = WPAssets::getInstance();

$css_url = get_template_directory_uri() . '/assets/css';
$js_url = get_template_directory_uri() . '/assets/js';

// Group - app (main css/js)
$wp_assets->register_group(
    array(
        'name' => 'app',
        'css' => array(
            'url' => $css_url . '/app.css',
        ),
        'js' => array(
            'url' => $js_url . '/app.js',
        ),
        'enqueue' => true,
    )
);

// Group - Front page
$wp_assets->register_group(
    array(
        'name' => 'page-front-page',
        'css' => array(
            'url' => $css_url . '/pages/front-page.css',
        ),
        'js' => array(
            'url' => $js_url . '/pages/front-page.js',
        ),
        'dependencies' => array( 'app' ),
    )
);
```

### Enqueue css/js/group assets (already registered)

```php
$wp_assets = WPAssets::getInstance();

$wp_assets->enqueue_css( 'styles' );
$wp_assets->enqueue_js( 'app' );
$wp_assets->enqueue_group( 'page-front-page' );
```

### Enqueue advanced css/js assets (not registered)

With the advanced `enqueue` function, it is not necessary to add the `enqueue` property to the asset.

```php
$wp_assets = WPAssets::getInstance();

$css_url = get_template_directory_uri() . '/assets/css';
$js_url = get_template_directory_uri() . '/assets/js';

$wp_assets->enqueue_css(
    array(
        'name' => 'components-slider',
        'url' => $css_url . '/components/slider.css',
    )
);

$wp_assets->enqueue_js(
    array(
        'name' => 'components-go-to',
        'url' => $css_url . '/components/go-to.js',
    )
);
```

### Enqueue advanced group assets (not registered)

With the advanced `enqueue` function, it is not necessary to add the `enqueue` property to the asset.

```php
$wp_assets = WPAssets::getInstance();

$css_url = get_template_directory_uri() . '/assets/css';
$js_url = get_template_directory_uri() . '/assets/js';

$wp_assets->enqueue_group(
    array(
        'name' => 'components-menu',
        'css'  => array(
            'url' => $css_url . '/components/menu.css',
            'dependencies' => array( 'styles' ),
        ),
        'js'  => array(
            'url' => $js_url . '/components/menu.js',
            'dependencies' => array( 'app' ),
        ),
    );
);
```

### Asset CSS properties

```php
array(
    'name'         => 'app', // (require) asset name
    'url'          => '/app.css', // (require) asset url
    'dependencies' => array(), // (optional) Array with asset name
    'version'      => null, // (optional) string|null|boolean
    'media'        => 'all', // (optional) string
    'enqueue'      => false, // (optional) boolean
);
```

### Asset JS properties

```php
array(
    'name'         => 'app', // (require) asset name
    'url'          => 'app.js', // (require) asset url
    'dependencies' => array(), // (optional) Array with asset name
    'version'      => null, // (optional) string|null|boolean
    'footer'       => true, // (optional) boolean
    'enqueue'      => false, // (optional) boolean
);
```

### Asset Group (CSS/JS)

A group contains a `asset` css and js, which means that they have their own properties. But there are 2 nuances:

- They can each have their own dependencies, but also have common dependencies (usually used if it is for `groups`).
- The properties `version` and `enqueue` are to be linked to the group and not to `assets` css and js.

```php
array(
    'name' => 'front-page',  // (require) asset name
    'css'  => array( // (optional) asset css properties
        'url'          => 'front-page.css', // (require) asset url
        'dependencies' => array(), // (optional) Array with css asset name
        'media'        => 'all', // (optional) string
        'enqueue'      => false, // (optional) boolean
    ),
    'js'  => array( // (optional) asset css properties
        'url'          => 'front-page.js', // (require) asset url
        'dependencies' => array(), // (optional) Array with js asset name
        'footer'       => true, // (optional) boolean
    ),
    'dependencies' => array(), // (optional) Array with css/js asset name
    'version'      => null, // (optional) string|null|boolean
    'enqueue'      => true, // (optional) boolean
);
```

### Methods

```php
// Register
$wp_assets->register_css();
$wp_assets->register_js();
$wp_assets->register_group();

// Enqueue
$wp_assets->enqueue_css();
$wp_assets->enqueue_js();
$wp_assets->enqueue_group();
```
