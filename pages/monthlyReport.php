<?php 

// =========== ALL REQUIRE FILE INCLUDED ================ //

require '../db/config.php';

// =========== CORE CALCULATIONS ================ //

require '../db/core.php';

// =========== FATCH LOCAL SERVER DATA ================ //

require '../db/core_two.php';

// ========= CHANGE THIS TO SEE ANOTHOR MONTH REPORT ======= //

if(!empty($_POST)):
  $postMonth = $_POST['month'];
  $totalWorkingDays = 0;
  $selectMonth = date('m', strtotime(date("Y-$postMonth-d")));
  $fullForm = date('F', strtotime(date("Y-$postMonth-d")));
  $startMonth = $_POST['month'];
else:
  $startMonth = 01;
  $fullForm = '';
endif;



?>

<!DOCTYPE html>
  <html>
    <head>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="refresh" content="1000" >    
       
    <title><?php echo $fullForm; ?> Attendance Report</title>
    <link rel="shortcut icon" type="image/png" href="favicon.png"/>
    <!--Import materialize.css-->
    <link rel="stylesheet" href="../src/materialize.min.css">
    <link href="../src/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/styles.css">
      
    </head>

    <body>


    <br><br><br>

    <div class="container">
      <div class="col s4 left">
        <form action="monthlyReport.php" method="post">
          <div class="col s4 left">
              <select name="month">
                <?php foreach(range(1,12) as $eachMonth): ?>

                <option value="<?php echo $eachMonth; ?>" <?php echo selected($startMonth, $eachMonth); ?>><?php echo date('F', strtotime(date("Y-$eachMonth-d")));?></option>

                <?php endforeach; ?>
              </select>
          </div>
  
          <div class="col s4 left">
              &nbsp;&nbsp;&nbsp;<button class="btn waves-effect waves-light btn-small" type="submit">Submit
                <i class="fa fa-location-arrow" aria-hidden="true"></i>
              </button>
          </div>          
          
        </form>
      </div>

<?php if(empty($_POST)): ?>
      <div class="col s4 right">
        <a href="index.php" class="btn waves-effect waves-light">Daily Report
        <i class="fa fa-file-text" aria-hidden="true"></i>
          </a>
      </div>
<?php endif; ?>

<?php if(!empty($_POST)): ?>

      <div class="col s4 right">
      <a href="../index.php" class="btn waves-effect waves-light" title="Daily Attendance Report">Daily Attendance</a>
        <button class="btn waves-effect waves-light" onclick="javascript:printDiv('printablediv')">Print
            <i class="fa fa-print" aria-hidden="true"></i>
          </button>
        
      </div>

    </div>
    
    <br><br><br>


    <div class="container" id="printablediv">
	    <div class="col s4 center">
        <h4><span style="color: #26a69a;"><?php echo $fullForm; ?></span> Attendance Report <?php echo '('.date('Y').')'; ?></h4>
	    </div><br>

      <!-- START TABLE CONTENT -->
    	<table id="example" class="bordered centered" cellspacing="0" width="100%">

      <!-- TABLE HEAD -->
        <thead>
            <tr>
                <th>Days <i class="fa fa-arrows-h" aria-hidden="true"></i><br>Name <i class="fa fa-arrows-v" aria-hidden="true"></i> </th>
                <?php $days = cal_days_in_month(CAL_GREGORIAN, $selectMonth, date('Y')); ?>
                <?php foreach(range(1, $days) as $day): 
                      $countWorkingDays = workingDaysTotal($day, $selectMonth);
                      if($countWorkingDays){
                        $totalWorkingDays += 1;
                      }?>
                <th class="class"><?php echo $day; ?></th>

                <?php endforeach; ?>
                <th>Total</th>
            </tr>
        </thead>
        <!-- TABLE HEAD -->

        <!-- TABLE FOOTER -->
        <tfoot>
          <tr>
            <th>Working Days: </th>
            <th colspan="<?php echo $days+1; ?>" style="text-align: center;">
              <?php echo $totalWorkingDays; ?>
            </th>
          </tr>
        </tfoot>
        <!-- TABLE FOOTER -->

        <!-- TABLE BODY -->
        <tbody>
        <?php foreach($localData1 as $eachData): $totalCount = 0;

        $sortedData = monthlyReportQueryOne($eachData['pin'], $selectMonth);
        ?>
            <tr>
                <td><?php echo $eachData['name']; ?></td>
                <?php $days = cal_days_in_month(CAL_GREGORIAN, $selectMonth, date('Y')); ?>
                <?php foreach(range(1, $days) as $day): ?>
                <?php if(in_array($day, $sortedData)): $totalCount += 1;?>

                    <td class="p-style">P</td>

                <?php else: ?>

                    <td class="a-style">A</td>

                <?php endif; ?>                  
                <?php endforeach; ?>
                <td class="total-style"><?php echo $totalCount; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <!-- TABLE BODY -->

    </table>
    <!-- END TABLE CONTENT  -->

    </div>

<?php endif; ?>


      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="../src/jquery-2.1.1.min.js"></script>
      <script src="../src/materialize.min.js"></script>
      <script src="../src/custom.js"></script>
      
    </body>
</html>
