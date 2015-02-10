<?php 
require_once('../../../../../wp-load.php');
$cUID = $_POST['real-id'];

if($cUID == 'non-member'){
	
}
else{
	update_user_meta( $cUID, 'REAL-Track-Start', 'True'); 
	update_user_meta( $cUID, 'REAL-Track-Start-new', 'True'); 
}
 
?>	