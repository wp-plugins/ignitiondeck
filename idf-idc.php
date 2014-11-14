<?php
add_action('idc_order_sharing_after', 'idf_idc_order_sharing_options', 10, 1);

function idf_idc_order_sharing_options($last_order) {
	$settings = idf_sharing_settings();
	if (!empty($settings)) {
		$order_id = $last_order->id;
		$mdid_order = mdid_by_orderid($order_id);
		if (!empty($mdid_order)) {
			$pay_id = $mdid_order->pay_info_id;
			$id_order = new ID_Order($pay_id);
			$get_order = $id_order->get_order();
			if (!empty($get_order)) {
				$project_id = $get_order->product_id;
				$project = new ID_Project($project_id);
				$post_id = $project->get_project_postid();
				if ($post_id > 0) {
					include_once plugin_dir_path(dirname(__FILE__)).'ignitiondeck-crowdfunding/templates/_socialButtons.php';
				}
			}
		}
	}
	//include_once('templates/_socialSharing.php');
}
?>