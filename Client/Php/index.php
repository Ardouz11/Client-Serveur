<html>
  <head>
	<meta http-equiv="refresh" content="30" >
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<title>Temperature Control System</title>
	<meta charset="utf-8">
<?php
exec("/www/c-bin/web_led 0 0");
 //$page = $_SERVER['PHP_SELF'];
 //$sec = "180";
 //header("Refresh: $sec; url=$page");
?>
<?php
	
        $dbh=new PDO("sqlite:/www/Projet/Client/DB/tempbase.db");
        $sql="SELECT * FROM temps;";
        $dataTable="";
        $lastTempValue=0;
        foreach($dbh->query($sql) as $row)
        { 
              $dataTable.="['". substr($row[timestamp],0,16) ." ',   ".$row[temp].",".$_GET['temp_threshold']."],";
              $lastTempValue=$row[temp];
        }
        $dbh=null;
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

         var data = google.visualization.arrayToDataTable([
           ['time','temperature','threshold'],
           <?php echo $dataTable;?>
        ]);

        var options = {
                title: ' Temperature',
                vAxis: { title: "Temperature" },
                legend:{position:'top',alignment:'start'},
                hAxis: {
                    title: "Date",
                    gridlines: { count: 3, color: '#CCC' },
                    format: 'dd-MMM-yyyy'
                }
           };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
	<div class="container-fluid">
	<div class="container">
	<br><br><br>
	<div class="row stats">
	<div class="col-lg-3 offset-lg-1 avatar">
	<img src="rachid.jpg" width="240" height="240"/>
	</div>
	<div class="col-lg-6 offset-lg-1 encadr">
        <p class="name">ARDOUZ RACHID</p>
        <p class="devoir">Embedded Soft </p>
        <p class="name">Temperature Control System</p>
        </div>
	</div>
	<br><br><br><br>
	<div class="row">
	<div class="col-lg-4 service">
	<img src="hot.png" width=80px height=80px>
	<p class="titleServ">Temperature Value</p>
	<p class="titleDesc">Know the real time Temperature Value</p>
	</div>
	<div class="col-lg-4 service">
	<img src="diagram.png" width=80px height=80px>
	<p class="titleServ">Temperature Graph</p>
	<p class="titleDesc">Visualize Temperature Evolution Chart</p>
	</div>
	<div class="col-lg-4 service">
	<img src="list.png" width=80px height=80px>
	<p class="titleServ">Temperature Tables</p>
	<p class="titleDesc">Get Temperature Values of the last Hours</p>
	</div>
	</div>
	</div>
	<br><br><br></div><br><br>
	<div class="container">
	<div class="col-lg-4 offset-lg-4 tempValue card">
	<div class="card-body">
	<div class="card-title real">Real Time Temperature</div>
	<h1 class="realtime"><img src="sun.png" style="margin-right:20px;" width=50px height=50px><?php echo $lastTempValue."°C" ?></h1>
	</div></div>
	</div>
	<br><br>
	<div class="container">
	<div class="card">
	<div class="card-body">
	<div class="card-title graph">Temperature Graph</div>
	<p class="alarm">Choose the alarm intensity to Show the Graph<p>
	<div class="formtresh">
	<form name="form1" method="get" action="index.php">
		<select name="temp_threshold" class="custom-select col-lg-2" id="temp_threshold">
			<?php 
				for($x=15;$x<=45;$x++)
				{
					echo "<option value='".$x."'>".$x."</option>\n";
				}
			?>
		<select/>
		<input name="tempSelect" type="submit" class="btn btn-primary">
	</form>
	<div id="curve_chart" class="col-lg-8 offset-lg-2" style="width: 900px; height: 500px"></div>
	</div>
	<br><br><br>
	<?php

		if(isset($_GET['temp_threshold']))
		{
			$user_Threshold=$_GET['temp_threshold'];
		}
		else
		{
			$user_Threshold = 27;
		}
		echo "<script>
			var temp = ".$user_Threshold.";
			var mySelect = document.getElementById('temp_threshold');

			for(var i, j = 0; i = mySelect.options[j]; j++) {
			    if(i.value == temp) {
				mySelect.selectedIndex = j;
				break;
			    }
			}
			</script>";


	if ($lastTempValue<=$user_Threshold)
	{ 
		exec("/www/c-bin/web_led 0 0");
		//exec("/www/c-bin/pwm 0");
		exec("python /www/c-bin/motor.py 0");
		echo "<div class='card-title real' style='color: green'>Alarm off led off and  DC Motor off</div";
	}
	else{
                exec("/www/c-bin/web_led 0 1");
                //exec("/www/c-bin/pwm 2");
                exec("python /www/c-bin/motor.py 1");
                echo "<div class='card-title real' style='color:green'>Alarm on led on and DC Motor on</div>";
        }

 	?>
	</div></div></div><br><br><br>
	<div class="container">
	<div class="card" style="height:650px;">
	<div class="card-body">
	<div class="card-title graph">Temperature Tables</div><br><br>
	<div class="row">
	<div class="col-lg-2 offset-lg-2 buttons">
    	<button type="button" class="btn btn-success butt" onclick="location.href='index.php?temp_threshold=27&tempSelect=Submit+Query&period=3'">3 Hours</button><br>
	<button type="button" class="btn btn-success butt" onclick="location.href='index.php?temp_threshold=27&tempSelect=Submit+Query&period=6'">6 Hours</button><br>
	<button type="button" class="btn btn-success butt" onclick="location.href='index.php?temp_threshold=27&tempSelect=Submit+Query&period=12'">12 Hours</button><br>
	<button type="button" class="btn btn-success butt" onclick="location.href='index.php?temp_threshold=27&tempSelect=Submit+Query&period=24'">24 Hours</button><br>
	</div>
	<div class="col-lg-4 offset-lg-2 tables" style="overflow:scroll;height:500px;">
    <?php

	function select($period){
		$dbh = new PDO("sqlite:/www/Projet/Client/DB/tempbase.db"); 
		echo '<h1 style="color:white;font-size:1px;">';
		$date_from = date("Y-m-d H:i:s");
		$d=strtotime("- $period hours");
		$date_to= date("Y-m-d H:i:s",$d);
		/*$date_from = date("Y-m-d H:i:s");
		$date_from = system("sudo python /home/pi/Projet/get_period.py $period");
		echo "</h1>";
		$date_to = date("Y-m-d H:i:s");
		//echo $date_from;*/
		$sql = "select * from temps where timestamp between '$date_to' and '$date_from'";
		echo "<table class=\"table\" border=1px><thead class=\"thead-dark\"><tr><td style=\"font-weight:bold;\">Date</td><td style=\"font-weight:bold;\">Temperature</td></tr></thead>";
		foreach($dbh->query($sql) as $row){
			echo "<tr><td style=\"text-align:center;\">$row[timestamp]</td><td>$row[temp]</td></tr>";
		}	
		echo "</table>";
	}

	if(isset($_GET['period']))
	{
		$period = $_GET['period'];
		if($period == 3)
			select(3);
		if($period == 6)
			select(6);
		if($period == 12)
			select(12);
		if($period == 24)
			select(24); 
	}
    ?>
	</div></div></div></div></div></div>
	</div>
	<br><br>
	<div class="container">
	<div class="card">
	<div class="card-body"><div class="card-title graph"><img src="information.png"> Circuit Informations</div><br><br>
	<div class="col-lg-9 offset-lg-2"><ul>
	<li>DS18B20 est simulé par un fichier texte</li>
	<li>LED ( Alarm To the GPIO 17 )</li>
	 <li>DC Motor  ( To the GPIO 21 )</li>
	<li>Choose a threshold to show the Graph !!</li>
	<li>All the links are dynamics, you don't have to worry about linking</li>
	</ul></div>
	</div>
	</div></div><br><br>
  </body>
</html>

