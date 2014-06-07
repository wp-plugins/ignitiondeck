<?php
function idf_platform() {
	$platform = get_option('idf_commerce_platform', 'legacy');
	return $platform;
}

function idf_registered() {
	/*
	1. Set option with any login info we need and keep logged in
	2. Download ID and 500
	3. Offer to activate ID and 500
	*/
	// 1
	update_option('idf_registered', 1);
	// 2 (we should use an API to deliver latest and not hard code url)
	$plugins_path = plugin_dir_path(dirname(__FILE__));
	$themes_path = plugin_dir_path(dirname(dirname(__FILE__))).'themes/';
	$idcf = file_get_contents('http://ignitiondeck.com/idf/idcf_latest.zip');
	$fh = file_get_contents('http://ignitiondeck.com/idf/fh_latest.zip');
	if (!empty($idcf)) {
		$put_idcf = file_put_contents($plugins_path.'idcf_latest.zip', $idcf);
		$idcf_zip = new ZipArchive;
		$idcf_zip_res = $idcf_zip->open($plugins_path.'idcf_latest.zip');
		if ($idcf_zip_res) {
			if (!file_exists($plugins_path.'ignitiondeck-crowdfunding')) {
				$idcf_zip->extractTo($plugins_path);
				$idcf_zip->close();
			}
			unlink($plugins_path.'idcf_latest.zip');
		}
	}
	if (!empty($fh)) {
		$put_fh = file_put_contents($themes_path.'fh_latest.zip', $fh);
		$fh_zip = new ZipArchive;
		$fh_zip_res = $fh_zip->open($themes_path.'fh_latest.zip');
		if ($fh_zip_res) {
			if (!file_exists($themes_path.'fivehundred')) {
				$fh_zip->extractTo($themes_path);
				$fh_zip->close();
			}
			unlink($themes_path.'fh_latest.zip');
		}
	}
	// 3
	activate_plugin($plugins_path.'ignitiondeck-crowdfunding/ignitiondeck.php');
}

add_action('wp_ajax_idf_registered', 'idf_registered');
add_action('wp_ajax_nopriv_idf_registered', 'idf_registered');

function idf_activate_theme() {
	if (isset($_POST['theme'])) {
		$slug = esc_attr($_POST['theme']);
		$slug = str_replace('500', 'fivehundred', $slug);
		switch_theme($slug);
		echo 1;
	}
	exit;
}

add_action('wp_ajax_idf_activate_theme', 'idf_activate_theme');
add_action('wp_ajax_nopriv_idf_activate_theme', 'idf_activate_theme');
?>