<?php
/*
 * Plugin Name: WEI Checker WP
 * Plugin URI: https://github.com/qyl27/WEI-Checker-WP
 * Description: WEI checker for WordPress.
 * Version: 0.1
 * Author: qyl27
 * Author URI: https://github.com/qyl27
 * LICENSE: GPLv3
 */

function wei_checker_on_activation(): void {
}
register_activation_hook(__FILE__, 'wei_checker_on_activation');

function wei_checker_on_admin_init(): void {
	register_setting('wei_checker', 'wei_checker_force_close', false);

	add_settings_section('wei_checker_settings', 'Settings', function () {}, 'wei_checker');

	add_settings_field('wei_checker_force_close', 'Force Close Page', function ($args) {
		$option = get_option('wei_checker_force_close');
		$checked = '';
		if (isset($option['wei_checker_force_close'])) {
			$checked = 'checked';
		}
		$label_for = esc_attr($args['label_for']);
		echo "<input type='checkbox' id='$label_for' name='wei_checker_force_close[$label_for]' $checked>";
	},
		'wei_checker', 'wei_checker_settings',
		array('label_for' => 'wei_checker_force_close', 'class' => ''));
}
add_action('admin_init', 'wei_checker_on_admin_init');

function wei_checker_options_page(): void {
	add_menu_page('WEI Checker WP', 'WEI Checker WP', 'manage_options',
		'wei_checker_wp_menu', 'wei_checker_menu_callback' );
}
function wei_checker_menu_callback(): void {
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_GET['settings-updated'])) {
		add_settings_error('wei_checker_settings_updated', 'wei_checker_message', 'Config saved.', 'updated');
	}

	settings_errors('wei_checker_settings_updated');

	$title = esc_html(get_admin_page_title());;
	echo "
	<div class='wrap'>
		<h1>$title</h1>
		<form action='options.php' method='post'>";

	settings_fields('wei_checker');
	do_settings_sections('wei_checker');
	submit_button('Save');

	echo "</form>
	</div>";
}
add_action( 'admin_menu', 'wei_checker_options_page' );

function wei_checker_on_init(): void {
	$url = plugins_url('public/js/checker.js', __FILE__);
	wp_enqueue_script("wei-checker-wp", $url);

	$force_close = get_option('wei_checker_force_close');
	if (isset($force_close['wei_checker_force_close'])) {
		wp_add_inline_script('wei-checker-wp', "<script>check(true);</script>");
	} else {
		wp_add_inline_script('wei-checker-wp', "<script>check(false);</script>");

    }
}
add_action('init', 'wei_checker_on_init');
