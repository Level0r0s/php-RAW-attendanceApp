<?php 


// =========== ALL REQUIRE FILE INCLUDED ================ //

require 'db/config.php';

// =========== CORE CALCULATIONS ================ //

require 'db/core.php';

// =========== FATCH LOCAL SERVER DATA ================ //

require 'db/core_two.php';



?>

<!DOCTYPE html>
  <html>
    <head>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="refresh" content="600" >    

    <title><?php echo date('d-M-Y'); ?> Attendance</title>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
    <!-- <link rel="stylesheet" href="src/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="src/buttons.dataTables.min.css">
    <!--Import materialize.css-->
     <link rel="stylesheet" href="src/materialize.min.css">
     <link href="src/font-awesome.css" rel="stylesheet">
     <link rel="stylesheet" href="src/styles.css">      
    </head>

    <body>
    
    <div class="container" style="margin-top: 10px">
      <div class="row">
        <div class="col s3">
          <a href="pages/monthlyReport.php" class="btn waves-effect waves-light button-size">Monthly Report
          <i class="fa fa-file-text" aria-hidden="true"></i>
            </a>
        </div>        

        <div class="col s2 center">
          <button class="btn waves-effect waves-light button-size" onclick="javascript:printDiv('printablediv')">Print
              <i class="fa fa-print" aria-hidden="true"></i>
          </button>
          
        </div>
        
        <div class="col s2 center">
          <button class="btn waves-effect waves-light button-size" onclick="location.reload()">Reload
            <i class="fa fa-refresh" aria-hidden="true"></i>
          </button>
        </div>

         <div class="col s2">
          <a href="index.php" class="btn waves-effect waves-light button-size">Today
          <i class="fa fa-file-text" aria-hidden="true"></i>
            </a>
        </div>

        <div class="col s3">
          <form action="index.php" method="POST">
              <div class="col s8">
                <input placeholder="Date" id="date" type="date" name="date" class="datepicker">
              </div>
              <div class="col s4">  
                <button type="submit" class="waves-effect waves-light btn button-size"><i class="fa fa-search"></i></button>
              </div> 
          </form>     
        </div>
      </div>
    </div>
    
    <div class="container" id="printablediv">    
      
      <div class="col s4 center">
          <h4><span style="color: #26a69a;"><?php echo date('d-M-Y', strtotime($searchDate)); ?></span> Attendance Report</h4>
      </div>

    	<table id="" class="bordered centered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th class="class">ID</th>
                <th>Name</th>
                <th>In-Time</th>
                <th>Out-Time</th>
                <th>Hour-Count</th>
                <th>Current-Status</th>
            </tr>
        </thead>
        <tbody>

       	<?php if(empty($postResult)): foreach($localData1 as $k=>$each):?>
            <tr>
                <td>
                  <?php echo $k+1;?>
                </td>
                <td>
                	 <?php echo $each['pin'];?>
                </td>
                <td>
            	     <?php 
                      echo $each['name'];
                    ?>
                </td>
                <td>
                	<?php 
                  if(is_numeric($pin = array_search($each['pin'], $teacher_pin))){
                      echo $localData[$pin]['intime'] == true ? date('h:i A', $localData[$pin]['intime']):'No Out'; 
                  }else{
                    echo 'Absent';
                  }
                  ?>
                </td>
                <td>
                	<?php 
                  if(is_numeric($pin = array_search($each['pin'], $teacher_pin))){
                      echo $localData[$pin]['outtime'] == true ? date('h:i A', $localData[$pin]['outtime']):'No Out'; 
                  }else{
                    echo 'Absent';
                  }
                  ?>
                </td>
                <td>
                <?php 
                if(is_numeric($pin = array_search($each['pin'], $teacher_pin))){ 
                      if($localData[$pin]['outtime']){
                        echo hour_count(date('h:i:s A', $localData[$pin]['outtime']), date('h:i:s A', $localData[$pin]['intime']));
                      }else{
                        echo hour_count(date('h:i:s A'), date('h:i:s A', $localData[$pin]['intime']));
                      }
                  }else{
                    echo 'Absent';
                  }
                ?>
                </td>
                <td>
                  <?php 
                  if(is_numeric($pin = array_search($each['pin'], $teacher_pin))){
                      if($localData[$pin]['outtime']){
                        echo "<p style='background-color: #e67e22; width: 50%; color: #fff; padding: 0; margin: 0 auto; border: 1px solid #e67e22; text-align: center; font-weight: bold;'>OUT</p>";
                      }else{
                        echo "<p style='background-color: #27ae60; width: 50%; color: #fff; padding: 0; margin: 0 auto; border: 1px solid #27ae60; text-align: center; font-weight: bold;'>IN</p>";
                      } 
                  }else{
                    echo "<p style='background-color: #c0392b; width: 50%; color: #fff; padding: 0; margin: 0 auto; border: 1px solid #c0392b; text-align: center; font-weight: bold;'>ABSENT</p>";
                  }
                  ?>
                </td>
            </tr>
        <?php endforeach; else:?>
            <tr>
              <td colspan="7" class="center">No Records Found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    	


      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="src/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="src/jquery.dataTables.min.js" ></script>
      <script type="text/javascript" src="src/dataTables.buttons.min.js" ></script>
      <script type="text/javascript" src="src/buttons.print.min.js" ></script>
      <script src="src/materialize.min.js"></script>
      <script src="src/custom.js"></script>
      
    </body>
</html>
