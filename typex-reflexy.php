<?php
/**
 * Plugin Name: Typex Reflexy.
 * Description: Wtyczka do publikacji Reflexów.
  * Version: 0.1
 * Author: Marcin Snoch
 * Author URI: http://msnoch.vxm.pl
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
            // 'taxonomies' => array('cuisines', 'post_tag') // this is IMPORTANT
        ]
    );
}
add_action('init', 'reflexy_post_type');

// Add cuisines taxonomy
function create_reflexy_taxonomy()
{
    register_taxonomy('reflexy', 'reflexy', [
        'hierarchical' => false,
        'labels' => [
            'name' => _x('Ketegorie', 'taxonomy general name'),
            'singular_name' => _x('Kategoria', 'taxonomy singular name'),
            'menu_name' => __('Ketegorie'),
            'all_items' => __('All'),
            'edit_item' => __('Edit'),
            'update_item' => __('Update'),
            'add_new_item' => __('Add'),
            'new_item_name' => __('New'),
        ],
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ]);
}
add_action('init', 'create_reflexy_taxonomy', 0);

function custom_template_include($template)
{
    if (is_archive() && 'reflexy' == get_post_type()) {
        $new_template = dirname(__FILE__).'/templates/archive-reflexy.php';
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

function my_shortcode_function()
{
//    $args = array(
//        'post_type' => 'reflexy',
//        'posts_per_page' => 4
//    );
//    $loop = new WP_Query($args);
//    if ($loop->have_posts()) :
//        while ($loop->have_posts()) : $loop->the_post();
//            $output .= '<div class="card">';
//            $output .= '<article id="post-' . get_the_ID() . '" ' . get_post_class('') . '>';
//            $output .= '<a href="">';
//            if (has_post_thumbnail()) {
//                $output .= get_the_post_thumbnail(null, 'medium_large', array('class' => 'card-img'));
//            } else {
//                $output .= '<img width="768" height="508" src="http://127.0.0.1:8000/wp-content/uploads/2023/02/Milli-Vanilli-768x508.jpg" class="card-img wp-post-image" alt="" decoding="async" loading="lazy" srcset="http://127.0.0.1:8000/wp-content/uploads/2023/02/Milli-Vanilli-768x508.jpg 768w, http://127.0.0.1:8000/wp-content/uploads/2023/02/Milli-Vanilli-300x198.jpg 300w, http://127.0.0.1:8000/wp-content/uploads/2023/02/Milli-Vanilli-1024x677.jpg 1024w, http://127.0.0.1:8000/wp-content/uploads/2023/02/Milli-Vanilli.jpg 1200w" sizes="(max-width: 768px) 100vw, 768px">';
//            }
//            $output .='</a>';
//            $output .='<div class="card-img-overlay">';
//            $output .='<h3 class="card-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
//            $output .='<p class="card-meta">META';
//            $output .='</p>';
//            $output .= '</div>';
//            $output .= '</article>';
//            $output .= '</div>';
//        endwhile;
//        return $output;
//    endif;
//    wp_reset_postdata();
    
    // Ustawienia shortcode
    $atts = '';
    $atts = shortcode_atts( array(
        'posts_per_page' => '10', // ilość wpisów na stronie
    ), $atts );

    // Query do pobrania wpisów
    $args = array(
        'post_type' => 'reflexy',
        'posts_per_page' => $atts['posts_per_page'],
    );

    // Pobieramy wpisy
    $custom_query = new WP_Query( $args );

    // Wczytujemy szablon
    ob_start();
    include( plugin_dir_path( __FILE__ ) . 'templates/front-page.php' );
    $output = ob_get_contents();
    ob_end_clean();

    // Zwracamy wynik
    return $output;
}
add_shortcode('show_reflexy', 'my_shortcode_function');

function custom_plugin_title($title) {
    if (is_archive() && 'reflexy' == get_post_type()) {
        $custom_title = 'REFLEXY';
        $title = $custom_title . ' &#8211; ' . get_bloginfo('name');
    }
    return $title;
}
add_filter('pre_get_document_title', 'custom_plugin_title');
