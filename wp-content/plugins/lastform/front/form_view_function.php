<?php
if(! defined( 'ABSPATH' )) exit;
function origincode_contact_show_published_contact_1($id){
	global $wpdb;
	$id=absint($id);
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."origincode_contact_contacts_fields where origincode_contact_id = %d order by ordering DESC",$id);
	$rowim=$wpdb->get_results($query);
	$tablename = $wpdb->prefix . "origincode_contact_general_options";
	$query=$wpdb->prepare("SELECT * FROM %s order by id ASC",$tablename);
	$query=str_replace("'","",$query);
	$origincode_gen_opt=$wpdb->get_results($query);

    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."origincode_contact_contacts where id = %d order by id ASC",$id);
    $origincode_contact=$wpdb->get_results($query);
    if(empty($origincode_contact)){
        printf("Form with ID %d doesn't exist.", $id);
        return;
    }
    $origincode_contacteffect=$origincode_contact[0]->hc_yourstyle;

    $strquery = "SELECT * from " . $wpdb->prefix . "origincode_contact_general_options";

    $rowspar = $wpdb->get_results($strquery);

    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }
	$frontendformid = $id;

	$query = $wpdb->prepare("SELECT *  FROM " . $wpdb->prefix . "origincode_contact_style_fields WHERE options_name = %s",$origincode_contacteffect);
    $rows = $wpdb->get_results($query);
    $style_values = array();
    foreach ($rows as $row) {
        $key = $row->name;
        $value = $row->value;
        $style_values[$key] = $value;
    }
  
  $queryMessage="SELECT * FROM ".$wpdb->prefix."origincode_contact_submission where id = '".$id."'  order by id ASC";
  $messageInArrayFront = $wpdb->get_results($queryMessage); 
  return origincode_contact_front_end_origincode_contact($rowim, $paramssld, $origincode_contact, $frontendformid,$style_values,$origincode_gen_opt,$rowspar,$messageInArrayFront);
}


function origincode_contact_is_single_column($rows) {
	foreach ( $rows as $row ) {
		if ($row->hc_left_right === 'right') {
				return false;
		}
	}

	return true;
}



