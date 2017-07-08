<?php
/* Wordpress template to create API for Android APP, QR reader code to check online and approve or dissaprove a ticket at club entrance.
?>

<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
 /*
 * Template Name: API
 */

if( !isset( $_GET['ACCESSCODE']) OR $_GET['ACCESSCODE'] != 'ACCESSCODE'  ) {
	exit('false');
}

if( $_GET['action'] == 'GETPASS' ):

	//get APP opening password.
	$password['password'] = get_option('PASSWORD_IDENTIFIER');
	if(!$password['password']) $password['password'] = 'DEFAULT_PASS';

	echo json_encode( $password );

endif;

if( $_GET['action'] == 'EVENTLIST' ):
	
	$query = $wpdb->get_results("
	
		SELECT ID, Name FROM (
			SELECT a.EventID AS ID, CONCAT(b.post_title, ' - BASS') AS Name, b.post_date as datee FROM TRANSACTIONS_TABLE a
				LEFT JOIN $wpdb->posts b
				ON a.EventID = b.ID 
				WHERE a.ResultCode = '000'
			
	        ORDER BY datee DESC
	    ) as t
    ");

	$newarr = new stdClass();
	$newarr->Events = $query;

	echo json_encode( $newarr );


endif;



if( $_GET['action'] == 'GetEventData' && isset( $_GET['eventID'] ) ):

	$eid = esc_sql($_GET['eventID']);

	$squery1 = $wpdb->get_results("SELECT a.ID as _id, a.UID as `barcode`, b.post_title as eventName, c.meta_value as UserNAME_IDENTIFIER, d.meta_value as UserID, a.sync AS status, a.ticketCount FROM TRANSACTIONS_TABLE a 
		LEFT JOIN $wpdb->posts b ON a.EventID = b.ID  
		LEFT JOIN $wpdb->usermeta c on a.UserID = c.user_id AND c.meta_key = 'NAME_IDENTIFIER'
		LEFT JOIN $wpdb->usermeta d on a.UserID = d.user_id AND d.meta_key = 'PID_IDENTIFIER'
		WHERE a.ResultCode = '000' AND a.STATUS_IDENTIFIER = '1' AND a.EventID = ".$eid."
               
	");
	
	$newarr = new stdClass();
	$newarr->Tickets = $squery1;

	echo json_encode( $newarr );

endif;



if( $_GET['action'] == 'GetEventDataSync' && isset( $_GET['eventID'] ) ):

	$eid = esc_sql($_GET['eventID']);

	$squery25 =  $wpdb->get_results("SELECT a.ID as _id, a.UID as `barcode`, b.post_title as eventName, c.meta_value as UserNAME_IDENTIFIER, d.meta_value as UserID, a.sync AS status, a.ticketCount FROM TRANSACTIONS_TABLE a 
		LEFT JOIN $wpdb->posts b ON a.EventID = b.ID  
		LEFT JOIN $wpdb->usermeta c on a.UserID = c.user_id AND c.meta_key = 'NAME_IDENTIFIER'
		LEFT JOIN $wpdb->usermeta d on a.UserID = d.user_id AND d.meta_key = 'PID_IDENTIFIER'
		WHERE a.ResultCode = '000' AND a.STATUS_IDENTIFIER = '1' AND a.EventID = ".$eid." AND a.sync > 0");


	$newarr = new stdClass();
	$newarr->Tickets = $squery25;

	echo json_encode( $newarr );

endif;


if( $_GET['action'] == 'checkStatus' && isset( $_GET['TicketID'] ) ):

	$tid = esc_sql($_GET['TicketID']);

	$cquery3 = $wpdb->get_results("SELECT sync, ticketCount FROM TRANSACTIONS_TABLE WHERE id = '". $tid."' ;");
	 if( !$cquery3 ) {
	  die('-1');
	 }
	else
	{
	 $dieMessage=sprintf("{\"TicketCount\":%d, \"ResponseCode\":%d}",$cquery3[0]->ticketCount,$cquery3[0]->sync);
   	 die($dieMessage);
	}

endif;

