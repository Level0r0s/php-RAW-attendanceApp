<?php 
// ===== FOR ANY HELP FOR THIS LAIBARY =======//
//// https://github.com/cobisja/tad-php

// =========== GET DEVICE STUDENT ATTENDANCE LOG DATE WISE ================ //
use TADPHP\TADFactory;
use TADPHP\TAD;

$comands = TAD::commands_available();
//$tad = (new TADFactory(['ip'=>'192.168.1.201']))->get_instance();



// === FEATCH ATTENDANCE MACHINE DATA =====//					
// === IF POST DATA FOUND === //

$start_date = '';
$end_date = '';
if(!empty($_POST['month'])) {
	$start_date = date('Y-0'.$_POST['month'].'-01');
	$end_date = date('Y-0'.$_POST['month'].'-t');
}

if(!empty($_POST['date'])){										
	$start_date = date('Y-m-d', strtotime($_POST['date']));				
	$end_date = date('Y-m-d', strtotime($_POST['date']));				
}else{															
	$start_date = date('Y-m-d');								
	$end_date = date('Y-m-d');								
}							
$searchDate = '2017-08-21';	
// $response = $tad->get_att_log()->filter_by_date(				
//     ['start' => date($start_date),'end' => date($end_date)]	
// )->to_array();																


//pd($response);

// ====== FOR CHECK USER INFORMATION =====//

//$user_info = $tad->get_user_info(['pin'=>3]);

// $new = new $user_info;
// $var1 = new ReflectionObject($new);
// $nodes = $var1->getProperty('TADPHP\TADResponse')
//$obj = new $user_info;

//echo $obj;

//$string = serialize($user_info);
// echo '<pre>';
// $val2 = json_encode( (array)$user_info, JSON_NUMERIC_CHECK);
// $val3 = json_decode($val2, true);
// $val4 = array_values($val3);
// $valE = $val4[1];
// //var_dump($valE);
// //$val5 = preg_split('/(?<=[0-9])(?=[a-z]+)/i',"$valE");
// $val6 = preg_replace(array('/</', '/>/'), '', $valE);
// var_dump($val6);
// die();



if(!empty($response)) {
	$dups = array();
	$arr_col = array_column($response['Row'], 'PIN');
	foreach(array_count_values($arr_col) as $val => $c){
	    if($c > 1) $dups[] = $val;
	    
	}
	// ============ CHECK MULTIDIMATIONAL ARRAY ============= //
	// IF IT IS SINGLE THERE WHERE NO ACTION
	$rv = array_filter ($response['Row'],'is_array');
	if (count($rv)>0) {
		foreach ($response['Row'] as $list) {

			$pin    = $list['PIN']; 
			$date   = strtotime(current(explode(' ', $list['DateTime'])));
			$query1 = $db->query("SELECT * FROM `attendance` WHERE `pin` = $pin AND `date` = $date");
			$query2 = "SELECT * FROM `attendance` WHERE `pin` = $pin AND `date` = $date";
			$result = mysqli_query($db,$query2);
			$row    = mysqli_fetch_array($result,MYSQLI_ASSOC);
			
			
			if ($query1->num_rows < 1) {
				$pin  = $list['PIN'];
				$date = strtotime(current(explode(' ', $list['DateTime'])));
				$time = strtotime(end(explode(' ', $list['DateTime'])));
				$absent[] = $db->query("INSERT INTO `attendance` (`pin`,`date`,`intime`) VALUES ($pin, $date, $time)");
			} else {
				if ($row['status']==1) {
					$reverseArr = array_reverse($arr_col, true);
					$arrIndex   = array_search($list['PIN'], $reverseArr);
					$inTime     = strtotime("+3 minutes", $row['intime']); 				
					$outStrToTime = strtotime(end(explode(' ', $response['Row'][$arrIndex]['DateTime'])));
	
					if ($outStrToTime >= $inTime) {
						$pin = $list['PIN']; 
						if(in_array($pin, $dups)) {
							$present[] = $db->query("UPDATE `attendance` SET `status`= 2, `outtime` = $outStrToTime WHERE `pin` = $pin AND `date` = $date");	
						}				
					}
				}
			}			
		}
	} else {
		$pin    = $response['Row']['PIN']; 
		$date   = strtotime(current(explode(' ', $response['Row']['DateTime'])));
		$query1 = $db->query("SELECT * FROM `attendance` WHERE `pin` = $pin AND `date` = $date");		
		
		if ($query1->num_rows < 1){
			$pin      = $response['Row']['PIN'];
			$date     = strtotime(current(explode(' ', $response['Row']['DateTime'])));
			$time     = strtotime(end(explode(' ', $response['Row']['DateTime'])));
			$absent[] = $db->query("INSERT INTO `attendance` (`pin`,`date`,`intime`) VALUES ($pin, $date, $time)");
		}
	}

} else {
	
}	

	




 ?>