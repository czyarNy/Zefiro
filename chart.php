<?php
	session_start();
	date_default_timezone_set("Europe/Warsaw");

	if (!isset($_SESSION['id']))
	{
		header('Location: log_form.php');
		exit();
	}
	
	require_once('funct.php');
	
	
	$type = $_GET['type'];
	
	$time = $_GET['time'];

	
	
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>zefiro.pl</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="style.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="assets/small_logo.png">
</head>

<body>
	<header class=" sticky-top">

		<nav class="navbar navbar-light bg-header">
		  <a class="navbar-brand" href="desktop.php">
			<img src="assets/small_logo.png" width="30" height="30" class="d-inline-block align-top" alt=""><span style="margin-left:2px;">efiro</span>
		  </a>
			  
								
			<div class="btn-group dropleft">
			  <button type="button" class="btn btn-outline-success" style="border-radius:7px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
				</svg>
			  </button>
			  <div class="dropdown-menu">
				
				<button class="dropdown-item disabled" type="button"><?php echo strtoupper($_SESSION['id']);?></button>
				<a href="settings.php"><button class="dropdown-item" type="button">Ustawienia</button></a>
				<a href="logout.php"><button class="dropdown-item" type="button">Wyloguj</button></a>
			  </div>
			</div>

		</nav>

	</header>
		
	<main>
		<section class="mt-4 main-box">
			<div class="container">
			
				<div class="row row-header">
					<div style="width:48px;" class="float-left">
						<img class="img-fluid" src="assets/wykres.png"/>
					</div>
					<div class="float-left">
						<div class="header-title">Wykres jakości powietrza</div>
						<div class="header-subtitle">Wygenerowane na podstawie danych 
							
						</div>
					</div>
				</div>
				 
				<div class="row">
					<div class="col-12 text-center">
						<div class="btn-group pb-5" role="group">
						  <button onclick="window.location.href='?type=pm1&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='pm1')echo 'btn-success';else echo 'btn-secondary';?>">PM1</button>
						  <button onclick="window.location.href='?type=pm25&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='pm25')echo 'btn-success';else echo 'btn-secondary';?>">PM2.5</button>
						  <button onclick="window.location.href='?type=pm10&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='pm10')echo 'btn-success';else echo 'btn-secondary';?>">PM10</button>
						  <button onclick="window.location.href='?type=temp&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='temp')echo 'btn-success';else echo 'btn-secondary';?>">Temperatura</button>
						  <button onclick="window.location.href='?type=humidity&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='humidity')echo 'btn-success';else echo 'btn-secondary';?>">Wilgotność</button>
						  <button onclick="window.location.href='?type=pressure&time=<?php echo $time?>'" type="button" class="btn <?php if($type=='pressure')echo 'btn-success';else echo 'btn-secondary';?>">Ciśnienie</button>
						</div>
						<div class="fixed-height-chart">
							<canvas id="myChart"></canvas>
						</div>
						<div class="btn-group pt-3" role="group">
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=360'" type="button" class="btn <?php if($time==360)echo 'btn-success';else echo 'btn-secondary';?>">Rok</button>
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=180'" type="button" class="btn <?php if($time==180)echo 'btn-success';else echo 'btn-secondary';?>">6 miesięcy</button>
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=90'" type="button" class="btn <?php if($time==90)echo 'btn-success';else echo 'btn-secondary';?>">3 miesiące</button>
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=30'" type="button" class="btn <?php if($time==30)echo 'btn-success';else echo 'btn-secondary';?>">1 miesiąc</button>
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=7'" type="button" class="btn <?php if($time==7)echo 'btn-success';else echo 'btn-secondary';?>">Tydzień</button>
						  <button onclick="window.location.href='?type=<?php echo $type?>&time=1'" type="button" class="btn <?php if($time==1)echo 'btn-success';else echo 'btn-secondary';?>">24 godziny</button>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-12">
						</br></br><center>www.zefiro.pl</center></br>
					</div>
				</div>
			
				
			</div>
		</section>
	</main>
	
<script>
<?php generateChart($time, $type);?>
</script>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
<?php $polaczenie -> close();?>