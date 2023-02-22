<?php
/**
 * Plugin Name: Typex Reflexy.
 * Description: Wtyczka do publikacji Reflexów.
 * Plugin URI: http://cd2.studio
 * Version: 0.1
 * Author: Marcin Snoch
 * Author URI: http://msnoch.vxm.pl
 */

// // Create recipes CPT
function reflexy_post_type()
{
	register_post_type(
		'reflexy',
		[
			'labels' => [
				'name' => __('Reflexy'),
				'singular_name' => __('Reflex'),
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

// // Add cuisines taxonomy
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
