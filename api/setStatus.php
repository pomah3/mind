<?php
	require_once ROOT."models/status.php";

	function api_setStatus() {
		$user = get_user($_GET['login']);
		Status_model::set_status($user, $_GET['status']);
		return [];
	}
?>
