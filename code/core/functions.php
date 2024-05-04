<?php
class Functions{

    public $recapatcha_ve_secret_key='6Lcv0NQZAAAAAHkixTIvcxdYOmZsS3EDoVYc1sBg';
 
    //Added by Shahbaz
    public function verify_recaptcha($token, $action){
        $params = [
            'secret' => $this->recapatcha_ve_secret_key,
            'response' => $token,
        ];

        // call curl to POST request
        $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $arrResponse = json_decode($response, true);

    
        // verify the response
        if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
            return true;
        } else {
            return false;
            //$strMSG = '<div class="alert alert-danger" style="width:100%;">You are a Bot!</div>';
        }

    }

	public function udt_last_activity_time($user_id, $check = 0){
		$retValue = array("status"  => 1, "message" => "Session Checked!");
		if($user_id > 0){
			if($check==1){
				//$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM `users` WHERE `user_id`='".$user_id."' AND `user_last_activity` > DATE_SUB(NOW(),INTERVAL 5 MINUTE)");
				$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM `users` WHERE `user_id`='".$user_id."' AND `user_last_activity` > DATE_SUB(NOW(),INTERVAL ".$GLOBALS['session_time']." MINUTE)");
				if(mysqli_num_rows($rs) > 0){
					mysqli_query($GLOBALS['conn'], "UPDATE users SET user_last_activity='".date("Y-m-d H:i:s")."' WHERE user_id='".$user_id."'");
				} else{
					//$retValue = array("status"  => -1, "message" => "Session Time out!");
				}
			}
			else{
				mysqli_query($GLOBALS['conn'], "UPDATE users SET user_last_activity='".date("Y-m-d H:i:s")."' WHERE user_id='".$user_id."'");
			}
		}
		return $retValue;
	}

	public function generate_token($id, $email){
		$time = time();
		$id = str_pad(dechex((int) $id), 8, '0', STR_PAD_LEFT);
		$time = str_pad(dechex((int) $time), 8, '0', STR_PAD_LEFT);
		$email = explode('@', $email);
		$user = $email[0];
		$domain = empty($email[1])?'':$email[1];
		$user = str_pad(bin2hex (substr($user, 0, 4)), 8, '0', STR_PAD_LEFT);
		$domain = str_pad(bin2hex (substr($domain,0, 4)), 8, '0', STR_PAD_LEFT);
		$token = [0 => '', 1 => '', 2 => '', 3 => ''];
		for($i = 0; $i<4;$i++){
			$token[0] .= $user[$i].$domain[$i];
			$token[1] .= $id[$i].$time[$i];
			$token[2] .= $user[4+$i].$domain[4+$i];
			$token[3] .= $id[4+$i].$time[4+$i];
		}
		return implode('', $token);
	}

	public function decode($token){
		if(strlen($token) < 32){
			return false;
		}
		$id = $time = $user = $domain = [0 => '', 1 => ''];
		for($i = 0; $i<4;$i++){
			$id[0] .= $token[8+2*$i];
			$id[1] .= $token[24+2*$i];
			$time[0] .= $token[9+2*$i];
			$time[1] .= $token[25+2*$i];
			$user[0] .= $token[2*$i];
			$user[1] .= $token[16+2*$i];
			$domain[0] .= $token[1+2*$i];
			$domain[1] .= $token[17+2*$i];
		}
		$id = hexdec(implode('', $id));
		$time = hexdec(implode('', $time));
		$user = ltrim(hex2bin(implode('', $user)), hex2bin('00')); //ltrim against str_pad in generate token
		$domain = ltrim(hex2bin(implode('', $domain)), hex2bin('00'));
		return [
			'id' => $id,
			'time' => $time,
			'user' => $user,
			'domain' => $domain,
		];
	}

	public function retFrequencyDays($frq){
		$str = 0;
		if($frq=='OnePerYear'){
			$str = 360;
		} elseif($frq=='TwoPerYear'){
			$str = 180;
		} elseif($frq=='FourPerYear'){
			$str = 90;
		} elseif($frq=='OnePerMonth'){
			$str = 30;
		} elseif($frq=='SixPerYear'){
			$str = 60;
		} elseif($frq=='TwelvePerYear'){
			$str = 30;
		} elseif($frq=='InFine'){
			$str = 0;
		} elseif($frq=='None'){
			$str = 0;
		}
		return $str;
	}

	/* public function getByToken($token, $details = false){
		$customer = false;
		$expired = '0';
		$row_array = array();
		$data = decode($token);
		if(!empty($data)
		&& !empty($data['id'])
		&& !empty($data['time'])
		&& !empty($data['user'])
		&& !empty($data['domain'])){
			$Query = "select sql_cache u.user_id, u.user_name, u.user_password, u.urole_id, u.saas_plan_id from users as u USE INDEX (`user_id`) where 
				u.user_id = ".$data['id']." and 
				u.user_name LIKE '".safe2($data['user'])."%@".$data['domain']."%' and 
				u.status_id = 1
				limit 1";
			$rs = mysqli_query($GLOBALS['conn'], $Query) or die(mysqli_error($GLOBALS['conn']));
			if(mysqli_num_rows($rs) > 0){
				$row = mysqli_fetch_object($rs);
				if($data['time'] < strtotime(VALIDITY_TIME)){
					$expired = '1';
				}
				$row_array = array(
					'user_id' => $row->user_id, 
					'user_name' => $row->user_name,
					'user_password' => $row->user_password,
					'expired' => $expired,
					'urole_id' => $row->urole_id,
					'saas_plan_id' => $row->saas_plan_id
				);
			}
		}
		return $row_array;
	} */

	public function generate_pdf($trade_id){
		$data = "";
		$url = $GLOBALS['siteURL']."invoice/trade_invoice.php?trade_id=".$trade_id."&save=1";
        $ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		//curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		//curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	public function send_notification($uIDs, $sendToAdmins, $params){
        $json=array();
		$json['error'] = '';
		$json['msg'] = '';
		$receiver_id = array();
		if(empty($uIDs) && $sendToAdmins==0){
			$json['error'] = '1';
            $json['msg'] = 'not successfull';
		} else {
			//Credentials
			$icon = 'https://devnewco.oyas.info/admin/assets/images/favicon.ico';
			$url = "https://fcm.googleapis.com/fcm/send";
			$server_key = "AAAA83ZPvho:APA91bHVnaSCScS3CtQDrJ1TT7F01vFZQ9voFOoH3EjNtpAeh6i-KjjEJfA2uTDljX6lwis-335NXhLQWF3qQh-tnV9gl6SVTpmTGI72kUlx3yJffT9NCbSshInOosE0NTXfH_BG7oE9";
			
			$headers = array(
				'Authorization: key='.$server_key,
				'Content-Type: application/json;charset=UTF-8'
            );
			//For Custom Message
			$ntm_title = '';
			if(isset($params['title']) && !empty($params['title'])){
				$ntm_title = $params['title'];
			}
			$ntm_body = '';
			if(isset($params['message']) && !empty($params['message'])){
				$ntm_body = $params['message'];
            }
			$click_url = '';
			if(isset($params['noti_link']) && !empty($params['noti_link'])){
				// $click_url = $params['noti_link'];
				$noti_link = str_replace("admin/#/admin/#/admin/", "admin/", $params['noti_link']);
				$noti_link = str_replace("admin/#/admin/", "admin/", $noti_link);
				$click_url = $GLOBALS['siteURL']."admin/#/".$noti_link;
			}
			$send_multiple = 0;
			$deviceToken = '';
			$gcmRegIds = array();
			//$QueryDT = "SELECT d.* FROM notification_devices AS d WHERE d.urole_id > 1 AND d.user_id IN (".$uIDs.") ORDER BY d.ntd_id DESC LIMIT 0,50";
			$QueryDT = "SELECT d.* FROM notification_devices AS d WHERE d.user_id IN (".$uIDs.") ORDER BY d.ntd_id DESC LIMIT 0,50";
            $rsDT = mysqli_query($GLOBALS['conn'], $QueryDT);
			$rCnt = mysqli_num_rows($rsDT);
			if($rCnt > 0){
				if($rCnt==1){
					$rowDT = mysqli_fetch_object($rsDT);
					$deviceToken = $rowDT->user_device_id;
					array_push($receiver_id, $rowDT->user_id);
				}
				else{
					while ($rowDT = mysqli_fetch_object($rsDT)){
						array_push($gcmRegIds, $rowDT->user_device_id);
						array_push($receiver_id, $rowDT->user_id);
						$send_multiple = 1;
					}
				}
			}
			//print("NOTI - 2 ");
			if($sendToAdmins == 1){
				//Send to all admins
				$send_multiple = 1;
                $QueryDT = "SELECT d.* FROM notification_devices AS d WHERE d.user_id IN (SELECT CONCAT(u.user_id) FROM users AS u WHERE u.urole_id=1)";
				$rsDT = mysqli_query($GLOBALS['conn'], $QueryDT);
				if(mysqli_num_rows($rsDT) > 0){
					while ($rowDT = mysqli_fetch_object($rsDT)){
						if(!empty($rowDT->user_device_id)){
							array_push($gcmRegIds, $rowDT->user_device_id);
							array_push($receiver_id, $rowDT->user_id);
						}
					}
                }
				//$receiver_ids = array_unique($receiver_id, SORT_NUMERIC);
				$gcmRegIDs = array_unique($gcmRegIds);
				/* print("<pre> Receiver ID: ");
				print_r($receiver_ids);
				print("</pre>");
				print("<pre> gcmRegIds: ");
				print_r($gcmRegIDs);
				print("</pre>");
				die(); */
			}

			if($send_multiple==1){
				$data = array(
					'registration_ids' => $gcmRegIds,
					'priority' => 'high',
					'notification' => array( 
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						//'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
					'data' => array (
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						//'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
                );
			}
			//$deviceToken = "fouWS9kwGL4ggyMXmfOfG_:APA91bFSaeamgAhXkW1BRisofOKBsoo1idzO759DzfWyc_2y3KQRu7SOpZSzSZ9IgVHT9hvg-tPEhCELETJevkq2tMVDXJbJeQEZAtTeniE0wz3X6SP5_vr8l6qYJxI9VOmnzXjvaRnf";
			//$deviceToken = "d-osQg-VoHf9LFj99mQ5v9:APA91bHwdqknpy8CkLRVe4DG7jIX31inKC4v-_9wVjxxETZisbotgL91yGI4GzWNm-SGLc5mWyZvnKAFNeE_396md5iAkEnc6IFla9TH3q-A9PRcFe6oKOWTtHJM7-PL5Jc3rWEywJqc";
			if(!empty($deviceToken)){
				$data = array(
					'to' => $deviceToken,
					'priority' => 'high',
					'notification' => array( 
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						//'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
					'data' => array (
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						//'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
					'webpush' => array(
						'fcm_options' => array(
							'link' => $click_url
						),
					),
				);
			}
			/* print("<pre>");
			print_r($data);
			print("</pre>");
			die(); */
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($ch);
            
			curl_close($ch);
	
            $arr = json_decode($result, true);
			/* print("<pre>");
            print_r($arr);
			print("</pre>"); */
			//die();
			$multicast_id = 0;
			if(isset($arr['multicast_id'])){
				$multicast_id = $arr['multicast_id'];
			}
			$noti_send = 1;
			$error = '';
			//if(isset($arr['results'][0]['error'])){
				//$noti_send = 0; 
				//$error = $arr['results'][0]['error'];
				/* $Qry = "UPDATE notification_devices set user_device_registered = '0', error = '".$error."' where ntd_id = '".$ntd_id."' ";
				mysqli_query($GLOBALS['conn'], $Qry); */
			//}
			$receiver_ids = array_unique($receiver_id, SORT_NUMERIC);
			$noti_sender_id = 0;
			if(isset($params['noti_sender_id']) && $params['noti_sender_id']>0){
				$noti_sender_id = $params['noti_sender_id'];
				$noti_send = 1;
			}
			//$noti_receiver_id = $params['noti_receiver_id'];
			$ntype_id = $params['ntype_id'];
			$nevnt_id = $params['nevnt_id'];
			$noti_title = $params['title'];
			$noti_content = $params['message'];
			$noti_link = str_replace("admin/#/admin/#/admin/", "admin/", $params['noti_link']);
			$noti_link = str_replace("admin/#/admin/", "admin/", $noti_link);
			$noti_id = $this->getMaximum("notifications", "noti_id");
			//print_r($receiver_ids);
			//for($i=0; $i<count($receiver_ids); $i++){
			foreach($receiver_ids as $rec_id){
				//$strQry = "INSERT INTO notifications (noti_id, noti_sender_id, noti_receiver_id, ntype_id, nevnt_id, noti_title, noti_content, noti_date, noti_link, multicast_id, noti_error, noti_sending_status) VALUES (" . $noti_id . ", '".$noti_sender_id."', '".$receiver_ids[$i]."', '".$ntype_id."', '".$nevnt_id."', '" . $this->dbStr($noti_title) . "', '" . $this->dbStr($noti_content) . "', '".date("Y-m-d")."', '" . $this->dbStr($noti_link) . "', '".$multicast_id."', '" . $this->dbStr($error) . "', '".$noti_send."')";
				//$strQry = "INSERT INTO notifications (noti_id, noti_sender_id, noti_receiver_id, ntype_id, nevnt_id, noti_title, noti_content, noti_date, noti_link, multicast_id, noti_sending_status) VALUES (" . $noti_id . ", '".$noti_sender_id."', '".$receiver_ids[$i]."', '".$ntype_id."', '".$nevnt_id."', '" . $this->dbStr($noti_title) . "', '" . $this->dbStr($noti_content) . "', '".date("Y-m-d H:i:s")."', '" . $this->dbStr($noti_link) . "', '".$multicast_id."', '".$noti_send."')";
				$strQry = "INSERT INTO notifications (noti_id, noti_sender_id, noti_receiver_id, ntype_id, nevnt_id, noti_title, noti_content, noti_date, noti_link, multicast_id, noti_sending_status) VALUES (" . $noti_id . ", '".$noti_sender_id."', '".$rec_id."', '".$ntype_id."', '".$nevnt_id."', '" . $this->dbStr($noti_title) . "', '" . $this->dbStr($noti_content) . "', '".date("Y-m-d H:i:s")."', '" . $this->dbStr($noti_link) . "', '".$multicast_id."', '".$noti_send."')";
				//print($strQry."<br>");
				mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
				$noti_id++;
			}
			$json['error'] = '0';
			$json['msg'] = 'success';
            $json['result'] = $result;
			/* print("<pre>");
			print_r($json);
			print("</pre>"); */
			//die();
            return $json;
			
		}
	}

    public function send_notification_message($lang_id, $ntt_id, $sendToAdmins, $params){
        //$lng = get_languages('common', $lang_id);
        $json=array();
		$json['error'] = '';
		$json['msg'] = '';

        $Query = "select * from users where user_id = '".$_SESSION['UserID'] . "'";
  		$rs = mysqli_query($GLOBALS['conn'], $Query) or die(mysqli_error($GLOBALS['conn']));
        
        if(mysqli_num_rows($rs) > 0){
             $rw = mysqli_fetch_object($rs);
             $userDetails = array(
                "user_id" => $rw->user_id,
                "mem_uid" => $rw->user_id,
                "user_name" => strval($rw->user_name),
                "urole_id" => $rw->urole_id,
                "status_id" => $rw->status_id,
                "lang_id" => $rw->lang_id,
            );

          	$userDetails = json_decode(json_encode($userDetails));
        }


		/* $sendToAdmins = 0;
		if(isset($params['sendToAdmins'])){
			$sendToAdmins = $params['sendToAdmins'];
		} */
	
		if(empty($params['deviceToken']) && $sendToAdmins==0){
			$json['error'] = '1';
            $json['msg'] = 'not successfull';
		} else {
			//Credentials
			$icon = 'https://devnewco.oyas.info/admin/assets/images/favicon.ico';
			$url = "https://fcm.googleapis.com/fcm/send";
			$server_key = "AAAA83ZPvho:APA91bHVnaSCScS3CtQDrJ1TT7F01vFZQ9voFOoH3EjNtpAeh6i-KjjEJfA2uTDljX6lwis-335NXhLQWF3qQh-tnV9gl6SVTpmTGI72kUlx3yJffT9NCbSshInOosE0NTXfH_BG7oE9";
			$headers = array(
				'Authorization: key='.$server_key,
				'Content-Type: application/json;charset=UTF-8'
            );

			//For Custom Message
			$ntm_title = '';
			if(isset($params['title'])){
				$ntm_title = $params['title'];
			}
			$ntm_body = '';
			if(isset($params['message'])){
				$ntm_body = $params['message'];
            }
            
			$click_url = '';
	
			//For Fix DB Message
			//$ntt_id = 0;
			//if(isset($params['ntt_id']) && $params['ntt_id']>0){
			if($ntt_id>0){
				//$ntt_id = $params['ntt_id'];
				if($ntt_id == 1){
					//Custom Message
					$ntm_title = 0;
					if(isset($params['title'])){
						$ntm_title = $params['title'];
					}
					$ntm_body = 0;
					if(isset($params['message'])){
						$ntm_body = $params['message'];
                    }
                    
				} else {
					//Fix DB Message
					if ($lang_id == 1) {
						$addQry = ' , title_en as msg_title , body_en as msg_body ';
					} else {
						$addQry = ' , title_fr as msg_title , body_fr as msg_body ';
					}
					$QueryNT = "select * ".$addQry." from  notificatioin_types where notification_id = '" . $ntt_id . "'";
					$rsNT = mysqli_query($GLOBALS['conn'], $QueryNT);
					if(mysqli_num_rows($rsNT) > 0){
						while ($rowNT = mysqli_fetch_object($rsNT)){
							$ntm_title = $rowNT->msg_title . ' ' . $ntm_title;
							$ntm_title = str_replace('{user_name}', $userDetails->mem_fname .' '. $userDetails->mem_lname, $ntm_title);
							$ntm_title = str_replace('{user_id}', $userDetails->user_id, $ntm_title);
							$ntm_title = str_replace('{client_name}', $userDetails->mem_fname .' '. $userDetails->mem_lname, $ntm_title);
							if(isset($params['prebooking_id'])){
								$ntm_title = str_replace('{bookingid}', $params['prebooking_id'], $ntm_title);
							}
							if(isset($params['client_name'])){
								$ntm_title = str_replace('{client_name}', $params['client_name'], $ntm_title);
							}
							if(isset($params['agent_name'])){
								$ntm_title = str_replace('{agent_name}', $params['agent_name'], $ntm_title);
							}
							if(isset($params['mission_size'])){
								$ntm_title = str_replace('{nbr_hours}', $params['mission_size'], $ntm_title);
							}
							if(isset($params['city'])){
								$ntm_title = str_replace('{city}', $params['city'], $ntm_title);
							}
						
							$ntm_body = $rowNT->msg_body .' '. $ntm_body;
							$ntm_body = str_replace('{user_name}', $userDetails->mem_fname .' '. $userDetails->mem_lname, $ntm_body);
							$ntm_body = str_replace('{user_id}', $userDetails->user_id, $ntm_body);
							$ntm_body = str_replace('{nbr_agents}', $params['agent_number'], $ntm_body);
							$ntm_body = str_replace('{agent_type}', $params['security_name'], $ntm_body);
							$ntm_body = str_replace('{start_date}', $params['start_date_modified'], $ntm_body);
							$ntm_body = str_replace('{end_date}', $params['end_date_modified'], $ntm_body);
							$ntm_body = str_replace('{start_time}', $params['hours_number_from'], $ntm_body);
							$ntm_body = str_replace('{end_time}', $params['hours_number_to'], $ntm_body);
							$ntm_body = str_replace('{location_address}', $params['mission_address'], $ntm_body);
							$ntm_body = str_replace('{estimated_price}', $params['estimated_price'], $ntm_body);
							if(isset($params['prebooking_id'])){
								$ntm_body = str_replace('{bookingid}', $params['prebooking_id'], $ntm_body);
							}
							if(isset($params['client_name'])){
								$ntm_body = str_replace('{client_name}', $params['client_name'], $ntm_body);
							}
							if(isset($params['agent_name'])){
								$ntm_body = str_replace('{agent_name}', $params['agent_name'], $ntm_body);
							}
							if(isset($params['mission_size'])){
								$ntm_body = str_replace('{nbr_hours}', $params['mission_size'], $ntm_body);
							}
							if(isset($params['city'])){
								$ntm_body = str_replace('{city}', $params['city'], $ntm_body);
							}
	
							$click_url = $rowNT->link;
	
						}
					}
				}
			}
	
			$gcmRegIds = array();
			$receiver_id = array();
			$deviceToken = 0;
			if(isset($params['deviceToken'])){
				$deviceToken = $params['deviceToken'];
			}
			$ntd_id = 0;
			$QueryDT = "select * from notification_devices where user_device_id = '" . $deviceToken . "'";
            $rsDT = mysqli_query($GLOBALS['conn'], $QueryDT);

			if(mysqli_num_rows($rsDT) > 0){
				while ($rowDT = mysqli_fetch_object($rsDT)){
					$ntd_id = $rowDT->ntd_id;
					//In case SINGLE token is define in params, then add this token along with others also, so that notification send to CLIENT/AGENT and Admins
					array_push($gcmRegIds, $rowDT->user_device_id);
					array_push($receiver_id, $rowDT->user_id);
				}
			}
			//$ntd_id = $this->getMaximumWhere("ntd_id", "notification_messages", '');
			$ntd_id = 0;
			$QueryNM = "select (MAX(`ntd_id`)+1) as ntd_id from notification_devices";
			$rsNM = mysqli_query($GLOBALS['conn'], $QueryNM);
			if(mysqli_num_rows($rsNM) > 0){
				while ($rowNM = mysqli_fetch_object($rsNM)){
                    $ntd_id = $rowNM->ntd_id;
				}
            }
            if($ntd_id==null){
                $ntd_id=1;
            }

			if($sendToAdmins == 1){
				//Send to all admins
                $QueryDT = "select * from notification_devices where is_admin = 1 and error IS NULL";
				$rsDT = mysqli_query($GLOBALS['conn'], $QueryDT);
				if(mysqli_num_rows($rsDT) > 0){
					while ($rowDT = mysqli_fetch_object($rsDT)){
						array_push($gcmRegIds, $rowDT->user_device_id);
						array_push($receiver_id, $rowDT->user_id);
					}
                }
                
				$data = array(
					'registration_ids' => $gcmRegIds,
					'priority' => 'high',
					'notification' => array( 
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
					'data' => array (
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
                );

            } else {
				//Send to specific admin
				$data = array(
					'to' => $deviceToken,
					'priority' => 'high',
					'notification' => array( 
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
					'data' => array (
						'title' => $ntm_title,
						'body' => $ntm_body,
						'subtitle' => $ntm_title,
						'icon' => $icon,
						'priority' => 'high',
						'Urgency' => 'high',
						'requireInteraction' => true,
						'sound' => 'default', 
						'badge' => '1',
						'vibrate' => '1',
						'click_action' => $click_url,
						'ntd_id' => $ntd_id,
						'delivery_receipt_requested' => true,
						'time_to_live' => '5000',
					),
				);
			}
	
			$content = $data;
			$result = "";
			/* $ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $content ) );
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($ch);
            
			curl_close($ch);
	
            $arr = json_decode($result, true);
            
			$multicast_id = 0;
			if(isset($arr['multicast_id'])){
				$multicast_id = $arr['multicast_id'];
			}
			$error = '';
			if(isset($arr['results'][0]['error'])){
				$error = $arr['results'][0]['error'];
				$Qry = "UPDATE notification_devices set user_device_registered = '0', error = '".$error."' where ntd_id = '".$ntd_id."' ";
				mysqli_query($GLOBALS['conn'], $Qry);
			} */
			$error = '';
			$multicast_id = 0;
			$Qry = "INSERT INTO notification_messages (ntd_id, ntd_id, ntt_id, ntm_title, ntm_body, ntm_dateadded, multicast_id, error) VALUES ('".$ntd_id."', '".$ntd_id."', '".$ntt_id."', '".$ntm_title."', '".$ntm_body."', '".date("Y-m-d H:i:s")."', '".$multicast_id."', '".$error."')";
			mysqli_query($GLOBALS['conn'], $Qry);

			$receiver_ids = array_unique($receiver_id, SORT_NUMERIC);
			$noti_sender_id = 0;
			$noti_send = 1;
			$ntype_id = 3;
			$nevnt_id = 3;
			$noti_title = $params['user_fullname'].' Logged In';
			$noti_content = $params['user_fullname'].' Logged In at '.date("d/m/Y H:i:s");
			$noti_link = '';
			$noti_id = $this->getMaximum("notifications", "noti_id");
			//for($i=0; $i<count($receiver_ids); $i++){
			foreach($receiver_ids as $rec_id) {
			 	$strQry = "INSERT INTO notifications (noti_id, noti_sender_id, noti_receiver_id, ntype_id, nevnt_id, noti_title, noti_content, noti_date, noti_link, multicast_id, noti_sending_status) VALUES (" . $noti_id . ", '".$noti_sender_id."', '".$rec_id."', '".$ntype_id."', '".$nevnt_id."', '" . $this->dbStr($noti_title) . "', '" . $this->dbStr($noti_content) . "', '".date("Y-m-d H:i:s")."', '" . $this->dbStr($noti_link) . "', '".$multicast_id."', '".$noti_send."')";
				mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
				$noti_id++;
			}
			$json['error'] = '0';
			$json['msg'] = 'success';
            $json['result'] = $result;
            
           // $jsonResults = json_encode($json);
            return $json;
			
		}
	}

	public function underliying($nb_underlings){
		$str = "";
		if($nb_underlings > 1){
			$str = "each underlings close ";
		}
		else{
			$str = "the underlying closes ";
		}
		return $str;
	}

	public function level_print($level){
		$str = "";
		if($level == 1){
			$str = "its inital level, ";
		}
		else{
			$str = strval($level * 100) . "% of its inital level: ";
		}
		return $str;
	}

	public function autocall_print($nb_underlings, $stepdowneffect, $autocall_barrier, $memoryeffect, $autocall_level, $coupon_level){
		$str = "";
		$str .= $this->underliying($nb_underlings);
		$str .= "at or above ";
		if($stepdowneffect){
			$str .= "the autocall level (please refer to the table below): ";
		}
		else{
			$str .= $this->level_print($autocall_barrier);
		}
			
		if($memoryeffect){
			$str .= "     Early redemption at " . strval($autocall_level * 100) . "% + N* x " . strval($coupon_level * 100) . "% coupon.";
		}
		else{
			$str .= "     Early redemption at " . strval($autocall_level * 100) . "% +" . strval($coupon_level * 100) . "% coupon.";
		}
		return $str;
	}

	public function coupon_print($nb_underlings, $coupon_barrier, $stepdowneffect, $memoryeffect, $autocall_level, $coupon_level){
		$str = "";
		$str = $this->underliying($nb_underlings);
		$str .= "at or above ";
		if($stepdowneffect){
			$str .= "the autocall level (please refer to the table below): ";
		}
		else{
			$str .= $this->level_print($coupon_barrier);
		}
		if($memoryeffect){
			$str .= "     Early redemption at " . strval($autocall_level * 100) . "% + N* x " . strval($coupon_level * 100) . "% coupon.";
		}
		else{
			$str .= "     Early redemption at " . strval($autocall_level * 100) . "% +" . strval($coupon_level * 100) . "% coupon.";
		}
		return $str;
	}

	public function protection_print($nb_underlings, $protection_barrier, $protection_level){
		$str = "";
		$str = $this->underliying($nb_underlings);
		$str .= "at or above ";
		$str .= $this->level_print($protection_barrier);
		$str .= "     Redemption at " . strval($protection_level * 100) . "%.";
		return $str;
	}

	public function non_call_print($non_call_periods, $nb_underlings, $coupon_barrier, $stepdowneffect, $memoryeffect, $autocall_level, $coupon_level){
		$str = "";
		if($non_call_periods != 0){
			if($non_call_periods == 1){
				$str = "On the first observation date:";
			}
			else{
				$str = "From observation date 1 to" . $non_call_periods . " :";
			}
			$str .= " If <br>";
			$str .= $this->coupon_print($nb_underlings, $coupon_barrier, $stepdowneffect, $memoryeffect, $autocall_level, $coupon_level);
			$str .= " Else, product continues.<br>";
			//return True;
			return $str;
		}
		else{
			return False;
		}
	}

	public function redemption_print($nb_underlings){
		$str = " Else, redemption at :";
		if($nb_underlings > 1){
			$str .= "     Final/Initial-1";
		}
		else{
			$str .= "     Finallevel/Initiallevel-1";
		}
		return $str;
	}

	// START - FUNCTIONS FOR PRODUCT MECHANISM
	public function desc_und($nb_und){
		$str = "";
		if($nb_und>1){
			$str = "worst underlying";
		} else{
			$str = "underlying";
		}
        return $str;
	}

	public function desc_und_final($nb_und, $barrier_type){
		$str = "";
		if($barrier_type=="American"){
			$str = "underlyings";
		} elseif($nb_und>1){
			$str = "worst underlying";
		} else{
			$str = "underlying";
		}
		return $str;
	}

	public function periodicity($period){
		$str = "";
		if($period==1){
			$str = "p.m.";
		} elseif($period==3){
			$str = "p.q.";
		} elseif($period==6){
			$str = "p.s.";
		} elseif($period==12){
			$str = "p.a.";
		}
		return $str;
	}

	public function periodicity_full($period){
		$str = "";
		if($period==1){
			$str = "Month";
		} elseif($period==3){
			$str = "Quarter";
		} elseif($period==6){
			$str = "Semester";
		} elseif($period==12){
			$str = "Year";
		}
		return $str;
	}

	public function memory_effect($memory){
		$str = "";
		if($memory){
			$str = " + any previously missed coupon\n(MEMORY EFFECT)";
		}
		return $str;
	}

	public function article_auto($level, $step_down_autocall){
		$str = "";
		if($step_down_autocall){
			$str = "its autocall barrier";
		} elseif($level==1){
			$str = "its initial level";
		} else{
			$str = $level."% of its initial level";
		}
		return $str;
	}

	public function article_barrier($level){
		$str = "";
		if($level==1){
			$str = "its initial level";
		} else{
			$str = $level."% of its initial level";
		}
		return $str;
	}

	public function article_coupon($level, $step_down_coupon){
		$str = "";
		if($step_down_coupon){
			$str = "its coupon barrier";
		} elseif($level==1){
			$str = "its initial level";
		} else{
			$str = $level."% of its initial level";
		}
		return $str;
	}

	public function redemption_text($nb_und){
		$str = "";
		if($nb_und>1){
			$str = "Worst_Final/Worst_Initial";
		} else{
			$str = "Final/Initial";
		}
		return $str;
	}

	public function none_of($barrier_type){
		$str = "";
		if($barrier_type=="American"){
			$str = "none of ";
		}
		return $str;
	}

	public function ever_traded($barrier_type){
		$str = "";
		if($barrier_type=="American"){
			$str = " has ever traded ";
		} else{
			$str = " closes ";
		}
		return $str;
	}

	// END - FUNCTIONS FOR PRODUCT MECHANISM

	/* public function ($str){
		$str = "";
		return $str;
	} */

	public function get_cron_list($params){
        $retValue = array();
        $Query = "SELECT c.crn_id, c.crn_title, c.crn_details, c.crn_url, cl.clog_status, (SELECT t.clog_time FROM cron_logs AS t WHERE t.crn_id=c.crn_id ORDER BY t.clog_id DESC LIMIT 0,1) AS last_clog_time, MAX(cl.clog_time) AS max_clog_time, SEC_TO_TIME(AVG(TIME_TO_SEC(cl.clog_time))) AS avg_clog_time,
		(SELECT COUNT(rd.crn_id) FROM cron_logs AS rd WHERE rd.crn_id=cl.crn_id AND rd.clog_started_on > NOW() - INTERVAL 1 DAY) AS nbr_time_lst_day,
		(SELECT COUNT(rw.crn_id) FROM cron_logs AS rw WHERE rw.crn_id=cl.crn_id AND rw.clog_started_on > NOW() - INTERVAL 7 DAY) AS nbr_time_lst_week,
		(SELECT COUNT(wd.crn_id) FROM cron_logs AS wd WHERE wd.crn_id=cl.crn_id AND wd.clog_status='Error - Not Completed' AND wd.clog_started_on > NOW() - INTERVAL 1 DAY) AS nbr_error_lst_day,
		(SELECT COUNT(ww.crn_id) FROM cron_logs AS ww WHERE ww.crn_id=cl.crn_id AND ww.clog_status='Error - Not Completed' AND ww.clog_started_on > NOW() - INTERVAL 7 DAY) AS nbr_error_lst_week
		FROM crons AS c 
		LEFT OUTER JOIN cron_logs AS cl ON cl.crn_id=c.crn_id
		GROUP BY crn_id";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if(mysqli_num_rows($rs) > 0){
            while($rw = mysqli_fetch_object($rs)){
				$crn_url = $GLOBALS['siteURL'].$rw->crn_url;
				$retValue[] = array(
					"crn_id" => strval($rw->crn_id),
					"crn_title" => strval($rw->crn_title),
					"crn_details" => strval($rw->crn_details),
					"last_run_time" => strval($rw->last_clog_time),
					"average_run_time" => strval($rw->avg_clog_time),
					"max_run_time" => strval($rw->max_clog_time),
					"nbr_time_lst_day" => strval($rw->nbr_time_lst_day),
					"nbr_time_lst_week" => strval($rw->nbr_time_lst_week),
					"nbr_error_lst_day" => strval($rw->nbr_error_lst_day),
					"nbr_error_lst_week" => strval($rw->nbr_error_lst_week),
					"run_status" => strval($rw->clog_status),
					"crn_url" => strval($crn_url)
				);
			}
        }
        return $retValue;
    }

	public function get_cron_logs($params){
        $retValue = array();
        $strWhere = "";
        if(isset($params['crn_id'])){
			$strWhere = "WHERE cl.crn_id='".$params['crn_id']."'";
        }
        $Query = "SELECT cl.*, c.crn_title FROM cron_logs AS cl LEFT OUTER JOIN crons AS c ON c.crn_id=cl.crn_id ".$strWhere." ORDER BY cl.clog_id DESC";
		$totalRec = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
        if(isset($params['pg'])){
            $pg = $params['pg'];
            $limit = 50;
            if ($pg < 2) {
                $start = 0;
                $pg = 1;
            } else {
                $start = ($pg - 1) * $limit;
            }
            $pages = (($totalRec % $limit) == 0) ? $totalRec / $limit : floor($totalRec / $limit) + 1;
            $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
        }
        else{
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $pages = 1;
            $pg = 1;
        }
        if(mysqli_num_rows($rs)>0){
            $retValue = array("status" => "1", "message" => "Record Found!", "totalRec" => $totalRec, "pages" => $pages, "page" => $pg);
            while($rw = mysqli_fetch_object($rs)){
				$retValue['data'][] = array(
					"clog_id" => strval($rw->clog_id),
					"crn_id" => strval($rw->crn_id),
					"crn_title" => strval($rw->crn_title),
					"clog_started_on" => strval($rw->clog_started_on),
					"clog_end_on" => strval($rw->clog_end_on),
					"clog_time" => strval($rw->clog_time),
					"clog_status" => strval($rw->clog_status)
					//"clog_response" => strval($rw->clog_response)
				);
			}
        }
		else{
            $retValue = array("status" => "0", "message" => "No record found!", "data" => array());
        }
        return $retValue;
    }

	public function dbStr($str){
		$str = str_replace("’", "'", $str);
		$string = str_replace("'", "''", $str); // Converts ' to ' in database, but ' to '' in the static page
		//return stripslashes($string); // Removes any forward slashes from string
		//$string = $str;
		return $string;
	}

	public function jsonStr($str){
		$str = str_replace('\"', '##', $str);
		return $str;
	}

	public function htmlString($str){
		$str = str_replace('!important', '#important', $str);
		$string = str_replace("'true'", 'true', $str);
		return $string;
	}

	public function dateToFrench($date, $format) {
		$english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
		$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
		return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
	}

	public function jsonObj($str){
		$str = str_replace('##', '\"', $str);
		$str = trim(preg_replace('/\s+/', ' ', $str));
		return $str;
	}

	public function ajaxUrlStr($str){
		//$string = str_replace("'", "\'", $str);
		$string  = htmlentities(addslashes($str));
		return $string;
	}

	public function htmlStr($str, $noBR = 0){	
		$string = str_replace('"', '&quot;', $str);
		if($noBR>0){
			$string = str_replace('<br>', ' ', $string);
		}
		return $string;
	}

	public function add_user_logs($params) {
		//print_r($params);
        if (isset($params['user_id']) && $params['user_id'] > 0) {
            $log_id = $this->getMaximum("user_logs", "log_id");
            $strQry = "INSERT INTO user_logs (log_id, user_id, section_id, sub_section, item_id, log_action) VALUES (" . $log_id . ", '" . $params['user_id'] . "', '" . $params['section_id'] . "', '" . $this->dbStr($params['sub_section']) . "', '" . $params['item_id'] . "', '" . $this->dbStr($params['log_action']) . "')";
            mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
        }
        $retValue = array("status" => "1", "message" => "Record Added / Updated!");
        return $retValue;
    }

	public function add_update_rfq_logs($params) {
        if (isset($params['rlog_id']) && $params['rlog_id'] > 0) {
			$rlog_id = $params['rlog_id'];
			$rlog_response = json_encode($params['rlog_response']);
            mysqli_query($GLOBALS['conn'], "UPDATE rfq_logs SET rlog_end_on='".date("Y-m-d H:i:s")."', rlog_response='".$this->dbStr($rlog_response)."' WHERE rlog_id=".$rlog_id);
        }
		else{
			$rlog_id = $this->getMaximum("rfq_logs", "rlog_id");
        	mysqli_query($GLOBALS['conn'], "INSERT INTO rfq_logs(rlog_id, rlog_name, rlog_started_on, rlog_rec_id, rlog_type) VALUES(".$rlog_id.", '".$params['rlog_name']."', '".date("Y-m-d H:i:s")."', '".$params['rlog_rec_id']."', '".$params['rlog_type']."')");
		}
        return $rlog_id;
    }

	public function srarch_qry_all_words($str, $fld, $both=0){
		$skipWords = array("je", "me", "m'", "moi", "tu", "te", "t", "toi", "nous", "vous", "il", "elle", "ils", "elles", "se", "en", "y", "le", "la", "l'", "les", "lui", "soi", "leur", "eux", "lui", "leur", "celui", "celui-ci", "celui-là", "celle", "celle-ci", "celle-là", "ceux", "ceux-ci", "ceux-là", "celles", "celles-ci", "celles-là", "ce", "ceci", "cela", "ça", "qui", "que", "quoi", "dont", "où", "qu'est-ce", "lequel", "auquel", "duquel", "laquelle", "à laquelle", "de laquelle", "lesquels", "auxquels", "desquels", "lesquelles", "auxquelles", "desquelles", "on", "tout", "un", "une", "l'un", "l'une", "les", "uns", "unes", "un", "autre", "autre", "d'autres", "l'autre", "autres", "aucun", "aucune", "aucuns", "aucunes", "certains", "certaine", "certains", "certaines", "tel", "telle", "tels", "telles", "tout", "toute", "tous", "toutes", "même", "la", "mêmes", "nul", "nulle", "nuls", "nulles", "quelqu'un", "quelqu'une", "quelques", "uns", "quelques", "unes");
		$strarray = (explode(" ",$str));
		$query = "";
		if($both == 1){
			$cs1 = $str;
			$cs2 = join(' ', array_reverse($strarray)); 
			$cs3 = str_replace(" ", "%", $this->dbStr($str));
			$cs4 = str_replace(" ", "%", $this->dbStr($cs2));
			$query .= "(".$fld." LIKE '".$this->dbStr($cs1)."')";
			$query .= " OR (".$fld." LIKE '".$this->dbStr($cs2)."')";
			$query .= " OR (".$fld." LIKE '%".$this->dbStr($cs3)."%')";
			$query .= " OR (".$fld." LIKE '%".$this->dbStr($cs4)."%')";
			/* if (strpos($str, '-') !== false) {
				$query .= "(REPLACE(".$fld.", '-', ' ') LIKE '".$this->dbStr(str_replace("-", " ", $cs1))."')";
				$query .= " OR (REPLACE(".$fld.", '-', ' ') LIKE '".$this->dbStr(str_replace("-", " ", $cs2))."')";
				$query .= " OR (REPLACE(".$fld.", '-', ' ') LIKE '%".$this->dbStr(str_replace("-", " ", $cs3))."%')";
				$query .= " OR (REPLACE(".$fld.", '-', ' ') LIKE '%".$this->dbStr(str_replace("-", " ", $cs4))."%')";
			} */
		}
		else{
			$query = $query . " (".$fld." LIKE '" . $this->dbStr($str) . "') ";
			$query = $query . " OR (".$fld." LIKE '%" . $this->dbStr($str) . "%') ";
			/* $query = $query . " OR (".$fld." LIKE '" . $this->dbStr($str) . "%') ";
			$query = $query . " OR (".$fld." LIKE '%" . $this->dbStr($str) . "') "; */
			foreach($strarray as $key=>$value){
				if (!in_array(strtolower($value), $skipWords)) {
					//if($key > 0){
						$query = $query . "OR";
					//}
					if($both==1){
						$query = $query . " (".$fld." LIKE '%" . $this->dbStr(rtrim($value, ",")) . "%') ";
					}
					else{
						$query = $query . " (".$fld." LIKE '" . $this->dbStr(rtrim($value, ",")) . "%') ";
					}
				}
			}
		}
		return $query;
	}

	public function strip_words($string){
		$wordlist = array("je", "de", "des", " me", "m'", "moi", "tu", "te", "t", "toi", "nous", "vous", "il", "elle", "ils", "elles", "se", "en", "y", "le", "Le ", "la", "La", "l'", "les", "lui", "soi", "leur", "eux", "lui", "leur", "celui", "celui-ci", "celui-là", "celle", "celle-ci", "celle-là", "ceux", "ceux-ci", "ceux-là", "celles", "celles-ci", "celles-là", "ce", "ceci", "cela", "ça", "qui", "que", "quoi", "dont", "où", "qu'est-ce", "lequel", "auquel", "duquel", "laquelle", "à laquelle", "de laquelle", "lesquels", "auxquels", "desquels", "lesquelles", "auxquelles", "desquelles", "on", "tout", "un", "une", "l'un", "l'une", "les", "uns", "unes", "un", "autre", "autre", "d'autres", "l'autre", "autres", "aucun", "aucune", "aucuns", "aucunes", "certains", "certaine", "certains", "certaines", "tel", "telle", "tels", "telles", "tout", "toute", "tous", "toutes", "même", "la", "mêmes", "nul", "nulle", "nuls", "nulles", "quelqu'un", "quelqu'une", "quelques", "uns", "quelques", "unes");
		foreach ($wordlist as &$word) {
			$word = '/\b' . preg_quote($word, '/') . '\b/';
		}
		$string = preg_replace($wordlist, '', $string);
		return $string;
	}

	public function addIfNotExist($Table, $IDField, $ValField, $value){
		$retRes = "";
		$strQry="SELECT ".$ValField." FROM ".$Table." WHERE ".$ValField."='".$this->dbStr($value)."'";
		$nResult =mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>0){		
			$retRes = "Exists";
		}
		else{
			$mID = $this->getMaximum($Table, $IDField);
			mysqli_query($GLOBALS['conn'], "INSERT INTO ".$Table." (".$IDField.", ".$ValField.") VALUES('".$mID."', '" . $this->dbStr($value)."')") or die(mysqli_error($GLOBALS['conn']));
		}
		return $retRes;	
	}

	public function limit_text( $text, $limit ){
		// figure out the total length of the string
		if( strlen($text)>$limit ){
			# cut the text
			$text = substr( $text,0,$limit );
			# lose any incomplete word at the end
			$text = substr( $text,0,-(strlen(strrchr($text,' '))) );
			$text.=" . . .";
		}
		// return the processed string
		return $text;
	}

	public function udtBackThemeName($str){
		$strVal = "";
		$chkStr = array("(Les)", "(les)", "(Des)", "(des)", "(La)", "(la)", "(Le)", "(le)", "(Du)", "(du)", "(L')", "(l')");
		foreach ($chkStr as $t2) {
			if (strpos($str, $t2) !== false) {
				//echo 'true';
				$str2 = str_replace($t2, "", $str);
				$tm = ltrim(rtrim($t2, ")"), "(");
				$strVal = $tm.$str2;
			}
		}
		return $strVal;
	}

	public function urlStr($str){
		$string = str_replace(" ", "-", trim($str));
		$string = str_replace("’", "-", $string);
		$string = str_replace("'", "-", $string);
		$string = str_replace(".", "", $string);
		$string = str_replace("?", "", $string);
		$string = str_replace(":", "", $string);
		$unwanted_array = array( 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
			'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
			'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
		$string = strtr( $string, $unwanted_array );
		return $string;
	}

	public function lov_cmnt_etat($val){
		$strRet = '';
		$strRet .= '<option value="0" ' . (($val == 0) ? 'selected' : '') . '>Stand By</option>';
		$strRet .= '<option value="2"' . (($val == 2) ? 'selected' : '') . '>Accepté</option>';
		$strRet .= '<option value="3" ' . (($val == 3) ? 'selected' : '') . '>Refusé</option>';
		return $strRet;
	}

	public function lov_dossier_status($val){
		$strRet = '';
		$strRet .= '<option value="0" ' . (($val === '0') ? 'selected' : '') . '>draft</option>';
		$strRet .= '<option value="1" ' . (($val === '1') ? 'selected' : '') . '>publié</option>';
		$strRet .= '<option value="2" ' . (($val === '2') ? 'selected' : '') . '>non publié</option>';
		return $strRet;
	}
	
	public function getMaximum($Table, $Field) {
		$maxID = 0;
		$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " ";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_object($nResult)) {
				if (@$row->CID)
					$maxID = $row->CID;
				else
					$maxID = 1;
			}
		}
		return $maxID;
	}

	public function chkExist($Field, $Table, $WHERE){
		$retRes=0;
		$strQry="SELECT $Field FROM $Table $WHERE";
		$nResult=mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){
			$row=mysqli_fetch_row($nResult);
			$retRes = $row[0];
			//$retRes=1;
		}	
		return $retRes;		
	}

	public function getMaximumVal($Table, $Field) {
		$maxID = 0;
		$strQry = "SELECT MAX(" . $Field . ") CID FROM " . $Table . " ";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_object($nResult)) {
				//if (@$row->CID){
					$maxID = $row->CID;
				//}
			}
		}
		return $maxID;
	}

	public function getMaxRecPipedriveOrg() {
		$maxID = 0;
		$strQry = "SELECT COUNT(DISTINCT(org_pipedrive_id)) AS CID FROM organizations_new WHERE org_pipedrive_id > 0";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_object($nResult)) {
				$maxID = $row->CID;
			}
		}
		return $maxID;
	}

	public function getMaxOrder($Table, $Field, $WHERE) {
		$maxID = 0;
		$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " " . $WHERE;
		$nResult = mysqli_query($GLOBALS['conn'], $strQry);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_object($nResult)) {
				if (@$row->CID)
					$maxID = $row->CID;
				else
					$maxID = 1;
			}
		}
		return $maxID;
	}

	public function getCount($qry) {
		$countRec = 0;
		$nResult = mysqli_query($GLOBALS['conn'], $qry);
		if (mysqli_num_rows($nResult) >= 1) {
			$row = mysqli_fetch_object($nResult);
			$countRec = $row->totalRec;
		}
		return $countRec;
	}

	public function checkExist($Field, $Table, $IDField, $ID){
		$retRes = 0;
		$strQry="SELECT $Field AS id FROM $Table WHERE $IDField='$ID' LIMIT 1";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){		
			$rw = mysqli_fetch_object($nResult);
			$retRes = $rw->id;
		}
		return $retRes;	
	}
	
	public function returnName($Field, $Table, $IDField, $ID){
		$retRes = "";
		$strQry="SELECT $Field FROM $Table WHERE $IDField='$ID' LIMIT 1";
		$nResult =mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){		
			$row=mysqli_fetch_row($nResult);
			$retRes=$row[0];
		}
		//print($Field. '-'.$Table.'-'.$IDField.'-'.$ID.'-'.$retRes);
		return $retRes;	
	}

	public function returnRecord($Field, $Table, $WHR){
		$retRes = "";
		$strQry="SELECT $Field FROM $Table WHERE ".$WHR." LIMIT 1";
		//print($strQry);
		$nResult =mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){		
			$row=mysqli_fetch_row($nResult);
			$retRes=$row[0];
		}	
		return $retRes;	
	}

	public function get_languages($page_ids, $lang_id){
		if($lang_id==1){
			$lang_code = 'en';
		} else{
			$lang_code = 'fr';
		}
		$data = array();
		$Query = "SELECT l.perameter_name, lang_".$lang_code." AS cnt FROM languages as l WHERE l.page_id IN (".$page_ids.") AND status = 1";
		$rs = mysqli_query($GLOBALS['conn'], $Query);
		while ($row = mysqli_fetch_object($rs)){
			$data = array_merge($data, array($row->perameter_name => html_entity_decode($row->cnt)));
		}
		return $data;
	}

	public function pageList($curpage, $pages, $qryString){
		$page_list  = "";
		/* Print the first and previous page links if necessary */
		if (($curpage != 1) && ($curpage)) {
			$page_list .= ' <li><a href="javascript: loadMore(1);" title="First Page" class="numbr selected"><<</a></li>';
		}

		if (($curpage - 1) > 0) {
			$prev = $curpage - 1;
			$page_list .= '<li><a href="javascript: loadMore('.$prev.');" title="Previous Page"><i class="fa fa-caret-left"></i> Previous</a></li>';
		}

		/* Print the numeric page list; make the current page unlinked and bold */
		/* $showPages = $curpage + 9;
		$startShow = 1;
		if ($curpage > 2) {
			$startShow = $curpage - 2;
			$page_list .= "<li><a href=\"#\">...</a></li>";
		}
		//for ($i=1; $i<=$pages; $i++){
		for ($i = $startShow; $i <= $showPages; $i++) {
			if ($i <= $pages) {
				if ($i == $curpage) {
					//$page_list .= "<b class=\"numbr selected\">".$i."</b>";
					$page_list .= "<li><a href='' class='numbr selected'><b>" . $i . "</b></a></li>";
				} else {
					$page_list .= "<li><a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . $i . $qryString . "\" title=\"Page " . $i . "\">" . $i . "</a></li>";
				}
				$page_list .= " ";
			}
		} */

		/* Print the Next and Last page links if necessary */
		/* if ($showPages < $pages) {
			$page_list .= "<li><a href=\"#\">...</a></li>";
		} */

		if (($curpage + 1) <= $pages) {
			$nxt = $curpage + 1;
			$page_list .= '<li class="arrowright"><a href="javascript: loadMore('.$nxt.');" title=" Next Page">Next <i class="fa fa-caret-right"></i></a></li>';
		}

		/* if (($curpage != $pages) && ($pages != 0)) {
			$page_list .= '<li><a href="javascript: loadMore('.$pages.');" title="Last Page">>></a></li>';
		} */
		//$page_list .= "</td>\n";

		return $page_list;
	}

	public function FillSelected($Table, $IDField, $TextField, $ID){
		$strQuery="SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
		$nResult=mysqli_query($GLOBALS['conn'], $strQuery);
		if (mysqli_num_rows($nResult)>=1){
			while ($row=mysqli_fetch_row($nResult)){
				if($row[0] == $ID){
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				}
				else{
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}

	public function FillSelectedCh($Table, $IDField, $TextField, $ID){
		$strQuery = "SELECT $IDField, $TextField FROM $Table";
		$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_row($nResult)) {
				if ($ID == $row[0]) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}

	public function FillSelectedSingleQry($strQuery, $selVal){
		$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
		if (mysqli_num_rows($nResult) >= 1) {
			while ($row = mysqli_fetch_row($nResult)) {
				if ($selVal == $row[0]) {
					print("<option value=\"$row[0]\" selected>$row[0]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[0]</option>");
				}
			}
		}
	}

	public function FillSelectedLang($ID){   
		$strQuery="SELECT lang_id, lang_name, lang_code FROM site_languages ORDER BY lang_id";
		$nResult=mysqli_query($GLOBALS['conn'], $strQuery);
		if (mysqli_num_rows($nResult)>=1){
			while ($row=mysqli_fetch_row($nResult)){
				if($row[0] == $ID){
					print('<option value="'.$row[0].'" data-image="assets/images/'.$row[2].'.png" data-imagecss="flag '.$row[2].'" data-title="'.$row[1].'" selected>'.$row[1].'</option>');
				}
				else{
					print('<option value="'.$row[0].'" data-image="assets/images/'.$row[2].'.png" data-imagecss="flag '.$row[2].'" data-title="'.$row[1].'">'.$row[1].'</option>');
				}
			}
		}
	}

	public function FillSelectedFlag($ID){   
		$strQuery="SELECT lang_id, lang_name, lang_code FROM site_languages ORDER BY lang_id";
		$nResult=mysqli_query($GLOBALS['conn'], $strQuery);
		if (mysqli_num_rows($nResult)>=1){
			while ($row=mysqli_fetch_row($nResult)){
				if($row[0] == $ID){
					print('<label>
						<input type="radio" name="lang_id" value="'.$row[0].'" checked>
						<span><img src="assets/images/'.$row[2].'.png" alt="" width="20"> &nbsp;'.$row[1].'</span>
					</label>');
				}
				else{
					print('<label>
						<input type="radio" name="lang_id" value="'.$row[0].'">
						<span><img src="assets/images/'.$row[2].'.png" alt="" width="20"> &nbsp;'.$row[1].'</span>
					</label>');
				}
			}
		}
	}

	public function randomKey($length) {
		$pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
		for($i=0; $i < $length; $i++) {
			@$key .= $pool[mt_rand(0, count($pool) - 1)];
		}
		return $key;
	}

	public function httpPost($url, $data){
		/* $data = array(
			"userName" => "Mobile1",
			"password" => "Pass@12345",
			"ip" => "192.168.2.131",
			"imei" => "861375030615086"
		); */
		$data_string = json_encode($data);   
	
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
		$result = curl_exec($ch);
		return $result;
	}

	public function ppd_update($url, $data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$output = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($output, true);
		return $result;
	}

	public function ppd_add($url, $data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($output, true);
		return $result;
	}

	public function url_exists($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//print("<br>code: ".$code." - ".$url);
		$status = 0;
		if ($code == 404) {
			$status = 0;
		} else {
			$status = 1;
		}
		curl_close($ch);
		return $status;
	}

	public function curl_Requests($cURL, $apiParams){
		$ch = curl_init($cURL);
		//curl_setopt($ch, CURLOPT_URL, $cURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $apiParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($apiParams))
		);
		$response = curl_exec($ch);
		curl_close ($ch);
		return json_decode($response, true);
	}

	public function curl_Requests_get($cURL, $apiParams){
		$ch = curl_init($cURL);
		//curl_setopt($ch, CURLOPT_URL, $cURL);
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $apiParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		/* curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($apiParams))
		); */
		$response = curl_exec($ch);
		curl_close ($ch);
		return json_decode($response, true);
	}

	public function curl_Requests_JSON($cURL, $apiParams){
		$ch = curl_init($cURL);
		//curl_setopt($ch, CURLOPT_URL, $cURL);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiParams));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($apiParams))
		);
		$response = curl_exec($ch);
		curl_close ($ch);
		return json_decode($response, true);
	}

	public function curl_del($path, $json = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);
		return $result;
	}

	public function removeAccents($str) {
		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
		return str_replace($a, $b, $str);
	}
	

}