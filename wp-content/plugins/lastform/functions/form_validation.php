<?php

if(! defined( 'ABSPATH' )) exit;
global $wpdb;
if(!isset($_SESSION))session_start();

function origincode_contact_set_html_content_type2(){
	return 'text/html';
}

function origincode_contact_contact_form_validation_callback(){
	define('ORIGINCODE_CONTACT_MB', 1048576);
	$submition_text = '';
	$sub_label = '';
	$files_url='';
	$files_type='';
	$checkBoxes='';
	$email='';
	$submition_errors='';
	////////////////////////////////get ip ////////////////////////////////////////

	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED' );
	} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED' );
	} elseif ( getenv( 'REMOTE_ADDR' ) ) {
		$ipaddress = getenv( 'REMOTE_ADDR' );
	} else {
		$ipaddress = 'UNKNOWN';
	}
	////////////////////////////////get ip ////////////////////////////////////////
	global $wpdb;
	$tablenameSub=$wpdb->prefix . "origincode_contact_submission";
	$query2="SELECT 'submission_ip','customer_spam' FROM " . $tablenameSub . " order by id ASC";
	$query2=str_replace("'","",$query2);
	$submissionSub=$wpdb->get_results($query2);
	$tablename = $wpdb->prefix . "origincode_contact_general_options";
	$query2="SELECT * FROM " . $tablename . " order by id ASC";
	$query2=str_replace("'","",$query2);
	$origincode_gen_opt=$wpdb->get_results($query2);

    $origincode_gen_opt_assoc=array();
    foreach($origincode_gen_opt as $key=>$origincode_gen_opt_single){
        $origincode_gen_opt_assoc[$origincode_gen_opt_single->name]=$origincode_gen_opt_single->value;
    }


	$spamError=$origincode_gen_opt_assoc['msg_refered_spam'];
	$all=$_POST['postData'];
	parse_str("$all",$myArray);
	$frontendformid = absint($_POST['formId']);
	$browser=sanitize_text_field($_POST['browser']);
	$_POSTED=$myArray;
	$query="SELECT * FROM ".$wpdb->prefix."origincode_contact_contacts_fields where origincode_contact_id = " . $frontendformid . " order by ordering ASC";
	$rowim=$wpdb->get_results($query);
	$email='';
	$emailArray='';
	$buttonsField='';
	$fileSize='';
	$afterSubmit='';
	$afterSubmitUrl='';
	foreach ($submissionSub as $submission) {
		if($submission->submission_ip==$ipaddress&&$submission->customer_spam==1){
			foreach ($rowim as $key=>$rowimages){
				$inputtype = $rowimages->conttype;
				if($inputtype == 'buttons'){
					$buttonsField='origin-contact-field-'.$rowimages->id;
				}
			}
			echo json_encode(array("markedAsSpam"=>$spamError,"spamButton"=>$buttonsField));
			exit;
		}
	}

	if(isset($_POSTED['submitok'])){

		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'origincode_contact_front_nonce')) {
			die('Authorization failed');
		}
		if($_POSTED['submitok'] == 'ok'){
			$thisdate = date("d.m.Y H:i");
			foreach ($rowim as $key=>$rowimages){
				$inputtype = $rowimages->conttype;
				$rowimages->hc_field_label=addslashes($rowimages->hc_field_label);
                $inputAllowedTypes=array('text','textarea','selectbox','checkbox','radio_box','file_box','e_mail','buttons','captcha','simple_captcha_box','hidden_field','page_break');
				if(in_array($inputtype,$inputAllowedTypes)){

					if($inputtype == 'captcha'){
						$url='https://www.google.com/recaptcha/api/siteverify';
						$privatekey=$origincode_gen_opt_assoc['form_captcha_private_key'];
						// Verify the post
						$req = wp_remote_post($url, array(
								'timeout' => 10,
								'body' => array(
									'secret' => $privatekey,
									'response' => $_POSTED['g-recaptcha-response'],
									'remoteip' => $ipaddress
								)
							)
						);

						// Was there an error posting ?
						if(is_wp_error($req)){
							$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['msg_captcha_error'].'*()*';
						}
					}

					if($inputtype=='simple_captcha_box'){
                        if(!isset($_POSTED['simple_captcha_'.$frontendformid.'']) || $_POSTED['simple_captcha_'.$frontendformid.'']==''){
                            $submition_errors.='simple_captcha_'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';
                        }
                        else{
                            if($_POSTED['simple_captcha_'.$frontendformid.'']!=$_SESSION['origincode_contact_captcha-user-'.$rowimages->id.'-'.$rowimages->id.$_POST['time']]){
                                $simple_captcha_error_message=($origincode_gen_opt_assoc['msg_simple_captcha_error'])?$origincode_gen_opt_assoc['msg_simple_captcha_error']:'Incorrect Input';
                                $submition_errors.='simple_captcha_'.$rowimages->id.':'.$simple_captcha_error_message.'*()*';
                            }
                        }
                    }

					if($inputtype == 'buttons'){
						$buttonsField='origin-contact-field-'.$rowimages->id;
						$afterSubmit=$rowimages->hc_other_field;
						$afterSubmitUrl=$rowimages->field_type;
					}

					if($inputtype == 'text' or $inputtype == 'textarea'){
						if(!isset($_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]='';
						$contactField=$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id];
						if($rowimages->hc_required=='on'&&$contactField==''){$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';}else{

						}
					}

					if($inputtype == 'selectbox'){
						if(!isset($_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]='';
						$contactField=$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id];
						if($rowimages->hc_required=='on'&&$contactField==''){$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';}else{

						}
					}

					if($inputtype == 'e_mail'){
						if(!isset($_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]='';
						$email=	$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&$email!='')||$rowimages->hc_required!='on'){
							if(is_email($email)||$email==''){
								$emailArray.=$email.'*()*';
							}else{
								$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['msg_invalid_email'].'*()*';
							}
						}else{
							$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';
						}
					}

					$checkBoxes='';
					if($inputtype == 'checkbox'){
						if(!isset($_POSTED['check_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['check_'.$frontendformid.'_'.$rowimages->id]='';
						$checkbox=$_POSTED['check_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&$checkbox!='')||$rowimages->hc_required!='on'){
							$options=explode(';;',$rowimages->name);
							foreach($options as $keys=>$option){
								if(isset($_POSTED['check_'.$frontendformid.'_'.$rowimages->id]['origincode_'.$frontendformid.'_'.$rowimages->id.'_'.$keys])){
									$checkBoxes .= $_POSTED['check_'.$frontendformid.'_'.$rowimages->id]['origincode_'.$frontendformid.'_'.$rowimages->id.'_'.$keys].',';
								}
							}
							$sub_label.= $rowimages->hc_field_label.'*()*';
							$checkBoxes=substr_replace($checkBoxes, "", -1);
							$submition_text.= $checkBoxes.'*()*';
						}else{
							$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';
						}
					}

					$fullname='';
					if($inputtype == 'nameSurname'){
						if(!isset($_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id]='';
						$fullname=$_POSTED['fullName_'.$frontendformid.'_'.$rowimages->id];
						if(($rowimages->hc_required=='on'&&($fullname['origincode_1']!=''&&$fullname['origincode_2']!=''))||$rowimages->hc_required!='on'){
							$sub_label.=$rowimages->hc_field_label.'*()*';
							if($fullname['origincode_1']!=''&&$fullname['origincode_2']!=''){
								$submition_text.=$fullname['origincode_1'].' '.$fullname['origincode_2'].'*()*';
							}
						}else{
							$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';
						}
					}

					if($inputtype == 'phone'){
						if(!isset($_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]))$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]='';
						$phoneNum=$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id];
						if(!(($rowimages->hc_required=='on'&&$phoneNum!='')||$rowimages->hc_required!='on')){
							$submition_errors.='origin-contact-field-'.$rowimages->id.':'.$origincode_gen_opt_assoc['required_empty_field'].'*()*';
						}
					}

					if($inputtype == 'license'){
						if(!isset($_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id])){
							$_POSTED['origincode_'.$frontendformid.'_'.$rowimages->id]='';
							$submition_errors.='origin-contact-field-'.$rowimages->id.': Please tick on checkbox*()*';
						}
					}

					if ( $inputtype == 'file_box' ) {
						if ( ( $rowimages->hc_required == 'on' && isset( $_FILES[ 'userfile_' . $rowimages->id ] ) ) || $rowimages->hc_required != 'on' ) {
							require_once( "mime_types.php" );
							$user_mime_types       = $rowimages->hc_other_field;
							$user_mime_types_array = array_filter( explode( ',', $user_mime_types ), 'strlen' );
							foreach ( $user_mime_types_array as $key => $value ) {
								$user_mime_types_array[ $key ] = trim( $value );
							}
							$result_array = array();
							foreach ( $user_mime_types_array as $key => $uservalue ) {
								/**
								 * @var array $origincode_mime_types
								 */
								foreach ( $origincode_mime_types as $origincode_key => $value ) {
									if ( preg_match( "/" . $uservalue . "/", $origincode_key ) ) {
										$result_array[ $origincode_key ] = $value;
									}
								}
							}
							if ( isset( $_FILES[ 'userfile_' . $rowimages->id ] ) && ! empty( $_FILES[ 'userfile_' . $rowimages->id ]['tmp_name'] ) ) {
								/*Checking Type*/
                                if ( ! in_array( $_FILES[ 'userfile_' . $rowimages->id ]['type'], $result_array ) ) {
                                    $origc_file_name = $_FILES[ 'userfile_' . $rowimages->id ]['name'];
                                    if(!in_array (strtolower(substr($origc_file_name,strrpos($origc_file_name,".") + 1)), $user_mime_types_array ) ){
                                        $submition_errors .= 'origin-contact-field-' . $rowimages->id . ':' . $origincode_gen_opt_assoc['msg_file_format'] . '*()*';
                                    }

								}
								//Checking FileSize
								$fileSize = $rowimages->name;
								if ( $_FILES[ 'userfile_' . $rowimages->id ]['size'] > $fileSize * ORIGINCODE_CONTACT_MB ) {
									$submition_errors .= 'origin-contact-field-' . $rowimages->id . ':' . $origincode_gen_opt_assoc['msg_large_file'] . '*()*';
								}
							}
						} else {
							$submition_errors .= 'origin-contact-field-' . $rowimages->id . ':' . $origincode_gen_opt_assoc['required_empty_field'] . '*()*';
						}


					}

					if ( ! isset( $_POSTED[ 'origincode_' . $frontendformid . '_' . $rowimages->id ] ) ) {
						$_POSTED[ 'origincode_' . $frontendformid . '_' . $rowimages->id ] = '';
					}

					if ( $inputtype != 'checkbox' && $inputtype != 'nameSurname' ) {
						$submition_text .= $_POSTED[ 'origincode_' . $frontendformid . '_' . $rowimages->id ] . '*()*';
						$sub_label .= $rowimages->hc_field_label . '*()*';
					}
				}
			}
			// if there are no validation errors, proceed
			if($submition_errors==''){
				if(isset($_FILES)){
					foreach ($_FILES as $keyofFile=>$fileSingle) {
						include_once ABSPATH . 'wp-admin/includes/media.php';
						include_once ABSPATH . 'wp-admin/includes/file.php';
						include_once ABSPATH . 'wp-admin/includes/image.php';
						require_once("mime_types.php");
						$user_mime_types=$rowimages->hc_other_field;
						$user_mime_types_array=array_filter(explode(',',$user_mime_types),'strlen');
						foreach ($user_mime_types_array as $key => $value) {
							$user_mime_types_array[$key] = trim($value);
						}
						$result_array=array();
						foreach ($user_mime_types_array as $key => $uservalue) {
							foreach ($origincode_mime_types as $origincode_key => $value) {
								if(preg_match("/".$uservalue."/",$origincode_key)){
									$result_array[$origincode_key]=$value;
								}
							}
						}
						$overrides = array('test_form' => false,'mimes' => $result_array);
						$int = filter_var($keyofFile, FILTER_SANITIZE_NUMBER_INT);

						$fieldPath=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."origincode_contact_contacts_fields where id = " . $int);
						$fieldPath=$fieldPath[0]->field_type;
						global $filePath;
						if ( $fieldPath == '' ) {
							$filePath = '';
						} else {
							$filePath = '/' . $fieldPath;
						}
						add_filter( 'upload_dir', 'origincode_contact_origin_upl' );
						if ( ! function_exists( 'origincode_contact_origin_upl' ) ) {
							function origincode_contact_origin_upl( $dir ) {
								global $filePath;

								return array(
									       'path'   => $dir['basedir'] . $filePath,
									       'url'    => $dir['baseurl'] . $filePath,
									       'subdir' => $filePath,
								       ) + $dir;
							}
						}
                        define('ALLOW_UNFILTERED_UPLOADS', true);
						$file = wp_handle_upload( $_FILES[ $keyofFile ], $overrides );

						if ( ! isset( $file['error'] ) ) {
							$files_url .= $file['url'] . '*()*';
							$files_type .= $file['type'] . '*()*';
							remove_filter( 'upload_dir', 'origincode_contact_origin_upl_remove' );
							if ( ! function_exists( 'origincode_contact_origin_upl_remove' ) ) {
								function origincode_contact_origin_upl_remove( $dir ) {
									global $filePath;

									return array(
										       'path'   => $dir['basedir'] . $filePath,
										       'url'    => $dir['baseurl'] . $filePath,
										       'subdir' => $filePath,
									       ) + $dir;
								}
							}
						}
					}
				}
				$emailArray=array_filter(explode('*()*',$emailArray),'strlen');
				$email_form_id=$frontendformid;

				$messagelabbelsexp = array_filter(explode("*()*", $sub_label),'strlen');
				$messagesubmisexp = explode("*()*", $submition_text);
				$adminSub='<table class="message-block">';
				$separator=':';
				foreach($messagelabbelsexp as $key=>$messagelabbelsexpls){
					$messagelabbelsexpls=stripslashes($messagelabbelsexpls);
					if($messagesubmisexp[$key]!=''){
						$adminSub.='<tr><td><strong>'.$messagelabbelsexpls.'</strong>'.$separator.' '.$messagesubmisexp[$key].'</td></tr>';
					}
				}
				$adminSub.='</table>';

				foreach ($emailArray as  $emailSingle) {
                    $subscriber_exists = $wpdb->get_var( "SELECT count(subscriber_id) FROM " . $wpdb->prefix . "origincode_contact_subscribers WHERE subscriber_form_id={$email_form_id} AND subscriber_email='".$emailSingle."' ", ARRAY_A );


                    if (!$subscriber_exists ) {
                        $table_name   = $wpdb->prefix . "origincode_contact_subscribers";
                        $email_insert = " INSERT INTO `" . $table_name . "` (`subscriber_form_id`,`subscriber_email`) VALUES (" . $email_form_id . ",'" . $emailSingle . "')";
                        $wpdb->query( $email_insert );
                    }

                    // Send Email to the user
					if($origincode_gen_opt_assoc['form_send_to_email_user']=='on'){
						if(isset($_POSTED['hc_email_r'])){
							$subject=$origincode_gen_opt_assoc['form_user_message_subject'];
							$sendmessage=wp_kses_post(html_entity_decode($origincode_gen_opt_assoc['form_user_message']));
							add_filter( 'wp_mail_content_type', 'origincode_contact_set_html_content_type2' );
							$messagelabbelsexp = array_filter(explode("*()*", $sub_label),'strlen');
							$messagesubmisexp = explode("*()*", $submition_text);
							$userSub='<table class="message-block">';
							$separator=':';
							foreach($messagelabbelsexp as $key=>$messagelabbelsexpls){
								$messagelabbelsexpls=stripslashes($messagelabbelsexpls);
								if($messagesubmisexp[$key]!=''){
									$userSub.='<tr>
											<td><strong>'.$messagelabbelsexpls.'</strong>'.$separator.' '.$messagesubmisexp[$key].'</td>
										</tr>';
								}
							}
							$userSub.='</table>';

                            $userSub = preg_replace('/\$(\d)/', '\\\$$1', $userSub);
                            $sendmessage=preg_replace('/{userContent}/', $userSub, $sendmessage);
							$headers = array('From: '.$origincode_gen_opt_assoc['form_adminstrator_user_name'].' <'.$origincode_gen_opt_assoc['form_adminstrator_user_mail'].'>');

							//------------------if subject empty sends the name of the form
							if(empty($subject)){
								$query = "SELECT name from " . $wpdb->prefix . "origincode_contact_contacts where id = " . $frontendformid;
								$subject = $wpdb->get_var( $query );
							}

							$sendmessage=preg_replace('/{userMessage}/', $adminSub, $sendmessage);

							wp_mail($emailSingle, $subject, $sendmessage,$headers);
							remove_filter( 'wp_mail_content_type', 'origincode_contact_set_html_content_type2' );
						}
					}
				}

				// Send Email to Admin
				if($origincode_gen_opt_assoc['form_send_email_for_each_submition']=='on'){
					function origincode_contact_set_html_content_type() {
						return 'text/html';
					}

					$subject=$origincode_gen_opt_assoc['form_message_subject'];
					$sendmessage=$origincode_gen_opt_assoc['form_adminstrator_message'];
					$email=$origincode_gen_opt_assoc['form_adminstrator_email'];
					add_filter( 'wp_mail_content_type', 'origincode_contact_set_html_content_type' );

					$attachments = array();
					$fileUrls = array_filter(explode("*()*", $files_url),'strlen');
					foreach ($fileUrls as $key => $value) {
						$link_pattern='/^(.*)\/uploads\//';
						$file_path=preg_replace($link_pattern,'',$value);
						array_push($attachments, WP_CONTENT_DIR . '/uploads/'.$file_path);
					}

                    $adminSub = preg_replace('/\$(\d)/', '\\\$$1', $adminSub);

                    $sendmessage = preg_replace( '/{formContent}/', $adminSub, $sendmessage );
					$sendmessage = html_entity_decode( $sendmessage );
					$sendmessage = wp_kses_post( $sendmessage );
                    if($origincode_gen_opt_assoc['form_save_reply_to_user']=="on" && !empty($emailArray)){
                        $origincode_gen_opt_assoc['form_adminstrator_user_mail']= $emailArray[0];
                    }
					$headers = array('From: '.$origincode_gen_opt_assoc['form_adminstrator_user_name'].' <'.$origincode_gen_opt_assoc['form_adminstrator_user_mail'].'>');

					//------------------if subject empty sends the name of the form
					if(empty($subject)){
						$query = "SELECT name  from " . $wpdb->prefix . "origincode_contact_contacts where id = " . $frontendformid;
						$select_res = $wpdb->get_var( $query );
						$subject = $select_res;
					}
					wp_mail($email, $subject, $sendmessage,$headers,$attachments);
					remove_filter( 'wp_mail_content_type', 'origincode_contact_set_html_content_type' );

				}

				/* save submissions to database */
				if($origincode_gen_opt_assoc['form_save_to_database']=='on'){
					$table_name = $wpdb->prefix . "origincode_contact_submission";
					$wpdb->insert(
						$table_name,
						array(
							'contact_id' => $frontendformid,
							'sub_labels' => $sub_label,
							'submission' => $submition_text,
							'submission_date' => date('Y-m-d h:i:s'),
							'submission_ip' => $ipaddress.'*()*'.$browser,
							'customer_country' => '(Only In Pro)',
							'customer_spam' => '0',
							'customer_read_or_not' => '0',
							'files_url' => $files_url,
							'files_type' => $files_type,
						),
						array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
					);
				}
				$success_message=$origincode_gen_opt_assoc['msg_send_success'];
				echo json_encode(array("success"=>$success_message,"buttons"=>$buttonsField,"afterSubmit"=>$afterSubmit,"afterSubmitUrl"=>$afterSubmitUrl));
			}else{
				$submition_errors_array=array();
				$submition_errors=array_filter(explode('*()*',$submition_errors),'strlen');
				foreach ($submition_errors as $key => $value) {
					$value=array_filter(explode(':',$value),'strlen');
					$submition_errors_array[$value[0]]=$value[1];
				}
				echo json_encode(array("errors"=>$submition_errors_array));
			}
		}
	}

	die();
}
