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
	if (current_user_can('create_edit_projects')) {
		if (!current_user_can('upload_files')) {
			if (is_multisite()) {
				require (ABSPATH . WPINC . '/pluggable.php');
			}
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$user = get_user_by('id', $user_id);
			if (!empty($user)) {
				$user->add_cap('upload_files');
			}
		}
	}
}
?>