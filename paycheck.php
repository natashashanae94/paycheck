<?php
/*Natasha Johnson
    CTP 130
    Lab 3*/

  $employeeID = $_POST['employeeID'];
  $hoursWorked = $_POST['hoursWorked'];
  $hourlyWage = $_POST['hourlyWage'];
 ?>

<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      table {
        border: 1px solid #000;
      }

      th {
        padding: 10px;
      }
    </style>
  </head>
  <body>
    <?php
    /*Check Input Value when form is submitted*/
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if(empty($employeeID)) {
              $employeeID = NULL;
              $identification = print "<p>Please enter your employee ID.</p>";
            }

            if(empty($hoursWorked) && $hoursWorked <= 0) {
              $hoursWorked = NULL;
              $hours = print "<p>Please enter the hours you worked this week</p>";
            }

            if(empty($hourlyWage) && $hourlyWage <= 0) {
              $hourlyWage = NULL;
              $wage = print "<p>Please enter your hourly wage</p>";
            }
        }
      ?>
      <!--STICKY FORM-->
        <form method="POST" action="">
          <table>
            <th colspan="2">Paycheck Calculator</th>
            <tr>
              <td><label for="employee">Employee ID:</label></td>
              <td><input type="text" name="employeeID" value="<?php echo $employeeID ?>"></td>
              <?php $identification; ?>
            </tr>
            <tr>
              <td><label for="hours">Hourly Worked:</label></td>
              <td><input type="text" name="hoursWorked" min="1" max="60" value="<?php echo $hoursWorked ?>"></td>
              <?php $hours; ?>
            </tr>
            <tr>
              <td><label for="wage">Hourly Wage:</label></td>
              <td><input type="text" name="hourlyWage" value="<?php echo $hourlyWage ?>"></td>
              <?php $wage; ?>
            </tr>
          </table>
          <input type="reset" name="reset-btn">
          <input type="submit" name="submit-btn">
        </form>

      <?php

        /*CALCULATE GROSS PAY WITH OVERTIME*/
        $overtimePay = 0;
        $overtimeHours = 0;
        $grossPay = 0;
        $date = date("m-d-Y");

        if($hoursWorked <= 40) {
          $grossPay = $hoursWorked * $hourlyWage;
        } else {
          $overtimeHours = $hoursWorked - 40; #Result: 5
          $overtimePay = ($overtimeHours * $hourlyWage) / 2; #Result: 25
          $grossPay = ($hoursWorked * $hourlyWage) + $overtimePay;
        }

      /*PAYCHECK REPORT*/
        echo "<p>Date: $date</p>";
        echo "<p>Employee ID: $employeeID</p>";
        echo "<p>Hourly Pay Rate: $". number_format($hourlyWage, 2). "</p>";
        echo "<p>Gross Pay: $". number_format($grossPay, 2). "</p>";

        /*WRITE INTO FILE*/
        $file = "paycheck.txt";
        $data = array($employeeID, $hoursWorked, $hourlyWage, $grossPay, $date);
        file_put_contents($file, implode(' ', $data)."\n",FILE_APPEND | LOCK_EX);

        /*CREATE TABLE*/
        echo '<table border="1">';
        $file = fopen("paycheck.txt", "r") or die("Unable to open file!");

        while(!feof($file)) {
          $data = fgets($file);
          echo"<tr><td>".str_replace('|','</td><td>', $data).'</td></tr>';
        }
          echo '</table>';
        fclose($file);
      ?>
  </body>
</html>
