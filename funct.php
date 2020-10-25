<?php

	$id  = $_SESSION['id']; 

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}

	function getActual()
	{
		
		global $id;
		global $polaczenie;	
		
		if ($rezultat = $polaczenie->query(
		"SELECT * FROM `logs` WHERE `id` = '$id' OR `id` = '$id\n' ORDER by `date` DESC LIMIT 1"))
		{
			$row = $rezultat->fetch_assoc();
			$out['pm1'] = $row['pm1'].' µg/m3';
			$out['pm25'] = $row['pm25'].' µg/m3';
			$out['pm10'] = $row['pm10'].' µg/m3';
			$out['temp'] = $row['temp'].'°C';
			$out['humd'] = $row['humidity'].'%';
			$out['prea'] = $row['pressure'].'HPa';
			$time = $row['date'];
			$time = date_create("$time");
			$time = date_format($time, 'Y-m-d H:i');
			$out['time'] = $time;
			
			return $out;		
		}		
	}
	
	function isTypeOk($type)
	{
		if
		(
			$type == 'pm1' ||
			$type == 'pm25' ||
			$type == 'pm10' ||
			$type == 'humidity' ||
			$type == 'temp' ||
			$type == 'pressure'
		)
		{
			return true;
		}else
			{
				return false;
			}
	}
	
	function generateChart($time, $type)
	{
		global $polaczenie;
		global $id;
		
		
		$date = new DateTime();
 
		if($time != 1)
		{
			$time++;
		}
		
		$interval = new DateInterval("P".$time."D");
		$date->sub($interval);
		
		$date2 = $date->format("Y-m-d H:i:s");
		
		if(isTypeOk($type))
		{
			if($time == 1)
			{
				if ($rezultat = $polaczenie->query(			
				"SELECT * FROM `hours` WHERE `id` = '$id' AND `date` >='$date2' ORDER by `date` ASC"))
				{		
					$num_rows = $rezultat->num_rows;
					$data = '';
					$labels = '';
						
					for($i=0; $i < $num_rows; $i++)
					{
						$row = $rezultat->fetch_assoc();
						$data .= $row["$type"].', ';
						$labels .= '-';
						$labels .= $num_rows - $i.', ';
					}					
				}
			}else
				{
					
					if ($rezultat = $polaczenie->query(			
					"SELECT * FROM `days` WHERE `id` = '$id' AND `date` >= '$date2' ORDER by `date` ASC"))
					{		
						$num_rows = $rezultat->num_rows;
						$data = '';
						$labels = '';
						
						for($i=0; $i < $num_rows; $i++)
						{
							$row = $rezultat->fetch_assoc();
							$data .= $row["$type"].', ';
							$labels .= '-';
							$labels .= $num_rows - $i.', ';
						}					
					}
				}
	
		
		
		
			$ret = "
				var ctx = document.getElementById('myChart').getContext('2d');
				
				var myLineChart = new Chart(ctx, {
					type: 'line',
					 data: {
						labels: [$labels],
						datasets: [{
						   
							backgroundColor: 'rgba(113, 163, 11, 0.7)',
							borderColor: 'rgba(0, 0, 0, 0.7)',
							data: [$data]
						}]
					},
					options: {
						legend:{display:false},
						responsive: true,
						maintainAspectRatio: false,
						elements: {
								   point:{
									  // radius: 0
								   }
						},
						
						scales: {
							xAxes: [{
								gridLines: {
									display:false
								}	   
							}],
							yAxes: [{
								gridLines: {
									display:false
								},
								ticks: {
									
									//beginAtZero: true   // minimum value will be 0.
								}
							}]
						}
					   
					  }
				});";

			echo $ret;
		}
	}
	
	function generateAlgorithm($time)
	{
		global $polaczenie;
		global $id;
		
		
		$date = new DateTime();
 
		$time++;
		
		$interval = new DateInterval("P".$time."D");
		$date->sub($interval);
		$date2 = $date->format("Y-m-d H:i:s");
		
		
					
		if ($rezultat = $polaczenie->query(			
		"SELECT * FROM `days` WHERE `id` = '$id' AND `date` >= '$date2' ORDER by `date` ASC"))
		{		
			$num_rows = $rezultat->num_rows;
			$data = '';
			$labels = '';
						
			for($i=0; $i < $num_rows; $i++)
			{
				$row = $rezultat->fetch_assoc();
				$j = ((($row['pm1']-0)/(40-0))+(($row['pm25']-0)/(25-0))+(($row['pm10']-0)/(50-0))+(($row['temp']-16)/(22-16))+(($row['humidity']-45)/(60-45))+(($row['pressure']-1000)/(1020-1000)))/6;
				$data .= $j.', ';
				$labels .= $num_rows - $i.', ';
			}					
		}
				
	
		
		
		
			$ret = "
				var ctx = document.getElementById('myChart').getContext('2d');
				
				var myLineChart = new Chart(ctx, {
					type: 'line',
					 data: {
						labels: [$labels],
						datasets: [{
						   
							backgroundColor: 'rgba(113, 163, 11, 0.7)',
							borderColor: 'rgba(0, 0, 0, 0.7)',
							data: [$data]
						}]
					},
					options: {
						legend:{display:false},
						responsive: true,
						maintainAspectRatio: false,
						elements: {
								   point:{
									   radius: 0
								   }
						},
						
						scales: {
							xAxes: [{
								gridLines: {
									display:false
								}	   
							}],
							yAxes: [{
								gridLines: {
									display:false
								},
								ticks: {
									
									//beginAtZero: true   // minimum value will be 0.
								}
							}]
						}
					   
					  }
				});";

			echo $ret;
		
	}
	
	function getCor()
	{
		global $polaczenie;
		global $id;
		
		if ($rezultat = $polaczenie->query(
		"SELECT * FROM `devices` WHERE `id` = '$id' "))
		{
			$row = $rezultat->fetch_assoc();
			$out['0'] = $row['y'];
			$out['1'] = $row['x'];
			
			
			return $out;		
		}	
	}











