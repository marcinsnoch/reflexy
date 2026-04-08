<?php
/**
 * Plugin Name: Typex Reflexy.
 * Description: Wtyczka do publikacji Reflexów.
 * Version: 2.0
 * Author: Marcin Snoch
 * Author URI: https://gravatar.com/marcinmsxtech
 */

// Create recipes CPT
function reflexy_post_type()
{
    register_post_type(
        'reflexy',
        [
            'labels' => [
                'name' => __('REFLEXY'),
                'singular_name' => __('REFLEX'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'reflexy'],
            'menu_position' => 5,
            'menu_icon' => 'dashicons-smiley',
        ]
    );
}

// Add reflexy categories taxonomy
function create_reflexy_taxonomy()
{
    register_taxonomy('reflexy_category', 'reflexy', [
        'hierarchical' => false,
        'labels' => [
            'name' => _x('Kategorie', 'taxonomy general name'),
            'singular_name' => _x('Kategoria', 'taxonomy singular name'),
            'menu_name' => __('Kategorie'),
            'all_items' => __('Wszystkie'),
            'edit_item' => __('Edytuj'),
            'update_item' => __('Aktualizuj'),
            'add_new_item' => __('Dodaj nową'),
            'new_item_name' => __('Nowa'),
        ],
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);
}

add_action('init', 'reflexy_post_type');
add_action('init', 'create_reflexy_taxonomy', 0);

// Activation hook
function reflexy_activate()
{
    reflexy_post_type();
    create_reflexy_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'reflexy_activate');

// Uninstall hook
function reflexy_uninstall()
{
    flush_rewrite_rules();
}
register_uninstall_hook(__FILE__, 'reflexy_uninstall');

function custom_template_include($template)
{
    global $wp_query;
    if (is_archive() && isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'reflexy') {
        $new_template = dirname(__FILE__).'/templates/archive-reflexy.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }

    if (is_singular('reflexy')) {
        $new_template = dirname(__FILE__).'/templates/single-reflexy.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }

    return $template;
}
add_filter('template_include', 'custom_template_include', 99);

function custom_posts_per_page($query)
{
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    //    if ( is_home() ) {
    //    }
    $query->set('posts_per_page', intval(get_option('reflexy_archive_posts_per_page', 10)));
}
add_action('pre_get_posts', 'custom_posts_per_page');

function add_plugin_styles()
{
    wp_enqueue_style('typex-reflexy', plugins_url('/typex-reflexy.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'add_plugin_styles');

// Add admin menu under Reflexy
function reflexy_add_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=reflexy',
        'Ustawienia Reflexy',
        'Ustawienia',
        'manage_options',
        'reflexy-settings',
        'reflexy_settings_page'
    );
}
add_action('admin_menu', 'reflexy_add_admin_menu');

// Register settings
function reflexy_register_settings() {
    add_settings_section(
        'reflexy_front_page',
        'Front Page Reflex',
        '__return_empty_string',
        'reflexy_settings'
    );

    add_settings_field(
        'reflexy_slice_type',
        'Klasa',
        'reflexy_slice_type_callback',
        'reflexy_settings',
        'reflexy_front_page'
    );

    add_settings_field(
        'reflexy_posts_per_page',
        'Liczba postów',
        'reflexy_posts_per_page_callback',
        'reflexy_settings',
        'reflexy_front_page'
    );

    add_settings_section(
        'reflexy_page',
        'Page Reflexy',
        '__return_empty_string',
        'reflexy_settings'
    );

    add_settings_field(
        'reflexy_archive_posts_per_page',
        'Liczba postów na stronie',
        'reflexy_archive_posts_per_page_callback',
        'reflexy_settings',
        'reflexy_page'
    );

    register_setting('reflexy_settings', 'reflexy_slice_type');
    register_setting('reflexy_settings', 'reflexy_posts_per_page');
    register_setting('reflexy_settings', 'reflexy_archive_posts_per_page');
}
add_action('admin_init', 'reflexy_register_settings');

// Settings field callbacks
function reflexy_slice_type_callback() {
    $value = get_option('reflexy_slice_type', 'slice type2');
    echo '<input type="text" name="reflexy_slice_type" value="' . esc_attr($value) . '" placeholder="slice type2" />';
    echo '<p class="description">Wpisz klasę CSS dla sekcji Reflexy na stronie głównej.</p>';
}

function reflexy_posts_per_page_callback() {
    $value = get_option('reflexy_posts_per_page', '10');
    echo '<input type="number" name="reflexy_posts_per_page" value="' . esc_attr($value) . '" min="1" max="50" />';
    echo '<p class="description">Liczba Reflexów wyświetlanych przez shortcode [show_reflexy].</p>';
}

function reflexy_archive_posts_per_page_callback() {
    $value = get_option('reflexy_archive_posts_per_page', '10');
    echo '<input type="number" name="reflexy_archive_posts_per_page" value="' . esc_attr($value) . '" min="1" max="100" />';
    echo '<p class="description">Liczba Reflexów wyświetlanych na stronie archiwum /reflexy/.</p>';
}

// Add settings link on plugins page
function reflexy_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('edit.php?post_type=reflexy&page=reflexy-settings') . '">' . __('Ustawienia', 'typex-reflexy') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'reflexy_plugin_action_links');

// Auto-update functionality
function reflexy_check_for_updates($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $plugin_slug = plugin_basename(__FILE__);
    $current_version = $transient->checked[$plugin_slug] ?? '1.0';

    // Check GitHub API for latest release
    $response = wp_remote_get('https://api.github.com/repos/marcinsnoch/reflexy/releases/latest');
    if (is_wp_error($response)) {
        return $transient;
    }

    $release = json_decode(wp_remote_retrieve_body($response), true);
    if (!$release || !isset($release['tag_name'])) {
        return $transient;
    }

    $latest_version = ltrim($release['tag_name'], 'v');
    if (version_compare($latest_version, $current_version, '>')) {
        $transient->response[$plugin_slug] = (object) [
            'slug' => 'reflexy',
            'new_version' => $latest_version,
            'url' => 'https://github.com/marcinsnoch/reflexy',
            'package' => $release['zipball_url'] ?? $release['zipball_url'],
            'tested' => '6.9',
            'requires' => '5.0',
            'requires_php' => '7.4'
        ];
    }

    return $transient;
}
add_filter('pre_set_site_transient_update_plugins', 'reflexy_check_for_updates');

function reflexy_plugin_info($false, $action, $response) {
    if ($action !== 'plugin_information' || $response->slug !== 'reflexy') {
        return $false;
    }

    $response = wp_remote_get('https://api.github.com/repos/marcinsnoch/reflexy/releases/latest');
    if (is_wp_error($response)) {
        return $false;
    }

    $release = json_decode(wp_remote_retrieve_body($response), true);
    if (!$release) {
        return $false;
    }

    return (object) [
        'name' => 'Reflexy',
        'slug' => 'reflexy',
        'version' => ltrim($release['tag_name'] ?? '1.0', 'v'),
        'author' => 'Marcin Snoch',
        'author_profile' => 'https://gravatar.com/marcinmsxtech',
        'contributors' => ['marcinsnoch' => 'https://github.com/marcinsnoch'],
        'requires' => '5.0',
        'tested' => '6.9',
        'requires_php' => '7.4',
        'compatibility' => [],
        'rating' => 100,
        'ratings' => ['5' => 1],
        'num_ratings' => 1,
        'support_url' => 'https://github.com/marcinsnoch/reflexy/issues',
        'homepage' => 'https://github.com/marcinsnoch/reflexy',
        'download_link' => $release['zipball_url'] ?? '',
        'sections' => [
            'description' => 'Wtyczka WordPress do publikowania Reflexów (refleksji, przemyśleń) dla strony Typex.info.',
            'changelog' => $release['body'] ?? 'Zobacz pełne informacje o zmianach na GitHub.'
        ],
        'banners' => [
            'low' => 'https://raw.githubusercontent.com/marcinsnoch/reflexy/main/banner-772x250.png',
            'high' => 'https://raw.githubusercontent.com/marcinsnoch/reflexy/main/banner-1544x500.png'
        ],
        'icons' => [
            '1x' => 'https://raw.githubusercontent.com/marcinsnoch/reflexy/main/icon-128x128.png',
            '2x' => 'https://raw.githubusercontent.com/marcinsnoch/reflexy/main/icon-256x256.png'
        ],
        'last_updated' => $release['published_at'] ?? current_time('mysql'),
        'added' => '2024-01-01',
        'active_installs' => 10
    ];
}
add_filter('plugins_api', 'reflexy_plugin_info', 20, 3);

// Settings page callback
function reflexy_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Ustawienia Reflexy', 'typex-reflexy'); ?></h1>
        <p><strong>Dane autora:</strong> Marcin Snoch | <a href="https://gravatar.com/marcinmsxtech" target="_blank">https://gravatar.com/marcinmsxtech</a></p>
        <p><strong>Instrukcja:</strong> Aby wyświetlić Reflexy na stronie, użyj shortcode: <code>[show_reflexy]</code></p>
        <form method="post" action="options.php">
            <?php
            settings_fields('reflexy_settings');
            do_settings_sections('reflexy_settings');
            ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function my_shortcode_function($atts)
{
    // Ustawienia shortcode
    $atts = shortcode_atts([
        'posts_per_page' => get_option('reflexy_posts_per_page', '10'), // ilość wpisów na stronie
    ], $atts);

    // Query do pobrania wpisów
    $args = [
        'post_type' => 'reflexy',
        'posts_per_page' => intval($atts['posts_per_page']),
    ];

    // Pobieramy wpisy
    $custom_query = new WP_Query($args);

    // Wczytujemy szablon
    ob_start();
    include(plugin_dir_path(__FILE__) . 'templates/front-page.php');
    $output = ob_get_contents();
    ob_end_clean();

    // Zwracamy wynik
    return $output;
}
add_shortcode('show_reflexy', 'my_shortcode_function');

function custom_plugin_title($title)
{
    if (is_archive() && 'reflexy' == get_post_type()) {
        $custom_title = 'REFLEXY';
        $title = $custom_title . ' &#8211; ' . get_bloginfo('name');
    }

    return $title;
}
add_filter('pre_get_document_title', 'custom_plugin_title');
