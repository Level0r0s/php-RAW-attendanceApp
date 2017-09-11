<?php 
date_default_timezone_set('asia/dhaka');
session_start();
require 'db/config.php';

$date = strtotime(date('Y-m-d'));
$arrs= array();
//$date = strtotime('2017-02-22 19:00:00');
$query = "SELECT * FROM `attendance` WHERE `date` = $date";
$result = mysqli_query($db, $query);
//$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
while ($rows = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
	$localData[] = $rows;
}
//print_r($arrs);	


//print_r(date('Y-m-d H:i:s'));	
//die();


$arr = array(
	'getStdClassInfo',
	'getStdGroupInfo',
	'getStdSectionInfo',
	'getStdShiftInfo',
	'getStdEnrollInfo',
	'getStdStudentInfo'
	);

foreach($arr as $ar){
$url="http://bidyapith2.nihalit.com/index.php?admin/$ar";
//  Initiate curl
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$allInfos[$ar] = json_decode($result, true);
curl_close($ch);
}

$classColumn = array_column($allInfos['getStdClassInfo'], 'class_id');
$studentColumn = array_column($allInfos['getStdStudentInfo'], 'student_id');
$groupColumn = array_column($allInfos['getStdGroupInfo'], 'group_id');
$sectionColumn = array_column($allInfos['getStdSectionInfo'], 'section_id');
$shiftColumn = array_column($allInfos['getStdShiftInfo'], 'shift_id');
$localDataColumn = array_column($localData, 'pin');


// print_r($localDataColumn);	
// die();
?>

<!DOCTYPE html>
  <html>
    <head>
    <title>School Attendance</title>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
      <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
      <!--Import materialize.css-->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
       <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <style type="text/css">
      	select {
		     display: block; 
		}
      </style>
    </head>

    <body>
    
    
    
    <div class="container">
    	<div class="offset-s4 col s4 left">
	    	<p>Select To Sort Class Wise:</p>
	    	<div class="searchCol"></div>	
	    	<br><br>
	    </div>
	    <div class="col s4 right">
	    	<p></p>
	    	<button class="btn waves-effect waves-light" onclick="location.reload()">Reload
			    <i class="material-icons right">loop</i>
		  	</button>
	    </div>
    	<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th class="class">Class</th>
                <th>Roll</th>
                <th>Section</th>
                <th>Shift</th>
                <th>Status</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Class</th>
                <th>Roll</th>
                <th>Section</th>
                <th>Shift</th>
                <th>Status</th>
            </tr>
        </tfoot>
        <tbody>
       	<?php foreach($allInfos['getStdEnrollInfo'] as $enroll):?>
            <tr>
                <td>
                <?php $sid = array_search($enroll['student_id'], $studentColumn);
                			echo $allInfos['getStdStudentInfo'][$sid]['name'];                	
            		?>
                </td>
                <td>
                	<?php $cid = array_search($enroll['class_id'], $classColumn);
                			echo $allInfos['getStdClassInfo'][$cid]['name'];                	
            		?>
                </td>
                <td>
            	<?php echo $enroll['roll']; ?>
                </td>
                <td>
                	<?php $seid = array_search($enroll['section_id'], $sectionColumn);
                			echo $allInfos['getStdSectionInfo'][$seid]['name'];                	
            		?>
                </td>
                <td>
                	<?php $shid = array_search($enroll['shift_id'], $shiftColumn);
                			echo $allInfos['getStdShiftInfo'][$shid]['name'];                	
            		?>
                </td>
                <td>
                <?php $localid = array_search($enroll['student_id'], $localDataColumn);
                	if(is_numeric($localid)){
                		if($localData[$localid]['status']==1){
                			echo "<p style='color: orange; padding: 0; margin: 0; border: 1px solid orange; text-align: center; font-weight: bold;'>In School</p>";
                		}else{
                			echo "<p style='color: green; padding: 0; margin: 0; border: 1px solid green; text-align: center; font-weight: bold;'>Present</p>";
                		}
                	}else{
                		echo "<p style='color: red; padding: 0; margin: 0; border: 1px solid red; text-align: center; font-weight: bold;'>Absent</p>";
                	}
                			
            		?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    	


      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" ></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" ></script>
      <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" ></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
      <script type="text/javascript">
      	$(document).ready(function() {
		    $('#example').DataTable( {
		    	"bSort": false,
		    	dom: 'Bfrtip',
		        buttons: [
		            'print'
		        ],
		        initComplete: function () {
		            this.api().columns('.class').every( function () {
		                var column = this;
		                var select = $('<select><option value="">All</option></select>')
		                    .appendTo( $('.searchCol').empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		 
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    });
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
		            } );
		        }
		    } );
		} );


      	$(document).ready(function(){
		    $('ul.tabs').tabs();
		    $('#example').DataTable();
		  });
      	 function refresh() {
	         //alert('dsa');
	         location.reload();
	     }

	     setTimeout(refresh, 300000);
      </script>
    </body>
</html>

<?php


//die();


//date_default_timezone_set('asia/dhaka');
//session_start();

// //$_SESSION['book'] = 'storybook';

// $time = date('Y-m-d H:i:s', time());

// echo $time;
// session_destroy();
// die();

if(isset($_SESSION['initialize'])) {
	if(time() - $_SESSION['initialize'] > 1800) {
    	session_destroy();
	}	
}

// echo $_SESSION['book'];
// session_destroy();

// die();



// ========== DB CONNECTION ============= //
require 'db/config.php';

// =========== TAD REQUIRE FILE ================ //
require 'lib/TADFactory.php';
require 'lib/TAD.php';
require 'lib/TADResponse.php';
require 'lib/Providers/TADSoap.php';
require 'lib/Providers/TADZKLib.php';
require 'lib/Exceptions/ConnectionError.php';
require 'lib/Exceptions/FilterArgumentError.php';
require 'lib/Exceptions/UnrecognizedArgument.php';
require 'lib/Exceptions/UnrecognizedCommand.php';

use TADPHP\TADFactory;
use TADPHP\TAD;



$comands = TAD::commands_available();
$tad = (new TADFactory(['ip'=>'192.168.1.201']))->get_instance();




// We are creating a superadmin user named 'Foo Bar' with a PIN = 123 and password = 4321.
// $r = $tad->set_user_info([
//     'pin' => 2,
//     'name'=> 'test user'
// ]);
// echo strtotime(date('H:i:s'));
// echo "<br>";
// echo strtotime(date('20:10:00'));
// die();

$response = $tad->get_att_log()->filter_by_date(
    ['start' => date('Y-m-d'),'end' => date('Y-m-d')]
)->to_array();

//echo "<pre>";
//print_r($response);
// $key = array_column($response['Row'], 'PIN');
// $reverseArr = array_reverse($key, true);
// $searchValue = array_search('20', $reverseArr);
// print_r($response);
 //echo $response['Row'][0]['DateTime'];
 //die();



// $query = "SELECT `pin` FROM `attendance` WHERE `status` = 2";
// $result = mysqli_query($db,$query);
// while ($row = mysqli_fetch_assoc($result)) {
// 	$arr[] = $row['pin'];
// }
// print_r($arr);
// die();

if(!empty($response)){
$dups = array();
$arr_col = array_column($response['Row'], 'PIN');
foreach(array_count_values($arr_col) as $val => $c){
    if($c > 1) $dups[] = $val;
    
}

// print_r($dups);
// die();
	// ============ CHECK MULTIDIMATIONAL ARRAY ============= //
	// IF IT IS SINGLE THERE WHERE NO ACTION
	$rv = array_filter ($response['Row'],'is_array');
	if (count($rv)>0){
		foreach ($response['Row'] as $list){

			$pin = $list['PIN']; 
			$date = strtotime(current(explode(' ', $list['DateTime'])));

			// echo '<br>';
			// print_r($date);
			// die();
			$query1 = $db->query("SELECT * FROM `attendance` WHERE `pin` = $pin AND `date` = $date");
			$query2 = "SELECT * FROM `attendance` WHERE `pin` = $pin AND `date` = $date";

			// echo $query->num_rows;
			// die();
			$result = mysqli_query($db,$query2);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

			// $currenTime = strtotime(date("H:i:s"));
			// $afterOneHour = strtotime("+5 minutes", $row['intime']);

			// echo date("H:i:s",$currenTime).'<br>';
			// echo date("H:i:s",$afterOneHour).'<br>';
			// die();
			
			
			if ($query1->num_rows < 1){
				$pin = $list['PIN'];
				$date = strtotime(current(explode(' ', $list['DateTime'])));
				$time = strtotime(end(explode(' ', $list['DateTime'])));
				$absent[] = $db->query("INSERT INTO `attendance` (`pin`,`date`,`intime`) VALUES ($pin, $date, $time)");
			} else{
				if($row['status']==1){
					$reverseArr = array_reverse($arr_col, true);
					$arrIndex = array_search($list['PIN'], $reverseArr);
					$inTime = strtotime("+5 minutes", $row['intime']); 				
					$outStrToTime = strtotime(end(explode(' ', $response['Row'][$arrIndex]['DateTime'])));
					//$outTime = strtotime(date("H:i:s"));
					// print_r(date('H:i:s',$inTime));
					// echo '<br>';
					// print_r(date('H:i:s',$outStrToTime));
					// die();
					if($outStrToTime >= $inTime){
						$pin = $list['PIN']; 
						if(in_array($pin, $dups)){
							$present[] = $db->query("UPDATE `attendance` SET `status`= 2, `outtime` = $outStrToTime WHERE `pin` = $pin AND `date` = $date");	
						}				
					}
				}
			}
			
			
		}

		$date = strtotime(current(explode(' ', $response['Row'][0]['DateTime'])));
		$query3 =  $db->query("SELECT * FROM `attendance` WHERE `date` = $date");
		$totalRow = $query3->num_rows;

		
		// =========== SEND DATA TO SERVER ADDRESS =============== //
		if (strtotime(date('H:i:s')) >= strtotime(date('14:25:00'))) {

			if(!isset($_SESSION['initialize'])){
				$_SESSION['initialize'] = time();	
			}
			
			$limit = 1;
			if(!isset($_SESSION['rowOffset'])){
				$rowOffset = $_SESSION['rowOffset'] = 0;	
			}else{
				$rowOffset = $_SESSION['rowOffset'] = $_SESSION['rowOffset'] + $limit;
			}
			


			if($_SESSION['rowOffset'] < $totalRow){
				$url = 'http://bidyapith2.nihalit.com/index.php?admin/getInfo';

				$date = strtotime(current(explode(' ', $response['Row'][0]['DateTime'])));
				$query = "SELECT * FROM `attendance` WHERE `date` = $date LIMIT $limit OFFSET $rowOffset";
				$result = mysqli_query($db,$query);
				while ($row = mysqli_fetch_assoc($result)) {
					$arr[] = urlencode($row['pin'].'@'.$row['intime'].'@'.$row['outtime'].'@'.$row['status']);
				
				}	
				// $id = array(
				// 	'lname' => urlencode('hello')
				// );
				$fields_string = '';

				//url-ify the data for the POST
				foreach($arr as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				rtrim($fields_string, '&');

				//open connection
				$ch = curl_init();

				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, count($arr));
				curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

				//execute post
				$result = curl_exec($ch);

				//close connection
				curl_close($ch);
				echo 'uploaded';
			}
		} else{
				echo 'not uploaded';
		}
	} else{
		echo 'Secound Initialize';
	}
	
} else{
	echo 'First Initialize';
}	

	



