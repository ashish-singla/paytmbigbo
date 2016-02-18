<?php
	global $wpdb;

	// Want To Delete Un-approved Comments?
	if($_POST['DeleteUnApproved']){
		// Then Delete Those!
		$query = $wpdb->query("DELETE from " . $wpdb->prefix . "comments WHERE comment_approved = '0';");
		// Show Confirmation Upon Successful Delete Query
		if($query)
			$unapprovedmessage = 'Deleted Successfully!';
		// Else Show No Comment Available Message
		else
			$unapprovedmessage = 'No Comment Available!';
	}

	// Want To Delete Approved Comments?
	if($_POST['DeleteApproved']){
		// Then Delete Those!
		$query = $wpdb->query("DELETE from " . $wpdb->prefix . "comments WHERE comment_approved = '1';");
		// Show Confirmation Upon Successful Delete Query
		if($query)
			$approvedmessage = 'Deleted Successfully!';
		// Else Show No Comment Available Message
		else
			$approvedmessage = 'No Comment Available!';
	}

	// Want To Delete SPAM Comments?
	if($_POST['DeleteSPAM']){
		// Then Delete Those!
		$query = $wpdb->query("DELETE from " . $wpdb->prefix . "comments WHERE comment_approved = 'spam';");
		// Show Confirmation Upon Successful Delete Query
		if($query)
			$spammessage = 'Deleted Successfully!';
		// Else Show No Comment Available Message
		else
			$spammessage = 'No Comment Available!';
	}

	// Get Un-Approved Comments Count
	$unapprovedcomments = $wpdb->get_var("SELECT COUNT(*) from wp_comments WHERE comment_approved = '0';");

	// Get SPAM Comments Count
	$spamcomments = $wpdb->get_var("SELECT COUNT(*) from wp_comments WHERE comment_approved = 'spam';");

	// Get Approved Comments Count
	$approvedcomments = $wpdb->get_var("SELECT COUNT(*) from wp_comments WHERE comment_approved = '1';");

	// Visual Stuffs Of Plugin Admin Page
	print '<script language="JavaScript">
function confirmWPCommentCleaner(typE){
	var agree=confirm("Please note, this action will remove all the comments under "+typE+" status. Proceed?");
	if (agree)
		return true;
	else
		return false;
}
</script>

<div class="wrap">
	<div class="WPCommentCleanerHeading">WPCommentCleaner</div>
	<div id="WPCommentCleaner">
		<form method="post" action="">
			<table>
				<thead>
					<tr>
						<th>Comment Status</th>
						<th>Total Comment</th>
						<th class="nopadding">Action</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Un-Approved Comments:</td>
						<th>'.$unapprovedcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPCommentCleaner(\'Un-Approved\')" name="DeleteUnApproved" class="WPCommentCleanerInput" value="DELETE" />
						</td>
						<td class="fixedwidth">'.$unapprovedmessage.'</td>
					</tr>
					<tr>
						<td>SPAM Comments:</td>
						<th>'.$spamcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPCommentCleaner(\'SPAM\')" name="DeleteSPAM" class="WPCommentCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$spammessage.'</td>
					</tr>
					<tr>
						<td>Approved Comments:</td>
						<th>'.$approvedcomments.'</th>
						<td class="nopadding">
							<input type="submit" onclick="return confirmWPCommentCleaner(\'Approved\')" name="DeleteApproved" class="WPCommentCleanerInput" value="DELETE">
						</td>
						<td class="fixedwidth">'.$approvedmessage.'</td>
					</tr>
				<tbody>
			</table>
		</form>
	</div>
</div>';
?>