<?php
function idf_platform() {
	$platform = get_option('idf_commerce_platform', 'legacy');
	return $platform;
}

function idf_enable_checkout() {
	if (class_exists('ID_Project') && is_id_licensed()) {
		return true;
	}
	return false;
}

function idf_idcf_delivery() {
	$plugins_path = plugin_dir_path(dirname(__FILE__));
	if (!file_exists($plugins_path.'ignitiondeck-crowdfunding')) {
		$idcf = file_get_contents('http://ignitiondeck.com/idf/idcf_latest.zip');
		if (!empty($idcf)) {
			$put_idcf = file_put_contents($plugins_path.'idcf_latest.zip', $idcf);
			$idcf_zip = new ZipArchive;
			$idcf_zip_res = $idcf_zip->open($plugins_path.'idcf_latest.zip');
			if ($idcf_zip_res) {
				$idcf_zip->extractTo($plugins_path);
				$idcf_zip->close();
				unlink($plugins_path.'idcf_latest.zip');
			}
		}
	}
	activate_plugin($plugins_path.'ignitiondeck-crowdfunding/ignitiondeck.php');
}

function idf_fh_delivery() {
	$themes_path = plugin_dir_path(dirname(dirname(__FILE__))).'themes/';
	if (!file_exists($themes_path.'fivehundred')) {
		$fh = file_get_contents('http://ignitiondeck.com/idf/fh_latest.zip');
		if (!empty($fh)) {
			$put_fh = file_put_contents($themes_path.'fh_latest.zip', $fh);
			$fh_zip = new ZipArchive;
			$fh_zip_res = $fh_zip->open($themes_path.'fh_latest.zip');
			if ($fh_zip_res) {
				$fh_zip->extractTo($themes_path);
				$fh_zip->close();
				unlink($themes_path.'fh_latest.zip');
			}
		}
	}
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
		         if (filetype($dir."/".$object) == "dir") {
		         	rrmdir($dir."/".$object);
		         }
		         else {
		         	unlink($dir."/".$object);
		         }
		    }
		}
		reset($objects); 
		rmdir($dir); 
	}
}

function idf_registered() {
	idf_idcf_delivery();
	idf_fh_delivery();
	update_option('idf_registered', 1);
	exit;
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