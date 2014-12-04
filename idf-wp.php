<?php
/*
This file is for general functions that modify the WordPress defaults
*/

add_action('pre_get_posts', 'idf_restrict_media_view');

function idf_restrict_media_view($query) {
	if ($query->get('post_type') == 'attachment') {
		if (!current_user_can('editor')) {
			if (is_multisite()) {
				require (ABSPATH . WPINC . '/pluggable.php');
			}
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			if ($user_id > 0) {
				$query->set('author', $user_id);
			}
		}
	}
}

add_action('init', 'idf_add_media_buttons');

function idf_add_media_buttons() {
	if (is_multisite()) {
		require (ABSPATH . WPINC . '/pluggable.php');
	}
	global $current_user;
	$add_cap = false;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	$user = get_user_by('id', $user_id);
	if (current_user_can('create_edit_projects')) {
		if (!current_user_can('upload_files')) {
			if (!empty($user)) {
				$user->add_cap('upload_files');
			}
		}
	}
	if (isset($_GET['create_project']) || isset($_GET['edit_project'])) {
		if (!current_user_can('publish_posts')) {
			idc_add_upload_cap($user);
		}
	}
	else if (isset($_SERVER['HTTP_REFERER'])) {
		if (strpos($_SERVER['HTTP_REFERER'], 'create_project') || strpos($_SERVER['HTTP_REFERER'], 'edit_project')) {
			idc_add_upload_cap($user);
		}
	}
	else {
		idc_remove_upload_cap($user);
	}
}

function idc_add_upload_cap($user) {
	if (!empty($user)) {
		$user->add_cap('edit_others_pages');
		$user->add_cap('edit_others_posts');
		$user->add_cap('edit_pages');
		$user->add_cap('edit_posts');
		$user->add_cap('edit_private_pages');
		$user->add_cap('edit_private_posts');
		$user->add_cap('edit_published_pages');
		$user->add_cap('edit_published_posts');
	}
}

function idc_remove_upload_cap($user) {
	if (!empty($user)) {
		$user->remove_cap('edit_others_pages');
		$user->remove_cap('edit_others_posts');
		$user->remove_cap('edit_pages');
		$user->remove_cap('edit_posts');
		$user->remove_cap('edit_private_pages');
		$user->remove_cap('edit_private_posts');
		$user->remove_cap('edit_published_pages');
		$user->remove_cap('edit_published_posts');
	}
}
?>