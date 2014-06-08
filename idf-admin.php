<?php
add_action('admin_menu', 'idf_admin_menus');

function idf_admin_menus() {
	if (current_user_can('manage_options')) {
		$home = add_menu_page(__('IgnitionDeck', 'idf'), __('IgnitionDeck', 'idf'), 'manage_options', 'idf', 'idf_main_menu', plugins_url( '/images/ignitiondeck-menu.png', __FILE__ ));
		$theme_list = add_submenu_page( 'idf', __('Themes', 'ignitiondeck'), __('Themes', 'ignitiondeck'), 'manage_options', 'idf-themes', 'idf_theme_list');
		$extension_list = add_submenu_page( 'idf', __('Extensions', 'ignitiondeck'), __('Extensions', 'ignitiondeck'), 'manage_options', 'idf-extensions', 'idf_extension_list');
		$menu_array = array($home,
						$theme_list,
						$extension_list
						);
		foreach ($menu_array as $menu) {
			add_action('admin_print_styles-'.$menu, 'idf_admin_enqueues');
		}
	}
}

function idf_main_menu() {
	$idf_registered = get_option('idf_registered');
	$platform = idf_platform();
	if (isset($_POST['commerce_submit'])) {
		$platform = esc_attr($_POST['commerce_selection']);
		update_option('idf_commerce_platform', $platform);
	}
	include_once 'templates/admin/_idfMenu.php';
}

function idf_extension_list() {
	$plugins = get_plugins();
	/*$plugin_array = array();
	if (!empty($plugins)) {
		foreach ($plugins as $plugin) {
			$plugin_array[] = $plugin['basename'];
		}
	}*/
	$api = 'http://ignitiondeck.com/id/?action=get_extensions';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $api);

	$json = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($json);
	include_once 'templates/admin/_extensionList.php';
}

function idf_theme_list() {
	$themes = wp_get_themes();
	$name_array = array();
	if (!empty($themes)) {
		foreach ($themes as $theme) {
			$name_array[] = $theme->Name;
		}
	}
	$active_theme = wp_get_theme();
	$active_name = $active_theme->Name;
	$api = 'http://ignitiondeck.com/id/?action=get_themes';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $api);

	$json = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($json);
	include_once 'templates/admin/_themeList.php';
}

function idf_admin_enqueues() {
	wp_register_script('idf-admin', plugins_url('/js/idf-admin.js', __FILE__));
	wp_register_style('idf-admin', plugins_url('/css/idf-admin.css', __FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('idf-admin');
	wp_enqueue_style('idf-admin');
}
?>