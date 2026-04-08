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
    if (is_archive() && 'reflexy' == get_post_type()) {
        $new_template = dirname(__FILE__) . '/templates/archive-reflexy.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }

    if (is_singular('reflexy')) {
        $new_template = dirname(__FILE__) . '/templates/single-reflexy.php';
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
    $query->set('posts_per_page', 10);
}
add_action('pre_get_posts', 'custom_posts_per_page');

function add_plugin_styles()
{
    wp_enqueue_style('typex-reflexy', plugins_url('/typex-reflexy.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'add_plugin_styles');

// Add admin menu
function reflexy_add_admin_menu() {
    add_options_page(
        'Ustawienia Reflexy',
        'Reflexy',
        'manage_options',
        'reflexy-settings',
        'reflexy_settings_page'
    );
}
add_action('admin_menu', 'reflexy_add_admin_menu');

// Register settings
function reflexy_register_settings() {
    register_setting('reflexy_settings', 'reflexy_author_name');
    register_setting('reflexy_settings', 'reflexy_author_email');
    register_setting('reflexy_settings', 'reflexy_author_url');
    register_setting('reflexy_settings', 'reflexy_slice_type');
}
add_action('admin_init', 'reflexy_register_settings');

// Settings page callback
function reflexy_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Ustawienia Reflexy', 'typex-reflexy'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('reflexy_settings');
            do_settings_sections('reflexy_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Nazwa autora', 'typex-reflexy'); ?></th>
                    <td><input type="text" name="reflexy_author_name" value="<?php echo esc_attr(get_option('reflexy_author_name', 'Marcin Snoch')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Email autora', 'typex-reflexy'); ?></th>
                    <td><input type="email" name="reflexy_author_email" value="<?php echo esc_attr(get_option('reflexy_author_email', 'msnoch@vxm.pl')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('URL autora', 'typex-reflexy'); ?></th>
                    <td><input type="url" name="reflexy_author_url" value="<?php echo esc_attr(get_option('reflexy_author_url', 'http://msnoch.vxm.pl')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Typ sekcji', 'typex-reflexy'); ?></th>
                    <td>
                        <select name="reflexy_slice_type">
                            <option value="type1" <?php selected(get_option('reflexy_slice_type', 'type2'), 'type1'); ?>>Type 1</option>
                            <option value="type2" <?php selected(get_option('reflexy_slice_type', 'type2'), 'type2'); ?>>Type 2</option>
                            <option value="type3" <?php selected(get_option('reflexy_slice_type', 'type2'), 'type3'); ?>>Type 3</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function my_shortcode_function($atts)
{
    // Ustawienia shortcode
    $atts = shortcode_atts([
        'posts_per_page' => '10', // ilość wpisów na stronie
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
