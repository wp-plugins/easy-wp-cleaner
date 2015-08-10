<?php
function easy_wp_cleaner_admin() {
	add_options_page('Easy WP Cleaner', 'Easy WP Cleaner','manage_options', __FILE__, 'easy_wp_cleaner_page');
}
function easy_wp_cleaner_page(){
?>

<div>
<h2>Easy WP Cleaner</h2>
<?php
function easy_wp_cleaner($type){
	global $wpdb;
	switch($type){
		case "revision":
			$ewc_sql = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
			$wpdb->query($ewc_sql);
			break;
		case "draft":
			$ewc_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'draft'";
			$wpdb->query($ewc_sql);
			break;
		case "autodraft":
			$ewc_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$wpdb->query($ewc_sql);
			break;
		case "moderated":
			$ewc_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
			$wpdb->query($ewc_sql);
			break;
		case "spam":
			$ewc_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$wpdb->query($ewc_sql);
			break;
		case "trash":
			$ewc_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$wpdb->query($ewc_sql);
			break;
		case "postmeta":
			$ewc_sql = "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$wpdb->query($ewc_sql);
			break;
		case "commentmeta":
			$ewc_sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$wpdb->query($ewc_sql);
			break;
		case "relationships":
			$ewc_sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$wpdb->query($ewc_sql);
			break;
		case "feed":
			$ewc_sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
			$wpdb->query($ewc_sql);
			break;
	}
}

function easy_wp_cleaner_count($type){
	global $wpdb;
	switch($type){
		case "revision":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "draft":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "autodraft":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "moderated":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "spam":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "trash":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "postmeta":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "commentmeta":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "relationships":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$count = $wpdb->get_var($ewc_sql);
			break;
		case "feed":
			$ewc_sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
			$count = $wpdb->get_var($ewc_sql);
			break;
	}
	return $count;
}

function easy_wp_cleaner_optimize(){
	global $wpdb;
	$ewc_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
	$result = $wpdb->get_results($ewc_sql);
	foreach($result as $row){
		$ewc_sql = 'OPTIMIZE TABLE '.$row->Name;
		$wpdb->query($ewc_sql);
	}
}

	$ewc_message = '';

	if(isset($_POST['easy_wp_cleaner_revision'])){
		easy_wp_cleaner('revision');
		$ewc_message = __("All revisions are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_draft'])){
		easy_wp_cleaner('draft');
		$ewc_message = __("All drafts are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_autodraft'])){
		easy_wp_cleaner('autodraft');
		$ewc_message = __("All autodrafts are deleted","Easy-WP-Cleaner");
	}
	
	if(isset($_POST['easy_wp_cleaner_moderated'])){
		easy_wp_cleaner('moderated');
		$ewc_message = __("All moderated comments are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_spam'])){
		easy_wp_cleaner('spam');
		$ewc_message = __("All spam comments are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_trash'])){
		easy_wp_cleaner('trash');
		$ewc_message = __("All trash comments are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_postmeta'])){
		easy_wp_cleaner('postmeta');
		$ewc_message = __("All orphan postmeta are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_commentmeta'])){
		easy_wp_cleaner('commentmeta');
		$ewc_message = __("All orphan commentmeta are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_relationships'])){
		easy_wp_cleaner('relationships');
		$ewc_message = __("All orphan relationships are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_feed'])){
		easy_wp_cleaner('feed');
		$ewc_message = __("All dashboard transient feed are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_all'])){
		easy_wp_cleaner('revision');
		easy_wp_cleaner('draft');
		easy_wp_cleaner('autodraft');
		easy_wp_cleaner('moderated');
		easy_wp_cleaner('spam');
		easy_wp_cleaner('trash');
		easy_wp_cleaner('postmeta');
		easy_wp_cleaner('commentmeta');
		easy_wp_cleaner('relationships');
		easy_wp_cleaner('feed');
		$ewc_message = __("All unnecessary data are deleted","Easy-WP-Cleaner");
	}

	if(isset($_POST['easy_wp_cleaner_optimize'])){
		easy_wp_cleaner_optimize();
		$ewc_message = __("Database optimized successfully","Easy-WP-Cleaner");
	}

	if($ewc_message != ''){
		echo '<div id="message" class="updated"><p><strong>' . $ewc_message . '</strong></p></div>';
	}
?>
<hr/>
<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">Notifications</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col"><?php _e('Type','Easy-WP-Cleaner'); ?></th>
								<th scope="col"><?php _e('Count','Easy-WP-Cleaner'); ?></th>
								<th scope="col"><?php _e('','Easy-WP-Cleaner'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php _e('Revision','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('revision'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_revision" value="revision" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('revision')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Draft','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('draft'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_draft" value="draft" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('draft')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Auto Draft','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('autodraft'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_autodraft" value="autodraft" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('autodraft')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Moderated Comments','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('moderated'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_moderated" value="moderated" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('moderated')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Spam Comments','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('spam'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_spam" value="spam" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('spam')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Trash Comments','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('trash'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_trash" value="trash" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('trash')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Orphan Postmeta','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('postmeta'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_postmeta" value="postmeta" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('postmeta')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Orphan Commentmeta','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('commentmeta'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_commentmeta" value="commentmeta" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('commentmeta')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Orphan Relationships','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('relationships'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_relationships" value="relationships" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('relationships')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
							<tr>
								<td><?php _e('Dashboard Transient Feed','Easy-WP-Cleaner'); ?></td>
								<td><?php echo easy_wp_cleaner_count('feed'); ?></td>
								<td>
									<form action="" method="post">
										<input type="hidden" name="easy_wp_cleaner_feed" value="feed" />
										<input type="submit" class="<?php if(easy_wp_cleaner_count('feed')>0){echo 'button-primary';}else{echo 'hide';} ?>" value="<?php _e('Delete','Easy-WP-Cleaner'); ?>" />
									</form>
								</td>
							</tr>
						</tbody>
					</table>
					<form action="" method="post">
						<input type="hidden" name="easy_wp_cleaner_all" value="all" />
						<input type="submit" class="button-primary" value="<?php _e('Delete All','Easy-WP-Cleaner'); ?>" />
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<br/>

<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">Database Status</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col"><?php _e('Table','Easy-WP-Cleaner'); ?></th>
								<th scope="col"><?php _e('Size','Easy-WP-Cleaner'); ?></th>
							</tr>
						</thead>
						<tbody id="the-list">
						<?php
							global $wpdb;
							$total_size = 0;
							$ewc_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
							$result = $wpdb->get_results($ewc_sql);

							foreach($result as $row){

								$table_size = $row->Data_length + $row->Index_length;
								$table_size = $table_size / 1024;
								$table_size = sprintf("%0.3f",$table_size);

								$every_size = $row->Data_length + $row->Index_length;
								$every_size = $every_size / 1024;
								$total_size += $every_size;

								echo "<tr". $alternate .">
										<td class='column-name'>". $row->Name ."</td>
										<td class='column-name'>". $table_size ." KB"."</td>
									</tr>\n";
							}
						?>
						</tbody>
						<tfoot>
							<tr>
								<th scope="col"><?php _e('Total','Easy-WP-Cleaner'); ?></th>
								<th scope="col" style="font-family:Tahoma;"><?php echo sprintf("%0.3f",$total_size).' KB'; ?></th>
							</tr>
						</tfoot>
					</table>
					<p>
						<form action="" method="post">
							<input type="hidden" name="easy_wp_cleaner_optimize" value="optimize" />
							<input type="submit" class="button-primary" value="<?php _e('Optimize Database','Easy-WP-Cleaner'); ?>" />
						</form>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
}
add_action('admin_menu', 'easy_wp_cleaner_admin');
?>