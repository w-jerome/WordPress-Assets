# WordPress Assets

## Usage

### Example

```php
$wp_assets = WPAssets::getInstance();

$css_url = get_template_directory_uri() . '/assets/css';

$wp_assets->register_css(
    array(
        'name'         => 'styles',
        'url'          => $css_url . '/styles.css',
        'enqueue'      => true,
    )
);
```

### Asset CSS properties

```php
array(
    'name'         => 'app', // (require)
    'url'          => '/app.css', // (require)
    'dependencies' => array(), // (optional) Array with asset name
    'version'      => null, // (optional) string|null|boolean
    'media'        => 'all', // (optional) string
    'enqueue'      => false, // (optional) boolean
);
```

### Asset JS properties

```php
array(
    'name'         => 'app', // (require)
    'url'          => 'app.js', // (require)
    'dependencies' => array(), // (optional) Array with asset name
    'version'      => null, // (optional) string|null|boolean
    'footer'       => true, // (optional) boolean
    'enqueue'      => false, // (optional) boolean
);
```

### Asset Group (CSS/JS)

```php
array(
    'name' => 'front-page',
    'css'  => array(
        'url'          => 'front-page.css',
        'dependencies' => array(),
    ),
    'js'  => array(
        'url'          => 'front-page.js',
        'dependencies' => array(),
    ),
    'dependencies' => array(),
    'version'      => null,
    'enqueue'      => true,
);
```