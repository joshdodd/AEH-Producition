<?php 
/********************************************* SWITCHES/VARIABLE SETUP ***********************************************/

$test = false;														// true = TEST DATABASE, false = PRODUCTION DATABASE
define ("EMAILCRON", FALSE);										// true = send email at import cron, false = do not send email
define ("IMPORT_PER_CRON", 1000);									// maximum number of rows to import per cron job - tweak to make sure script doesn't timeout
define ("SP_SECURITY_PWD", "F46DB250-B294-4B3D-BC95-45B7DDFEE334"); // Stored Procedure Security Password
define ("SOAP_ACCOUNT_PWD", "300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7");// SOAP web services security password
define ("MAX_WP_USERS_UPDATED", '400');								// How many users updated in WP from the wp_aeh_import_full table

/****************************************** PROD or TEST? IMIS STORED PROCS ETC. *************************************/
if ($test){
	define ("SP_IMPORT_USERS",   "test_importUsers");				// main import users cron job
	define ("SP_GET_IMIS_USER",  "test_GetImisUser");				// retrieve info on one iMIS user
	define ("SP_GET_ROW_COUNT",  "test_GetRowCount");				// get total row count for main import users table from iMIS
	define ("SP_LOGIN_TIME",     "test_MESH_UD_Security");			// update last login time in iMIS
	define ("SP_DOES_USER_EXIST","test_DoesUserExist");				// check if user already exists in iMIS from their email address
	define ("SP_GET_TITLES",     "test_GetTitles");					// cron to get user TITLES and set serialized value in WP Options table
	define ("SP_WEB_INTERESTS",  "test_GetWebInterests");			// cron to get WEB_INTEREST and set serialized value in WP Options table
	define ("SP_COMPANY_LIST",  "test_GetCompanyList");				// cron to get COMPANY and HQ address info and set serialized value in WP Options table
	define ("IMIS_SOAP_URL",'http://isgweb.naph.org/ibridge_test/Account.asmx?wsdl'); 								// URL for test SOAP Client comms with iMIS
	define ("IMIS_POST_URL",'http://isgweb.naph.org/ibridge_test/DataAccess.asmx/ExecuteDatasetStoredProcedure');	// URL for test POST comms (read) with iMIS
	define ("SP_POST_UPDATE_URL", 'http://isgweb.naph.org/ibridge_test/DataAccess.asmx/ExecuteStoredProcedure');	// URL for POST execute SP on Account Updates
	define ("SOAP_DEMOG_UPDATE_URL",'http://isgweb.naph.org/ibridge_test/Demographics.asmx?wsdl');					// URL for POST execute SP on Demographic Updates
}else{
	define ("SP_IMPORT_USERS",   "importUsers");					// main import users cron job
	define ("SP_GET_IMIS_USER",  "GetImisUser");					// retrieve info on one iMIS user
	define ("SP_GET_ROW_COUNT",  "GetRowCount");					// get total row count for main import users table from iMIS
	define ("SP_LOGIN_TIME",     "MESH_UD_Security");				// update last login time in iMIS
	define ("SP_DOES_USER_EXIST","DoesUserExist");					// check if user already exists in iMIS from their email address
	define ("SP_GET_TITLES",     "GetTitles");						// cron to get user TITLES and set serialized value in WP Options table
	define ("SP_WEB_INTERESTS",  "GetWebInterests");				// cron to get WEB_INTEREST and set serialized value in WP Options table
	define ("SP_COMPANY_LIST",  "GetCompanyList");					// cron to get COMPANY and HQ address info and set serialized value in WP Options table
	define ("SP_EMAIL_LIST",  "GetEmailList");				     	// cron to get verified email domains and update wp_aeh_email table
	define ("IMIS_SOAP_URL",'http://isgweb.naph.org/ibridge/Account.asmx?wsdl'); 									// URL for SOAP Client comms with iMIS
	define ("IMIS_POST_URL",'http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteDatasetStoredProcedure');		// URL for POST comms (read) with iMIS
	define ("SP_POST_UPDATE_URL", 'http://isgweb.naph.org/ibridge/DataAccess.asmx/ExecuteStoredProcedure');			// URL for POST execute SP on Account Updates
	define ("SOAP_DEMOG_UPDATE_URL",'http://isgweb.naph.org/ibridge/Demographics.asmx?wsdl');					// URL for POST execute SP on Demographic Updates
}

/*
iMIS passwords
Account        = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Activities     = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Authentication = 27D5F4B5-57B2-4A67-BC82-AA2E1756DED3
DataAccess     = F46DB250-B294-4B3D-BC95-45B7DDFEE334
Demographics   = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Purchase       = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
Relationships  = 300A6E01-5DB9-4217-A2DE-CDB2F08FE1F7
*/
/*********************************************************************************************************************/

function print_constants(){
	$string = '';
	$string .= "SP_IMPORT_USERS: " . SP_IMPORT_USERS . "<br />";
	$string .= "SP_GET_IMIS_USER: " . SP_GET_IMIS_USER . "<br />";
	$string .= "SP_GET_ROW_COUNT: " . SP_GET_ROW_COUNT . "<br />";
	$string .= "SP_LOGIN_TIME: " . SP_LOGIN_TIME . "<br />";
	$string .= "SP_DOES_USER_EXIST: " . SP_DOES_USER_EXIST . "<br />";
	$string .= "SP_GET_TITLES: " . SP_GET_TITLES . "<br />";
	$string .= "SP_WEB_INTERESTS: " . SP_WEB_INTERESTS . "<br />";
	$string .= "IMIS_SOAP_URL: " . IMIS_SOAP_URL . "<br />";
	$string .= "IMIS_POST_URL: " . IMIS_POST_URL . "<br />";
	$string .= "SP_POST_UPDATE_URL: " . SP_POST_UPDATE_URL . "<br />";
	$string .= "EMAILCRON: " . EMAILCRON . "<br />";
	$string .= "IMPORT_PER_CRON: " . IMPORT_PER_CRON . "<br />";
	$string .= "SP_SECURITY_PWD: " . SP_SECURITY_PWD . "<br />";
	$string .= "SOAP_ACCOUNT_PWD: " . SOAP_ACCOUNT_PWD . "<br />";
	$string .= "MAX_WP_USERS_UPDATED: " . MAX_WP_USERS_UPDATED . "<br />";
	return $string;
}

/* ************************************************************************************************************************************************/
// update profile hook to send updated data to iMIS DB


add_action( 'wpmem_post_update_data', 'prof_update_hook', 99999 , 1 );
add_action( 'profile_update', 'admin_profile_update', 12, 1 );

function prof_update_hook( $fields )
{
  $user_id = $fields['ID'];
  update_imis($user_id);
}

function admin_profile_update( $user_id ) {
   
    // password changed. fix for changing pw from admin..
    if($_POST['pass1']!='')
    {	
    	$pass = $_POST['pass1'];
    	update_user_meta($user_id,'aeh_password',$pass);
    }
    update_imis($user_id);
	 
}




function update_imis($user_id){
	global $wpdb;
	$user        = (string)$user_id;

    //get user ID of the profile being updated (not necessarily who is logged in!)
	$userdata    = get_userdata($user_id);              	//use this object for data in wp_users
	$imis_id   	 = $userdata->aeh_imis_id;


	if ($imis_id != ""){									//only do this update code if imis user id is present
		$update = update_imis_from_wp($imis_id, $userdata, $user);
			//$wpdb->update('wp_aeh_import', array('WP_post_ID' => '$user'), array( 'ID' => $imis_id ),array('%s'));
			//$wpdb->update('wp_aeh_import_full', array('WP_post_ID' => '$user'), array( 'ID' => $imis_id ),array('%s'));
		if ($update === false){}else{


		}
	}
}


/******************************************************** UPDATE IMIS FROM WP USERMETA ***********************************************************/

function update_imis_from_wp($imis_id, $userdata, $user){

		global $wpdb;
		$check = time(); //used for test purposes
		$chgtime = date('Y-m-d H:i:s',$check);


		$mem_type    = $userdata->aeh_staff; if ($mem_type=='Y' OR $mem_type=='y'){$mem_type= "STAFF";}else{$mem_type= "MIND";}
		$prefix      = $userdata->prefix;
		$firstname   = $userdata->first_name;
		$middlename  = $userdata->middle_name;
		$lastname    = $userdata->last_name;
		$suffix      = $userdata->suffix;
		$jobtitle    = $userdata->job_title;
		$nickname    = $userdata->nickname;
		$addressnum  = $userdata->address_number;
		$streetaddr  = $userdata->street_address;
		$city        = $userdata->city;
		$state       = $userdata->state;
		$zip         = $userdata->zip_code;
		$ptpwd       = $userdata->aeh_password;
		$country     = $userdata->country;
		$workphone   = $userdata->phone;
		$fax         = $userdata->fax;
		$designation = $userdata->designation;
		$mobile      = $userdata->mobile_phone;
		$employer    = $userdata->hospital_name;
        $co_id       = $userdata->CO_ID;
		$email       = $userdata->user_email;
		$website     = $userdata->user_url;
		$asst_name   = $userdata->assistant_name;
		$asst_phone  = $userdata->assistant_phone;
		$asst_email  = $userdata->assistant_email;
		$webinterest = $userdata->imisWebInterests;
		if (empty($webinterest)){
			$webinterest = "";
		}else{
			$temp = "";
			foreach ($webinterest as $each){$temp .= $each . ", ";}
			$webinterest = substr($temp,0,-2);
		}




		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'account' => array(
				'Id'          => (string)$imis_id,
				'MemberType'  => (string)$mem_type,
				'Prefix'      => (string)$prefix,
				'Email'       => (string)$email,
				'WebLogin'    => (string)$email,
				'Company'     => (string)$employer,
        		'CompanyID'   => (string)$co_id,
				'FirstName'   => (string)$firstname,
				'MiddleName'  => (string)$middlename,
				'LastName'    => (string)$lastname,
				'InformalName'=> (string)$firstname,
				'Suffix'      => (string)$suffix,
				'Title'       => (string)$jobtitle,
				'Password'    => (string)$ptpwd,
				'Designation' => (string)$designation,
				'WorkPhone'   => (string)$workphone,
				'Fax'         => (string)$fax,
				'WebSite'     => (string)$website

			)
		);
		$q = serialize($params);


		$client     = new SoapClient(IMIS_SOAP_URL);
		$response   = $client->Update($params);
		$result     = $response->UpdateResult;
		$z = serialize($result);
		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('profile_update_response: $check', '$result : $email')");



 
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_NAME',
			'fieldValue'      => (string)$asst_name
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic1 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_PHONE',
			'fieldValue'      => (string)$asst_phone
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic2 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_EMAIL',
			'fieldValue'      => (string)$asst_email
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
   		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic3 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Iweb_create_account',
			'fieldName'       => 'WEB_INTERESTS',
			'fieldValue'      => (string)$webinterest
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic4 response: $check', '$result')");
    	unset ($demographic);

    	$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Membership',
			'fieldName'       => 'WEB_CHG',
			'fieldValue'      => (string)$chgtime
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('WEB_CHG response: $check', '$result')");
    	unset ($demographic);
 
		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'address' => array(
				'Id'           => (string)$imis_id,
				'Address1'     => (string)$streetaddr,
				'City'         => (string)$city,
				'StateProvince'=> (string)$state,
				'Zip'          => (string)$zip,
				'Country'      => (string)'',
				'Number' 	   => (string)$addressnum
			)
		);
		$addrclient = new SoapClient(IMIS_SOAP_URL);
		$response   = $addrclient->UpdateAddress($params);
		$result     = $response->UpdateAddressResult;
         $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address response: $check', '$result')");
 
		if ($result == $imis_id){
			$return = $imis_id;
		}else{
			return false;
		}


}


/******************************************************** UPDATE IMIS ON CREATE NEW IMIS ACCOUNT ****************************************************************/


function update_create_imis_from_wp($imis_id, $userdata, $user){


		global $wpdb;
		$check = time(); //used for test purposes
		$chgtime = date('Y-m-d H:i:s',$check);


		$mem_type    = $userdata->aeh_staff; if ($mem_type=='Y' OR $mem_type=='y'){$mem_type= "STAFF";}else{$mem_type= "MIND";}
		$prefix      = $userdata->prefix;
		$firstname   = $userdata->first_name;
		$middlename  = $userdata->middle_name;
		$lastname    = $userdata->last_name;
		$suffix      = $userdata->suffix;
		$jobtitle    = $userdata->job_title;
		$nickname    = $userdata->nickname;
		$addressnum  = $userdata->address_number;
		$streetaddr  = $userdata->street_address;
		$city        = $userdata->city;
		$state       = $userdata->state;
		$zip         = $userdata->zip_code;
		$ptpwd       = $userdata->aeh_password;
		$country     = $userdata->country;
		$workphone   = $userdata->phone;
		$fax         = $userdata->fax;
		$designation = $userdata->designation;
		$mobile      = $userdata->mobile_phone;
		$employer    = $userdata->hospital_name;
        $co_id       = $userdata->CO_ID;
		$email       = $userdata->user_email;
		$website     = $userdata->user_url;
		$asst_name   = $userdata->assistant_name;
		$asst_phone  = $userdata->assistant_phone;
		$asst_email  = $userdata->assistant_email;
		$webinterest = $userdata->imisWebInterests;
		if (empty($webinterest)){
			$webinterest = "";
		}else{
			$temp = "";
			foreach ($webinterest as $each){$temp .= $each . ", ";}
			$webinterest = substr($temp,0,-2);
		}


		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'account' => array(
				'Id'          => (string)$imis_id,
				'MemberType'  => (string)$mem_type,
				'Prefix'      => (string)$prefix,
				'Email'       => (string)$email,
				'WebLogin'    => (string)$email,
				'Company'     => (string)$employer,
        		'CompanyID'   => (string)$co_id,
				'FirstName'   => (string)$firstname,
				'MiddleName'  => (string)$middlename,
				'LastName'    => (string)$lastname,
				'InformalName'=> (string)$firstname,
				'Suffix'      => (string)$suffix,
				'Title'       => (string)$jobtitle,
				'Password'    => (string)$ptpwd,
				'Designation' => (string)$designation,
				'WorkPhone'   => (string)$workphone,
				'Fax'         => (string)$fax,
				'WebSite'     => (string)$website

			)
		);
		$q = serialize($params);


		$client     = new SoapClient(IMIS_SOAP_URL);
		$response   = $client->Update($params);
		$result     = $response->UpdateResult;
		$z = serialize($result);
		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('update_on_create_response: $check', '$result')");



 
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_NAME',
			'fieldValue'      => (string)$asst_name
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic1 response: $check', '$result')");

		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_PHONE',
			'fieldValue'      => (string)$asst_phone
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic2 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Assistant',
			'fieldName'       => 'ASSISTANT_EMAIL',
			'fieldValue'      => (string)$asst_email
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic3 response: $check', '$result')");
		unset ($demographic);
		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Iweb_create_account',
			'fieldName'       => 'WEB_INTERESTS',
			'fieldValue'      => (string)$webinterest
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
   	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Demographic4 response: $check', '$result')");
   		unset ($demographic);

   		$params = array(
			'securityPassword'=> SOAP_ACCOUNT_PWD,
			'id'              => (string)$imis_id,
			'windowName'      => 'Name-Membership',
			'fieldName'       => 'WEB_CHG',
			'fieldValue'      => (string)$chgtime
		);
		$demographic = new SoapClient(SOAP_DEMOG_UPDATE_URL);
		$response    = $demographic->Update($params);
		$result      = $response->UpdateResult;
    	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('WEB_CHG response: $check', '$result')");
    	unset ($demographic);
 
		$params = array(
			'securityPassword' => SOAP_ACCOUNT_PWD,
			'address' => array(
				'Id'           => (string)$imis_id,
				'Address1'     => (string)$streetaddr,
				'City'         => (string)$city,
				'StateProvince'=> (string)$state,
				'Zip'          => (string)$zip,
				'Country'      => (string)'',
				'Number' 	   => (string)$addressnum
			)
		);
		$addrclient = new SoapClient(IMIS_SOAP_URL);
		$response   = $addrclient->UpdateAddress($params);
		$result     = $response->UpdateAddressResult;
         $wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address response: $check', '$result')");
		//$z = serialize($params);
		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('Address $check', '$result, $z')");
		if ($result == $imis_id){
			$return = $imis_id;
		}else{
			return false;
		}

}

/******************************************************** CREATE NEW IMIS ACCOUNT ****************************************************************/

function new_imis($first,$last,$email,$password){

	global $wpdb;
	$check = time(); //used for test purposes
	$expiration = date("Y") + 30; 					//add 30 years to the present year
	$check_email = check_aeh_email($email); 		//test the email address to see what to set other values to
	if ($check_email[0] == "public")return false; 	//no public users get added to the iMIS DB!
	if ($check_email[2] == 'Y'){$staff = 'Y'; $memtype = "STAFF";}else{$staff = 'N'; $memtype = "PEND";}
	$params = array(
		'securityPassword' => SOAP_ACCOUNT_PWD,
		'account' => array(
			'Status'              => 'A',
			'MemberType'          => $memtype,
			'JoinDate'            => date ("m/d/Y"),
			'FirstName'           => $first,
			'LastName'            => $last,
			'SourceCode'          => 'WEB',
			'Email'               => $email,
			'WebLogin'            => $email,
			'Password'            => $password,
			'ExpirationDate'      => "01/01/$expiration",
			'StaffUser'           => $staff,
		)
	);

	$client     = new SoapClient(IMIS_SOAP_URL);
	$response   = $client->Create($params);
	$result     = $response->CreateResult;
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('New User Time: $check', '$result')");

	if (is_numeric($result + 0))return $result; // add 0 to result. If it was a number in the first place it'll still be a number (will be numeric).
	return false;

}

/************************************************** CHECK TO GET STATUS OF EMAIL ADDRESS *********************************************************/

function check_aeh_email($email){ // this is the email address to check and return its status
	global $wpdb;
	$return       = explode('@', $email);
	$domain       = $return[1];
	$result       = $wpdb->get_row("SELECT * FROM `wp_aeh_email` WHERE `domain` = '$domain'");
	$aeh_staff    = "N";
	$organization = "";
	if ($result->domain == $domain){
		$member_type    = 'hospital';
		$aeh_staff      = $result->staff;
		$organization   = $result->organization;
	}else{
		$member_type    = 'public';
	}
	$result = array();
	array_push($result, $member_type, $domain, $aeh_staff, $organization);
	return $result; // key 0 => member_type, 1 => domain, 2 => staff(Y/N), 3 => organization
}
/*************************************************************************************************************************************************/



/******************************************************** CRON Jobs & Misc Functions *************************************************************/
//


add_action('aeh_import_imis', 'import_imis');

function import_imis() { 	 // fill up the wp_aeh_import & wp_aeh_import_full tables from iMIS

	global $wpdb;

	$rowcount = get_imis_row_count();
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('rowcount', '$rowcount')");


	$results  = $wpdb->get_row('SELECT `rownum` FROM `wp_aeh_import` ORDER BY `rownum` DESC LIMIT 0, 1');
	$import   = $results->rownum;
	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('import', '$import')");


	if ($import === null){
		$start = '0';
	}else{
		$start = $import;
	}

	if ($start >= $rowcount){
		$start = '0';
		$wpdb->query('TRUNCATE TABLE `wp_aeh_import_full`');						//trash the full table before copying over the latest full contents
		$wpdb->query('INSERT `wp_aeh_import_full` SELECT * FROM `wp_aeh_import`');	//copy over the import table to the full table so WP can use it anytime
		$wpdb->query('TRUNCATE TABLE `wp_aeh_import`');								//truncate the import table to start over doing new imports
	}

	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('start', '$start')");


	$sql = "";
	$error = 0;																		// assume no errors at first
	$crontime = date("Y-m-d H:i:s");												// get the current datetime stamp for the cron log
	$variables = "@start=$start, @limit=" . IMPORT_PER_CRON;

	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_IMPORT_USERS,
		'parameters'      => $variables
	);

	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	$result_stat = $result['status'];

	$startunix = time();
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		// Print headers
		$header = $result['header'];

		//$thexml = html_entity_decode($result['content']);				// convert the xml into real characters instead of entities
		$xml = simplexml_load_string($result['content']);

		//file_put_contents("import_table.txt",$result['content']);		// test save file to check xml output

		if ($xml === false){
			echo 'Error while parsing the document';
			$error++;													// make error non zero
		}
		$xml = dom_import_simplexml($xml);

		if (!$xml) {
			echo 'Error while converting XML';
			$error++;													// make error non zero
		}

		if ($error==0){													// proceed with table import if no errors so far

			$nodelist = $xml->getElementsByTagName('Table');

			for($i = 0; $i < $nodelist->length; $i++) {

				$unix        = time(); if ($i == 0){$first_time = $unix;}
				$ID          = $nodelist->item($i)->getElementsByTagName('ID');
				$rownum      = $nodelist->item($i)->getElementsByTagName('RowNum');
				$prefix      = $nodelist->item($i)->getElementsByTagName('PREFIX');
				$firstname   = $nodelist->item($i)->getElementsByTagName('FIRST_NAME');
				$middlename  = $nodelist->item($i)->getElementsByTagName('MIDDLE_NAME');
				$lastname    = $nodelist->item($i)->getElementsByTagName('LAST_NAME');
				$designation = $nodelist->item($i)->getElementsByTagName('DESIGNATION');
				$informal    = $nodelist->item($i)->getElementsByTagName('INFORMAL');
				$workphone   = $nodelist->item($i)->getElementsByTagName('WORK_PHONE');
				$fax         = $nodelist->item($i)->getElementsByTagName('FAX');
				$suffix      = $nodelist->item($i)->getElementsByTagName('SUFFIX');
				$addressnum	 = $nodelist->item($i)->getElementsByTagName('ADDRESS_NUM');
				$address1	 = $nodelist->item($i)->getElementsByTagName('ADDRESS_1');
				$city        = $nodelist->item($i)->getElementsByTagName('CITY');
				$state       = $nodelist->item($i)->getElementsByTagName('STATE_PROVINCE');
				$zip         = $nodelist->item($i)->getElementsByTagName('ZIP');
				$country     = $nodelist->item($i)->getElementsByTagName('COUNTRY');
				$email       = $nodelist->item($i)->getElementsByTagName('EMAIL');
				$password    = $nodelist->item($i)->getElementsByTagName('WEB_PASSWORD');
				$mem_type    = $nodelist->item($i)->getElementsByTagName('MEMBER_TYPE');
				$company     = $nodelist->item($i)->getElementsByTagName('COMPANY');
				$co_id 		 = $nodelist->item($i)->getElementsByTagName('CO_ID');
				$title       = $nodelist->item($i)->getElementsByTagName('TITLE');
				$website     = $nodelist->item($i)->getElementsByTagName('WEBSITE');
				$mobile      = $nodelist->item($i)->getElementsByTagName('MOBILE_PHONE');
				$asst_name   = $nodelist->item($i)->getElementsByTagName('ASSISTANT_NAME');
				$asst_phone  = $nodelist->item($i)->getElementsByTagName('ASSISTANT_PHONE');
				$asst_email  = $nodelist->item($i)->getElementsByTagName('ASSISTANT_EMAIL');
				$webinterest = $nodelist->item($i)->getElementsByTagName('WEB_INTERESTS');


				$ID          =   $ID->item(0)->nodeValue;
				$rownum      =   $rownum->item(0)->nodeValue;
				$prefix      =   addslashes($prefix->item(0)->nodeValue);
				$firstname   =   addslashes($firstname->item(0)->nodeValue);
				$middlename  =   addslashes($middlename->item(0)->nodeValue);
				$lastname    =   addslashes($lastname->item(0)->nodeValue);
				$suffix      =   addslashes($suffix->item(0)->nodeValue);
				$designation =   addslashes($designation->item(0)->nodeValue);
				$fullname    =   trim (str_replace("  ", " ", "$prefix $firstname $middlename $lastname $suffix $designation"));
				$informal    =   addslashes($informal->item(0)->nodeValue);
				$workphone   =   addslashes($workphone->item(0)->nodeValue);
				$fax         =   addslashes($fax->item(0)->nodeValue);
				$addressnum  =   addslashes($addressnum->item(0)->nodeValue);
				$address1    =   addslashes($address1->item(0)->nodeValue);
				$city        =   addslashes($city->item(0)->nodeValue);
				$state       =   addslashes($state->item(0)->nodeValue);
				$zip         =   addslashes($zip->item(0)->nodeValue);
				$country     =   addslashes($country->item(0)->nodeValue);
				$email       =   addslashes($email->item(0)->nodeValue);
				$password    =   addslashes($password->item(0)->nodeValue);
				$mem_type    =   addslashes($mem_type->item(0)->nodeValue);
				$company     =   addslashes($company->item(0)->nodeValue);
				$co_id       =   addslashes($co_id->item(0)->nodeValue);
				$title       =   addslashes($title->item(0)->nodeValue);
				$website     =   addslashes($website->item(0)->nodeValue);
				$mobile      =   addslashes($mobile->item(0)->nodeValue);
				$asst_name   =   addslashes($asst_name->item(0)->nodeValue);
				$asst_phone  =   addslashes($asst_phone->item(0)->nodeValue);
				$asst_email  =   addslashes($asst_email->item(0)->nodeValue);
				$webinterest =   addslashes($webinterest->item(0)->nodeValue);
				$username 	 =   "$ID-" . preg_replace("/[^a-z0-9]+/i", "", "$firstname$middlename$lastname");




				$sql = "
				INSERT INTO `wp_aeh_import` (
					`rownum`,
					`ID`,
					`unixtime`,
					`username`,
					`firstname`,
					`middlename`,
					`lastname`,
					`suffix`,
					`fullname`,
					`nickname`,
					`email`,
					`password`,
					`mem_type`,
					`company`,
					`CompanyID`,
					`title`,
					`prefix`,
					`designation`,
					`website`,
					`addressnum`,
					`address1`,
					`city`,
					`zip`,
					`state`,
					`country`,
					`workphone`,
					`fax`,
					`mobile`,
					`asst_name`,
					`asst_phone`,
					`asst_email`,
					`webinterest`
				) VALUES (
					'$rownum',
					'$ID',
					'$unix',
					'$username',
					'$firstname',
					'$middlename',
					'$lastname',
					'$suffix',
					'$fullname',
					'$informal',
					'$email',
					'$password',
					'$mem_type',
					'$company',
					'$co_id',
					'$title',
					'$prefix',
					'$designation',
					'$website',
					'$addressnum',
					'$address1',
					'$city',
					'$zip',
					'$state',
					'$country',
					'$workphone',
					'$fax',
					'$mobile',
					'$asst_name',
					'$asst_phone',
					'$asst_email',
					'$webinterest'

				)";

				$wpdb->query($sql);
			}
			$elapsed = time() - $startunix;
			$now    = date("Y-m-d H:i:s");
			$header = substr($header, strpos($header, 'Content-Length:'));
			$message =  "Added $i records to temp import table at $now taking $elapsed seconds.\r\n\r\nUNIX time range: $first_time - $unix\r\n\r\n$header\r\n";
			$sql = "
			INSERT INTO `wp_aeh_import_meta` (
				`date`,
				`header`,
				`elapsed`,
				`records`,
				`first`,
				`last`
			) VALUES (
				'$now',
				'$header',
				$elapsed,
				$i,
				$first_time,
				$unix
				)";
			$wpdb->query($sql);
		}
	}
		/*************************** confirmation email & update cron log file ******************************/

		if ($error){$message = "The table import failed in Cron Job. See administrator for details.";}
		$cronlogtext = "Cron fired at: $crontime\r\n$message\r\n**************************************************************\r\n\r\n";
		file_put_contents("cronlog.txt", $cronlogtext, FILE_APPEND);

		if (EMAILCRON){
			$headers = "From: Cron Job <cron@essentialhospitals.org>\r\n";
			wp_mail('steve@meshfresh.com', 'iMIS data imported', $cronlogtext, $headers);
		}

}

/******************************************* CRON TO TAKE iMIS VALUES AND UPDATE WP USERS *************************************************/

add_action('aeh_update_wp_users', 'update_wp_users');
function update_wp_users() { // fill up the wp_aeh_import & wp_aeh_import_full tables from iMIS
	global $wpdb;

	$sql = "
	SELECT
	`rownum`,
	`ID`,
	`firstname`,
	`middlename`,
	`lastname`,
	`suffix`,
	`nickname`,
	`addressnum`,
	`address1`,
	`city`,
	`state`,
	`zip`,
	`country`,
	`workphone`,
	`fax`,
	`mobile`,
	`email`,
	`password`,
	`mem_type`,
	`company`,
	`CompanyID`,
	`title`,
	`prefix`,
	`designation`,
	`website`,
	`asst_name`,
	`asst_phone`,
	`asst_email`,
	`webinterest`,
	`user_id`
	FROM `wp_aeh_import_full` AS t1
	JOIN `wp_usermeta` AS t2 ON `meta_key` = 'aeh_imis_id' AND `meta_value` = t1.ID
	WHERE `WP_post_ID` = ''
	ORDER BY `t1`.`rownum` ASC
	LIMIT " . MAX_WP_USERS_UPDATED;
	$results = $wpdb->get_results($sql);



	$string = ""; $n = 0; $t = 0;

	foreach ($results as $row){
		$rownum      = $row->rownum;
		$imis_id     = $row->ID;
		$firstname   = $row->firstname; //
		$middlename  = $row->middlename;//
		$lastname    = $row->lastname;  //
		$suffix      = $row->suffix;	//
		$nickname    = $row->nickname;  //
		$addressnum  = $row->addressnum;//
		$address1    = $row->address1;	//
		$city        = $row->city;		//
		$state       = $row->state;		//
		$zip         = $row->zip;		//
		$country     = $row->country;	//
		$workphone   = $row->workphone;	//
		$fax         = $row->fax;		//
		$mobile      = $row->mobile;	//
		$email       = $row->email;		//
		$password    = $row->password;	//
		$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
		$company     = $row->company;	//
		$co_id       = $row->CompanyID;	//

		$title       = $row->title;		//
		$prefix      = $row->prefix;	//
		$designation = $row->designation;
		$website     = $row->website;	//
		$asst_name   = $row->asst_name;	//
		$asst_phone  = $row->asst_phone;//
		$asst_email  = $row->asst_email;//
		$webinterest = explode(',',$row->webinterest);//
		$fullname    = $row->fullname;  //
		$wp_id       = $row->user_id;

		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('UPLOAD CHECK', '$wp_id')");



		update_user_meta($wp_id, 'first_name', $firstname);
		update_user_meta($wp_id, 'middle_name', $middlename);
		update_user_meta($wp_id, 'last_name', $lastname);
		update_user_meta($wp_id, 'nickname', $nickname);
		update_user_meta($wp_id, 'address_number', $addressnum);
		update_user_meta($wp_id, 'street_address', $address1);
		update_user_meta($wp_id, 'city', $city);
		update_user_meta($wp_id, 'state', $state);
		update_user_meta($wp_id, 'zip_code', $zip);
		update_user_meta($wp_id, 'country', $country);
		update_user_meta($wp_id, 'phone', $workphone);
		update_user_meta($wp_id, 'fax', $fax);
		update_user_meta($wp_id, 'CO_ID', $co_id);
		update_user_meta($wp_id, 'designation', $designation);
		update_user_meta($wp_id, 'mobile_phone', $mobile);
		update_user_meta($wp_id, 'aeh_staff', $mem_type);
		update_user_meta($wp_id, 'hospital_name', $company);
		update_user_meta($wp_id, 'job_title', $title);
		update_user_meta($wp_id, 'assistant_name', $asst_name);
		update_user_meta($wp_id, 'assistant_phone', $asst_phone);
		update_user_meta($wp_id, 'assistant_email', $asst_email);
		update_user_meta($wp_id, 'imisWebInterests', $webinterest);
		update_user_meta($wp_id, 'suffix', $suffix);
		update_user_meta($wp_id, 'prefix', $prefix);
		update_user_meta($wp_id, 'imis_verified', '1');
		update_user_meta($wp_id, 'role', 'member');
		//$the_user = new WP_User($wp_id); 
		//3$the_user->set_role('member');


		if ($password != 'XXXXXXzzzzzz'){update_user_meta($wp_id, 'aeh_password', $password); wp_set_password( $password, $wp_id );}

		//wp_update_user(array('ID' => $wp_id, 'user_url' => $website, 'user_email' => $email));
		$wpdb->query("UPDATE $wpdb->users SET user_email = '$email' WHERE ID =  '$id' ");




		$wpdb->query("UPDATE `wp_aeh_import_full` SET `WP_post_ID` = $wp_id WHERE `rownum` = $rownum AND `ID` = $imis_id");
		//$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('imis_verified: ', '$imis_id')");
		$n++; //$string .= "$rownum: $imis_id - $fullname\r\n";
	}
}




/**************************************** CHECK IF IMIS USER EXISTS FROM THEIR EMAIL ADDRESS **********************************************/
// if they do then return an array of key => value pairs. If an error return false.
function does_imis_user_exist($email){

	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_DOES_USER_EXIST,
		'parameters'      => "@email='$email'"
	);

	$result = post_request(IMIS_POST_URL, $params);
	//print_r($result);
	if ($result['status'] == 'ok'){ 									//if no status then an error occurred.
		$xml = simplexml_load_string($result['content']);
		if ($xml === false)return false;
		$xml = dom_import_simplexml($xml);
		if ($xml === false)return false;
		$nodelist = $xml->getElementsByTagName('Table');
		if ($nodelist->length==0)return false;
		$ID          = $nodelist->item(0)->getElementsByTagName('ID');
		$prefix      = $nodelist->item(0)->getElementsByTagName('PREFIX');
		$firstname   = $nodelist->item(0)->getElementsByTagName('FIRST_NAME');
		$middlename  = $nodelist->item(0)->getElementsByTagName('MIDDLE_NAME');
		$lastname    = $nodelist->item(0)->getElementsByTagName('LAST_NAME');
		$designation = $nodelist->item(0)->getElementsByTagName('DESIGNATION');
		//$informal    = $nodelist->item(0)->getElementsByTagName('INFORMAL');
		$workphone   = $nodelist->item(0)->getElementsByTagName('WORK_PHONE');
		$fax         = $nodelist->item(0)->getElementsByTagName('FAX');
		$suffix      = $nodelist->item(0)->getElementsByTagName('SUFFIX');
		$addressnum	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
		$address1	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_1');
		$city        = $nodelist->item(0)->getElementsByTagName('CITY');
		$state       = $nodelist->item(0)->getElementsByTagName('STATE_PROVINCE');
		$zip         = $nodelist->item(0)->getElementsByTagName('ZIP');
		$country     = $nodelist->item(0)->getElementsByTagName('COUNTRY');
		$email       = $nodelist->item(0)->getElementsByTagName('EMAIL');
		$password    = $nodelist->item(0)->getElementsByTagName('WEB_PASSWORD');
		$mem_type    = $nodelist->item(0)->getElementsByTagName('MEMBER_TYPE');
		$company     = $nodelist->item(0)->getElementsByTagName('COMPANY');
		$co_id       = $nodelist->item(0)->getElementsByTagName('CO_ID');
		$title       = $nodelist->item(0)->getElementsByTagName('TITLE');
		$website     = $nodelist->item(0)->getElementsByTagName('WEBSITE');
		$mobile      = $nodelist->item(0)->getElementsByTagName('MOBILE_PHONE');
		$asst_name   = $nodelist->item(0)->getElementsByTagName('ASSISTANT_NAME');
		$asst_phone  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_PHONE');
		$asst_email  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_EMAIL');
		$webinterest = $nodelist->item(0)->getElementsByTagName('WEB_INTERESTS');


		unset ($result);$result = array();
		$result[ID] = $ID->item(0)->nodeValue;
		$result[prefix] = $prefix->item(0)->nodeValue;
		$result[firstname] = $firstname->item(0)->nodeValue;
		$result[middlename] = $middlename->item(0)->nodeValue;
		$result[lastname] = $lastname->item(0)->nodeValue;
		$result[suffix] = $suffix->item(0)->nodeValue;
		$result[designation] = $designation->item(0)->nodeValue;
		//$result[informal] = $informal->item(0)->nodeValue;
		$result[workphone] = $workphone->item(0)->nodeValue;
		$result[fax] = $fax->item(0)->nodeValue;
		$result[addressnum] = $addressnum->item(0)->nodeValue;
		$result[address1] = $address1->item(0)->nodeValue;
		$result[city] = $city->item(0)->nodeValue;
		$result[state] = $state->item(0)->nodeValue;
		$result[zip] = $zip->item(0)->nodeValue;
		$result[country] = $country->item(0)->nodeValue;
		$result[email] = $email->item(0)->nodeValue;
		$result[password] = $password->item(0)->nodeValue;
		$result[mem_type] = $mem_type->item(0)->nodeValue;
		$result[company] = $company->item(0)->nodeValue;
		$result[companyID] = $co_id->item(0)->nodeValue;
		$result[title] = $title->item(0)->nodeValue;
		$result[website] = $website->item(0)->nodeValue;
		$result[mobile] = $mobile->item(0)->nodeValue;
		$result[asst_name] = $asst_name->item(0)->nodeValue;
		$result[asst_phone] = $asst_phone->item(0)->nodeValue;
		$result[asst_email] = $asst_email->item(0)->nodeValue;
		$result[webinterest] = $webinterest->item(0)->nodeValue;

		$the_status = $result['status'];
		global $wpdb;






		return $result;
	}else{
		return false;
	}
}


/******************************************* GET IMIS USER BY IMIS ID **********************************************/

function get_imis_user($imisuser){
	$addressnum = false;
	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_GET_IMIS_USER,
		'parameters'      => "@user=$imisuser"
	);

	$result = post_request(IMIS_POST_URL, $params);
	//print_r($result);
	if ($result['status'] == 'ok'){ 									//if no status then an error occurred.
		$xml = simplexml_load_string($result['content']);
		if ($xml === false)return false;
		$xml = dom_import_simplexml($xml);
		if ($xml === false)return false;
		$nodelist = $xml->getElementsByTagName('Table');
		if ($nodelist->length==0)return false;
		$ID          = $nodelist->item(0)->getElementsByTagName('ID');
		$prefix      = $nodelist->item(0)->getElementsByTagName('PREFIX');
		$firstname   = $nodelist->item(0)->getElementsByTagName('FIRST_NAME');
		$middlename  = $nodelist->item(0)->getElementsByTagName('MIDDLE_NAME');
		$lastname    = $nodelist->item(0)->getElementsByTagName('LAST_NAME');
		$designation = $nodelist->item(0)->getElementsByTagName('DESIGNATION');
		//$informal    = $nodelist->item(0)->getElementsByTagName('INFORMAL');
		$workphone   = $nodelist->item(0)->getElementsByTagName('WORK_PHONE');
		$fax         = $nodelist->item(0)->getElementsByTagName('FAX');
		$suffix      = $nodelist->item(0)->getElementsByTagName('SUFFIX');
		$addressnum	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
		$address1	 = $nodelist->item(0)->getElementsByTagName('ADDRESS_1');
		$city        = $nodelist->item(0)->getElementsByTagName('CITY');
		$state       = $nodelist->item(0)->getElementsByTagName('STATE_PROVINCE');
		$zip         = $nodelist->item(0)->getElementsByTagName('ZIP');
		$country     = $nodelist->item(0)->getElementsByTagName('COUNTRY');
		$email       = $nodelist->item(0)->getElementsByTagName('EMAIL');
		$password    = $nodelist->item(0)->getElementsByTagName('WEB_PASSWORD');
		$mem_type    = $nodelist->item(0)->getElementsByTagName('MEMBER_TYPE');
		$company     = $nodelist->item(0)->getElementsByTagName('COMPANY');
		$co_id       = $nodelist->item(0)->getElementsByTagName('CO_ID');
		$title       = $nodelist->item(0)->getElementsByTagName('TITLE');
		$website     = $nodelist->item(0)->getElementsByTagName('WEBSITE');
		$mobile      = $nodelist->item(0)->getElementsByTagName('MOBILE_PHONE');
		$asst_name   = $nodelist->item(0)->getElementsByTagName('ASSISTANT_NAME');
		$asst_phone  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_PHONE');
		$asst_email  = $nodelist->item(0)->getElementsByTagName('ASSISTANT_EMAIL');
		$webinterest = $nodelist->item(0)->getElementsByTagName('WEB_INTERESTS');


		unset ($result);$result = array();
		$result[ID] = $ID->item(0)->nodeValue;
		$result[prefix] = $prefix->item(0)->nodeValue;
		$result[firstname] = $firstname->item(0)->nodeValue;
		$result[middlename] = $middlename->item(0)->nodeValue;
		$result[lastname] = $lastname->item(0)->nodeValue;
		$result[suffix] = $suffix->item(0)->nodeValue;
		$result[designation] = $designation->item(0)->nodeValue;
		//$result[informal] = $informal->item(0)->nodeValue;
		$result[workphone] = $workphone->item(0)->nodeValue;
		$result[fax] = $fax->item(0)->nodeValue;
		$result[addressnum] = $addressnum->item(0)->nodeValue;
		$result[address1] = $address1->item(0)->nodeValue;
		$result[city] = $city->item(0)->nodeValue;
		$result[state] = $state->item(0)->nodeValue;
		$result[zip] = $zip->item(0)->nodeValue;
		$result[country] = $country->item(0)->nodeValue;
		$result[email] = $email->item(0)->nodeValue;
		$result[password] = $password->item(0)->nodeValue;
		$result[mem_type] = $mem_type->item(0)->nodeValue;
		$result[company] = $company->item(0)->nodeValue;
		$result[companyID] = $co_id->item(0)->nodeValue;
		$result[title] = $title->item(0)->nodeValue;
		$result[website] = $website->item(0)->nodeValue;
		$result[mobile] = $mobile->item(0)->nodeValue;
		$result[asst_name] = $asst_name->item(0)->nodeValue;
		$result[asst_phone] = $asst_phone->item(0)->nodeValue;
		$result[asst_email] = $asst_email->item(0)->nodeValue;
		$result[webinterest] = $webinterest->item(0)->nodeValue;

		$the_status = $result['status'];
		global $wpdb;

		$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('get mis user :$the_status', '$result[ID]  - $result[firstname]')");




		return $result;
	}else{
		return false;
	}
}



/******************************************* CRON TO TAKE iMIS VALUES AND DETERMINE/CREATE WP USERS **********************************************/

add_action('aeh_find_new_wp_users', 'find_new_wp_users');
function find_new_wp_users() { // call this cron from 1-4 hourly
	global $wpdb;
	$results = $wpdb->get_col("SELECT `ID` FROM `wp_aeh_import_full`");
	$current_ids = $wpdb->get_col("SELECT `meta_value` FROM `wp_usermeta` WHERE `meta_key`='aeh_imis_id' ");

	$new_diffs = array_diff($results,$current_ids);

 	$a = array();
 	foreach ($new_diffs as $new_diff){
		array_push($a, $new_diff);
	}
	var_dump($a);
	update_option('imis_users_to_add',$a);
	return $a;
}

add_action('aeh_insert_new_wp_users', 'insert_new_wp_users');
function insert_new_wp_users(){  // call this cron about 1-4 hourly but not at the same time as the above cron
	global $wpdb;

	$option = get_option('imis_users_to_add');

	foreach($option as $imis_id){
		$check = $wpdb->query("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'aeh_imis_id' AND `meta_value` = '$imis_id'"); //make sure this user doesn't already exist first
		if (!$check){ 														// if this user has been added from iMIS to WP then skip adding this user or else do the biz
			add_one_imis_user($imis_id);	
			echo $imis_id . "ADDED";								// loop here adding individual WP accounts programmatically.
		}
	}
}

//Adds new wordpress user from iMIS data
function add_one_imis_user($imis_id){
	global $wpdb;

	$row = $wpdb->get_row("SELECT * FROM `wp_aeh_import_full` WHERE `ID` = $imis_id"); // get all the iMIS parameters from the wp_imis_import_full table
	if (!$row)return false;
	$rownum      = $row->rownum;
	$firstname   = addslashes($row->firstname);
	$middlename  = addslashes($row->middlename);
	$lastname    = addslashes($row->lastname);
	$username    = addslashes($row->username);
	$suffix      = addslashes($row->suffix);
	$nickname    = addslashes($row->nickname);
	$addressnum  = addslashes($row->addressnum);
	$address1    = addslashes($row->address1);
	$city        = addslashes($row->city);
	$state       = addslashes($row->state);
	$zip         = addslashes($row->zip);
	$country     = addslashes($row->country);
	$workphone   = addslashes($row->workphone);
	$fax         = addslashes($row->fax);
	$mobile      = addslashes($row->mobile);
	$email       = addslashes($row->email);
	$password    = addslashes($row->password);
	$mem_type    = $row->mem_type; if ($mem_type == "STAFF"){$mem_type = 'Y';}else{$mem_type = 'N';}
	$company     = addslashes($row->company);
	$co_id       = addslashes($row->CompanyID);
	$title       = addslashes($row->title);
	$prefix      = addslashes($row->prefix);
	$designation = addslashes($row->designation);
	$website     = addslashes($row->website);
	$asst_name   = addslashes($row->asst_name);
	$asst_phone  = addslashes($row->asst_phone);
	$asst_email  = addslashes($row->asst_email);
	$webinterest = explode(',',$row->webinterest);
	$fullname    = addslashes($row->fullname);

	$userdata = array(
		'user_login'   =>  $username,
		'user_url'     =>  $website,
		'user_email'   =>  $email,
		'user_pass'    =>  $password,
		'first_name'   =>  $firstname,
		'display_name' =>  $fullname,
		'last_name'    =>  $lastname,
		'nickname'     =>  $nickname,
		'role'         =>  'member'
	);

	$wp_id = wp_insert_user($userdata);

	if( !is_wp_error($wp_id) ) {
	 echo "User created : ". $wp_id;
	}
	else{
		return $email;
	}


	//On success
	if(!is_wp_error($wp_id)){
		update_user_meta($wp_id, 'aeh_imis_id', $imis_id);
		update_user_meta($wp_id, 'aeh_member_type', 'hospital');
		update_user_meta($wp_id, 'role', 'member');
		update_user_meta($wp_id, 'aeh_password', $password);
		update_user_meta($wp_id, 'address_number', $addressnum);
		update_user_meta($wp_id, 'street_address', $address1);
		update_user_meta($wp_id, 'city', $city);
		update_user_meta($wp_id, 'state', $state);
		update_user_meta($wp_id, 'zip_code', $zip);
		update_user_meta($wp_id, 'country', $country);
		update_user_meta($wp_id, 'phone', $workphone);
		update_user_meta($wp_id, 'fax', $fax);
		update_user_meta($wp_id, 'designation', $designation);
		update_user_meta($wp_id, 'mobile_phone', $mobile);
		update_user_meta($wp_id, 'aeh_staff', $mem_type);
		update_user_meta($wp_id, 'hospital_name', $company);
		update_user_meta($wp_id, 'CO_ID', $co_id);
		update_user_meta($wp_id, 'job_title', $title);
		update_user_meta($wp_id, 'assistant_name', $asst_name);
		update_user_meta($wp_id, 'assistant_phone', $asst_phone);
		update_user_meta($wp_id, 'assistant_email', $asst_email);
		update_user_meta($wp_id, 'imisWebInterests', $webinterest);
		update_user_meta($wp_id, 'suffix', $suffix);
		update_user_meta($wp_id, 'title', $prefix);
		update_user_meta($wp_id, 'verified', 'true');
		update_user_meta($wp_id, 'imis_verified', 1);
	}

	$wpdb->query("INSERT INTO `test` (`name`, `value`) VALUES ('new wp user added : $wp_id', 'imis: $imis_id, $email')");
	return $wp_id; // if user added ok then $wp_id = the WP ID of the newly added user

}

/************************************ CUSTOM LOGIN FUNCTION TO RECORD LAST LOGIN TIME ************************************/

add_action('wp_login', 'check_custom_authentication', 10, 2);

function check_custom_authentication($user_login, $user) {

        //global $wpdb;

     	//if(!username_exists($username)) return;
		$user_id = $user->id;						//$user_id = WP ID field (user key)
		$logtime = date("Y-m-d H:i:s.000");

		$imis_id = (string)get_user_meta($user_id,'aeh_imis_id', TRUE);
		if ($imis_id!=""){

			$params = array(
				'securityPassword' => SP_SECURITY_PWD,
				'name' => SP_LOGIN_TIME,
				'parameters' => "@user_id = '$imis_id', @logtime ='$logtime'"
			);

			// Send a POST request to ibridge
			$result = post_request(SP_POST_UPDATE_URL, $params);
		}

        update_user_meta($user_id, 'user_last_login', $logtime);
		file_put_contents("userlog.txt", "$logtime $user_login ($user_id)\r\n", FILE_APPEND);
}


/******************************************* GET USER TITLES & WEB INTERESTS *********************************************/

add_action('aeh_get_imis_tables', 'get_imis_tables');				// get the unique user TITLE fields and store in wp_options
																	// also get the WEB_INTERESTS and store those in wp_options
function get_imis_tables(){





	//---------------APPROVED EMAIL LIST------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_EMAIL_LIST,
		'parameters' => ''
	);
	$domains = array();
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('EMAIL_SUFFIX');
					$domains[$i] = $t->item(0)->nodeValue;
					if($domains[$i]!= ''){
						//echo $i . " : " . $domains[$i] . "<br>";
						global $wpdb;
						$wpdb->query("INSERT INTO `wp_aeh_email` (`domain`, `organization`,staff) VALUES ('$domains[$i]', ' ', 'N')");

					}
				}

			}
		}
	}
	unset($xml);
	unset($result);


	//---------------USER TITLES------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_GET_TITLES,
		'parameters' => ''
	);
	$titles = array();
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('TITLE');
					$titles[$i] = $t->item(0)->nodeValue;
				}
				if($titles!= ''){
					update_option("user_titles", $titles);
				}
			}
		}
	}
	unset($xml);
	unset($result);


	//---------------WEB INTERESTS------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_WEB_INTERESTS,
		'parameters' => ''
	);
	$interests = array();

	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);
		//file_put_contents("webinterests.txt",$xml);return;

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('CODE');
					$interests['code'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('DESCRIPTION');
					$interests['description'][$i] = $t->item(0)->nodeValue;
				}
				if($titles!= '$interests'){
					update_option("user_web_interests", $interests);
				}
			}
		}
	}

	unset($xml);
	unset($result);



	//---------------COMPANY DROPDOWN LIST------------------------------------

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_COMPANY_LIST,
		'parameters' => ''
	);
	$companies = array();

	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);
		//file_put_contents("webinterests.txt",$xml);return;

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				for($i = 0; $i < $nodelist->length; $i++){
					$t = $nodelist->item($i)->getElementsByTagName('HQ');
					$companies['hq'][$i] = $t->item(0)->nodeValue;
       			    $t = $nodelist->item($i)->getElementsByTagName('ID');
          			$companies['id'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('COMPANY');
					$companies['company'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('COMPANY_SORT');
          			$companies['company_sort'][$i] = $t->item(0)->nodeValue;
          			$t = $nodelist->item($i)->getElementsByTagName('ADDRESS');
					$companies['address'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('CITY');
					$companies['city'][$i] = $t->item(0)->nodeValue;
					$t = $nodelist->item($i)->getElementsByTagName('STATE');
					$companies['state'][$i] = $t->item(0)->nodeValue;
          			$t = $nodelist->item($i)->getElementsByTagName('ZIP');
          			$companies['zip'][$i] = $t->item(0)->nodeValue;
          			$t = $nodelist->item($i)->getElementsByTagName('WORK_PHONE');
         			$companies['work_phone'][$i] = $t->item(0)->nodeValue;
          			$t = $nodelist->item($i)->getElementsByTagName('FAX');
          			$companies['fax'][$i] = $t->item(0)->nodeValue;

				}
				if($titles!= '$companies'){
					update_option("company_list", $companies);
				 
					print_r($companies);
					 
				}
			}
		}
	}

}

/********************************************** GET ROW COUNT **************************************************/

// find out the number of users in the iMIS DB (web users conforming to our criteria)
function get_imis_row_count(){

	$params = array(
		'securityPassword' => SP_SECURITY_PWD,
		'name' => SP_GET_ROW_COUNT,
		'parameters' => ''
	);
	$totalrows = false;
	// Send a POST request to ibridge
	$result = post_request(IMIS_POST_URL, $params);
	if ($result['status'] == 'ok'){ //if no status then an error occurred.

		$xml = simplexml_load_string($result['content']);

		if ($xml === false){
			//echo 'Error while parsing the document';

		}else{

			$xml = dom_import_simplexml($xml);

			if (!$xml) {
				//echo 'Error while converting XML';
			}else{
				$nodelist = $xml->getElementsByTagName('Table');
				$t = $nodelist->item(0)->getElementsByTagName('TOTAL_ROWS');
				$totalrows = $t->item(0)->nodeValue;
			}
		}
	}
	return $totalrows;
}
/********************************************** GET iMIS ADDRESS NUM **************************************************/




function get_address_num($imisuser){
	$addressnum = false;
	$params = array(
		'securityPassword'=> SP_SECURITY_PWD,
		'name'            => SP_GET_IMIS_USER,
		'parameters'      => "@user=$imisuser"
	);

	$result = post_request(IMIS_POST_URL, $params);
	$startunix = time();
	if ($result['status'] == 'ok'){ //if no status then an error occurred.
		$header = $result['header'];
		$xml = simplexml_load_string($result['content']);
		if ($xml !== false){
			$xml = dom_import_simplexml($xml);
			if ($xml){
				$nodelist = $xml->getElementsByTagName('Table');
				if ($nodelist->length>0){
					$addressnum	= $nodelist->item(0)->getElementsByTagName('ADDRESS_NUM');
					$addressnum = $addressnum->item(0)->nodeValue;
				}
			}
		}
	}
	return $addressnum;
}





/********************************************** POST REQUEST FUNCTION **************************************************/
function post_request($url, $data, $referer='') {

    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);

    // parse the given URL
    $url = parse_url($url);

    if ($url['scheme'] != 'http') {
        die('Error: Only HTTP request are supported !');
    }

    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];

    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);

    if ($fp){

        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");

        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");

        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);

        $result = '';
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 4096);
        }
    }
    else {
        return array(
            'status' => 'err',
            'error' => "$errstr ($errno)"
        );
    }

    // close the socket connection:
    fclose($fp);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);

    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * 			Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */

class XML2Array {

    private static $xml = null;
	private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
		if(is_string($input_xml)) {
			$parsed = $xml->loadXML($input_xml);
			if(!$parsed) {
				throw new Exception('[XML2Array] Error parsing the XML string.');
			}
		} else {
			if(get_class($input_xml) != 'DOMDocument') {
				throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
			}
			$xml = self::$xml = $input_xml;
		}
		$array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
		$output = array();

		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
				$output['@cdata'] = trim($node->textContent);
				break;

			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:

				// for each child node, call the covert function recursively
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::convert($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;

						// assume more nodes of same kind are coming
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					} else {
						//check if it is not an empty text node
						if($v !== '') {
							$output = $v;
						}
					}
				}

				if(is_array($output)) {
					// if only one node of its kind, assign it directly instead if array($value);
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1) {
							$output[$t] = $v[0];
						}
					}
					if(empty($output)) {
						//for empty nodes
						$output = '';
					}
				}

				// loop through the attributes and collect them
				if($node->attributes->length) {
					$a = array();
					foreach($node->attributes as $attrName => $attrNode) {
						$a[$attrName] = (string) $attrNode->value;
					}
					// if its an leaf node, store the value in @value instead of directly storing it.
					if(!is_array($output)) {
						$output = array('@value' => $output);
					}
					$output['@attributes'] = $a;
				}
				break;
		}
		return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}

/*************************************************************************************************************************************************/

