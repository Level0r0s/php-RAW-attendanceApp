<?php 

// === FEATCH ATTENDANCE TABLES DATA =====//
// === IF POST DATA FOUND === //

if(!empty($_POST['date'])){
	$searchDate = date('Y-m-d', strtotime($_POST['date']));	
}else{
	$searchDate = date('Y-m-d');
}
$fdate     = strtotime($searchDate);
$query     = "SELECT * FROM `attendance` WHERE `date` = $fdate";
$result    = mysqli_query($db, $query);
$localData = array();
while ($rows = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
	$localData[] = $rows;
}
if(empty($localData) && isset($_POST['date'])){
	$postResult = 'No Result Found';
}



// === FEATCH TEACHERS TABLES DATA =====//
$query1  = "SELECT * FROM `teachers`";
$result1 = mysqli_query($db, $query1);
while ($rows1 = mysqli_fetch_array($result1,MYSQLI_ASSOC)) {
	$localData1[] = $rows1;
}

if(!empty($localData)){
	$teacher_pin = array_column($localData, 'pin');	
}else{
	$teacher_pin = array();
}




 ?>