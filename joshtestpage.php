<?php
/*
Template Name: iMIS Test Template
*/
 

$test = 0;
$create = 0; 

//include "simple_html_dom.php";

include ('includes/aeh_config.php'); 
include ("includes/aeh-functions.php");
get_header();
global $wpdb;

?>

<div id="membernetwork">
	<div class="container">
		<h1 class="title"><span class="grey">Essential Hospitals</span> Maintenance Page</h1>	
		<div id="registrationcontent" class="group">
			<div class="gutter clearfix">
				<h2 class='heading'>Diagnostic Pages</h1>

<?php

//ORDER FOR IMIS SYNC
//import_imis();
//update_wp_users();
//find_new_wp_users();
insert_new_wp_users();




//$headers = "From: America's Essential Hospitals <info@essentialhospitals.org>\r\n";
//mail('joshdodd@meshfresh.com', 'Email Test', 'Testing the email', $headers);


		/*
		$comment = get_comment($ci);
		$commentDate = strtotime($comment->comment_date);
		$commentDate = date("M jS", $commentDate);
		$commentContent = $comment->comment_content;
		$commentID = $comment->comment_ID;
		$commentAuthor = $comment->comment_author;
		$commentContent = apply_filters('the_content', $comment->comment_content); 
		echo $commentContent;
 

		$subject = "TEST EMAIL";
		$headers = "From: AEH <info@essentialhospitals.org>";
		$message = "A user has commented on the post TITLE published TODAY<br><br>
					$commentContent<br><br>
					<a href='/comment.php?action=editcomment&c=$commentID'>Approve or delete this comment</a>
					<br><br>
					<a href='/edit-comments.php'>View all recent comments</a>
					<br><br>
					The above comment may be hidden until you or another web admin approves it. If this user previously posted comments that were approved, this comment will have been automatically approved and is already visible. Review this comment regardless, as action may be necessary. ";
*/

		//wp_mail('joshdodd@meshfresh.com', $subject, $message, $headers);

		//set the coauthors of ALL child pages
		//$post_id =5055;	
		// $args = array(
			 
		//	'child_of' => 5055,
		//	'post_type' => 'group'
 
		//); 			
		//$pages = get_pages($args);
		//foreach($pages as $child) {
		//		echo $child->ID;
		//		echo "<br>";
			//$coauthors_plus->add_coauthors($child->ID,$userArray);
			//add_post_meta($child->ID,'authout',$authout,true) || update_post_meta($child->ID,'authout',$authout);
		//}


/*
$f = fsockopen('smtp.essentialhospitals.org', 25) ;
if ($f !== false) {
    $res = fread($f, 1024) ;
    if (strlen($res) > 0 && strpos($res, '220') === 0) {
        echo "Success" ;
        var_dump($f);
    }
    else {
        echo "Error: " . $res ;
    }
}
fclose($f) ; */
 
	// Send a POST request to ibridge
	//$result = post_request('http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteDatasetStoredProcedure', $params);
	//$result_stat = $result['status'];
	

	/*------------USERS TO OPEN REAL MODULE----------------//
	$results = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'REAL-Track-Start' ORDER BY `wp_usermeta`.`user_id` ASC");
	 
	echo "<table>"; 
	foreach($results as $id) 
	{
		echo "</tr>"; 
		$user_info = get_userdata($id);
     	$email = $user_info->user_email;
      	$first_name = $user_info->first_name;
      	$last_name = $user_info->last_name;
		echo "<td>".$id ."</td><td>". $email."</td><td>".$first_name." ".$last_name."</td>";
		echo "</tr>"; 
	}
	echo "</table>"; */


	/*------------LOGGED IN USERS TO ACCESS  REAL MODULE LANDING----------------
	$results = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'REAL' ORDER BY `wp_usermeta`.`user_id` ASC");
	 
	echo "<table>"; 
	foreach($results as $id) 
	{
		echo "</tr>"; 
		$user_info = get_userdata($id);
     	$email = $user_info->user_email;
      	$first_name = $user_info->first_name;
      	$last_name = $user_info->last_name;
		echo "<td>".$id ."</td><td>". $email."</td><td>".$first_name." ".$last_name."</td>";
		echo "</tr>"; 
	}
	echo "</table>"; 


	get_imis_tables(); */



	

 
 
	/*
	$results = $wpdb->get_col("SELECT `ID` FROM `wp_aeh_import_full`");
	$current_ids = $wpdb->get_col("SELECT `meta_value` FROM `wp_usermeta` WHERE `meta_key`='aeh_imis_id' ");

	$new_diffs = array_diff($results,$current_ids);

 	$a = array();
 	foreach ($new_diffs as $new_diff){
		array_push($a, $new_diff);
	}
	var_dump($a);
	update_option('imis_users_to_add',$a);

	$option = get_option('imis_users_to_add');

	$ids = ''; echo "<br><br><br>-----ADDING USER----<br><br>";
	foreach($option as $imis_id){
		$check = $wpdb->query("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_imis_id' AND `meta_value` = '$imis_id'"); //make sure this user doesn't already exist first
		echo "IMIS ID: " . $imis_id;
		if (!$check){ 														// if this user has been added from iMIS to WP then skip adding this user or else do the biz
			add_one_imis_user($imis_id);	
			$ids = $ids . ', '. $imis_id ;							    	// loop here adding individual WP accounts programmatically.
			echo "ADDED";
		}
		echo "<br/>";
	}
	
 

 
//------------------------------------------------------------------------------------------
	//Test for IMIS DB vs MESH DB

	/*
 	$mesh_users = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'aeh_member_type' and meta_value = 'hospital'");
 	$mesh_users = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'aeh_imis_id' and meta_value != ''");
 	$imis_users = $wpdb->get_col("SELECT ID FROM `wp_aeh_import_full` ");

 	sort($imis_users);

 	$mesh_imis_ids = array();


 	foreach ($mesh_users  as $wp_id) {
 		$imis_id = get_user_meta( $wp_id, 'aeh_imis_id', true );
 		//echo $wp_id . "  :  " . $imis_id . "<br>";
 		array_push($mesh_imis_ids , $imis_id);
 	}
 	sort($mesh_imis_ids);
 	


 	$imis_diffs = array_diff($mesh_imis_ids,$imis_users);
 	$ctr = 1;
 	echo "-----IMIS DIFFS------";
 	foreach($imis_diffs as $idiff)
 	{
 		echo $ctr . "  :  " . $idiff . "<br>";
 		$ctr++;
 	}

 	?>
 	<table>
 		<tr>
 			<td>
 				<h3> MESH IMIS IDS </h3>
				<?php 
				$ctr = 1;
			 	foreach($mesh_imis_ids as $mesh_imis_id)
			 	{
			 		echo $ctr . "  :  " . $mesh_imis_id . "<br>";
			 		$ctr++;
			 	}
				?>

 			</td>

 			<td>
 				<h3>IMIS IDS </h3>
 				<?php 
 				$ctr = 1;
			 	foreach($imis_users as $imis_user)
			 	{
			 		echo $ctr . "  :  " . $imis_user . "<br>";
			 		$ctr++;
			 	}
				?>

 			</td>
 		</tr>
 	</table>




 	




	<?php 
*/
	//------------------------------------------------------------------------------------------
	//GET USERS THAT ARE NOT VERIFIED (NEED TO BE ADDED TO IMIS...)

/*
 	$all_hosp = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'aeh_member_type' and meta_value = 'hospital'");
	$verified = $wpdb->get_col("SELECT user_id FROM `wp_usermeta` where meta_key = 'imis_verified' and meta_value = 1");


 	$diffs = array_diff($all_hosp,$verified);
 	//print_r($diffs);

 	$n = 1;
 	echo "<br><br><br><br> ---------------------------- USERS NOT IMIS VERIFIED (WP ID) ------------------------<br><br><br><br>";
 	foreach ($diffs as $diff){
		 
		//$imis = array();
		$imisid  = get_usermeta( $diff, $meta_key = 'aeh_imis_id' );
		//delete_user_meta( $diff , 'aeh_imis_id' );
		//delete_user_meta( $diff , 'aeh_password' );
		//delete_user_meta( $diff , 'address_number' );
		 

		echo $n . ":  " .$diff .  "<br>";
		//array_push($imis, $imisid);


		$n++;
	}

	 
	 




	//print_r($diff);
	//print_r($verified);






/**************************************************************************************************************** 

	$results = $wpdb->get_results("SELECT * FROM `wp_users` where ID IN (SELECT DISTINCT user_id FROM `wp_usermeta` WHERE `meta_key` = 'aeh_member_type'  AND `meta_value` = 'hospital' AND user_id NOT IN (SELECT user_id FROM `wp_usermeta` WHERE`meta_key` = 'aeh_password'))
 ");
	 $n = 1;

	foreach($results as $result){

		$pwd_hash   = $result->user_pass;
		$user_id = $result->ID;
		$user_login = $result->user_login;
		$user_email = $result->user_email;
		 
 		require_once( ABSPATH . 'wp-includes/class-phpass.php');
		
		$wp_hasher = new PasswordHash(8, TRUE);
 
		if($wp_hasher->CheckPassword($tester, $pwd_hash)){
			echo "<span style='color:#00ff00'> YES, Matched </span>: " . $user_id . " - " .$user_login ;
			add_user_meta( $user_id, "aeh_password", $tester );
		}else{
			echo  $user_email;
		}
		echo "<br /> ";
		$n++;
		//unset ($wp_hasher);
	}
	$n = $n - 1;
	echo "<br /> TOTAL: $n";

	exit;
 
/****************************************************************************************************************/
?>  