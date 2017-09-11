<?php 
function selected($val1, $val2)
{
	if($val1==$val2){
		$result = "selected='selected'";
	}else{
		$result = '';
	}
	return $result;
}

function workingDaysTotal($date, $month)
{
	$db = database::db();
	$date = strtotime(date("Y-$month-$date"));
	$query = $db->query("SELECT * FROM `attendance` WHERE `date` = $date");
    if($query->num_rows > 1){
    	return true;
    }else{
    	return false;
    }
}

function monthlyReportQueryOne($pin, $month)
{
	$db = database::db();
	$eachPin = $pin;
    $first_day = strtotime(date("Y-$month-01"));
    $last_day = strtotime(date("Y-$month-t"));

    $query = "SELECT * FROM `attendance` WHERE `pin` = $eachPin AND `date` BETWEEN $first_day AND $last_day";
    $result = mysqli_query($db, $query);
    while ($rows = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
      $localData[] = $rows;
    }

    if(!empty($localData)){
      foreach($localData as $eachLocal){
        $localDate[] = date('d', $eachLocal['date']);
      }
    }else{
      $localDate = array();
    }
    return $localDate;
}

function hour_count($timeOne, $timeTwo)
{
	$d1= new DateTime($timeOne); 
	$d2= new DateTime($timeTwo);
	$interval= $d1->diff($d2);
	$value = $interval->h.':'.$interval->i.' Minutes';
	return $value;
}

function pd($data)
{	
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die();
}


 ?>