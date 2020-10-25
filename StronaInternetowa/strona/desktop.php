<?php
	session_start();
	date_default_timezone_set("Europe/Warsaw");

	if (!isset($_SESSION['id']))
	{
		header('Location: log_form.php');
		exit();
	}
	
	require_once('funct.php');
	

	
	
?>
<?php $actualData = getActual();?>
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

		<nav class="navbar navbar-light bg-header ">
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
								<img class="img-fluid" src="assets/wind.png"/>
							</div>
							<div class="float-left">
								<div class="header-title">Aktualna jakość powietrza</div>
								<div class="header-subtitle"><?php echo $actualData['time'];?></div>
									
								
							</div>
			</div>
			
				  <div class="row">
					<div class="col-md-3">
						<a href="chart.php?time=1&type=pm1">
							<div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/pm1.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">PM 1</span></br>
									
									<span class="data-once-content"><?php echo $actualData['pm1'];?></span>
										
									
								</div>
							</div>
						</a>
						<a href="chart.php?time=1&type=pm25">
							<div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/pm25.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">PM 2.5</span></br>
									
									<span class="data-once-content"><?php echo $actualData['pm25'];?></span>
										
									
								</div>
							</div>
						 </a>
						 <a href="chart.php?time=1&type=pm10">
							 <div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/pm10.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">PM 10</span></br>
									
									<span class="data-once-content"><?php echo $actualData['pm10'];?></span>
										
									
								</div>
							</div>
						</a>
					</div>
					
					<div class="col-md-3">
						<a href="chart.php?time=1&type=temp">
							<div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/temp.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">Temperatura</span></br>
									
									<span class="data-once-content"><?php echo $actualData['temp'];?></span>
										
									
								</div>
							</div>
						</a>
						<a href="chart.php?time=1&type=humidity">
							<div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/wilg.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">Wilgotność</span></br>
									
									<span class="data-once-content"><?php echo $actualData['humd'];?></span>
										
									
								</div>
							</div>
						</a>
						<a href="chart.php?time=1&type=pressure">
							<div class="data-once">
								<div style="width:20%;" class="float-left">
									<img class="img-fluid" src="assets/pres.png"/>
								</div>
								<div class="float-left">
									<span class="data-once-title">Ciśnienie</span></br>
									
									<span class="data-once-content"><?php echo $actualData['prea'];?></span>
										
									
								</div>
							</div>
						</a>
					</div>
					
					<div class="col-md-6">								
						<img class="device-img" src="assets/device.png"/>				
					</div>			
					
				  </div>
				  <div class="row row-header">
						<div style="width:48px;" class="float-left">
										<img class="img-fluid" src="assets/jakosc.png"/>
									</div>
									<div class="float-left">
										<div class="header-title">Ogólna jakość powietrza</div>
										<div class="header-subtitle">na podstawie algorytmu 
										<button  data-toggle="modal" data-target="#howAlgorithm">
										  [?]
										</button></div>
											
										
									</div>
					</div>
				  <div class="row">
					<div class="col-12">
						<div class="fixed-height-chart">
							<canvas id="myChart"></canvas>
						</div>
					</div>
					
					
					
				  </div>
				  
				  <div class="row row-header">
						<div style="width:48px;" class="float-left">
										<img class="img-fluid" src="assets/mapa.png"/>
									</div>
									<div class="float-left">
										<div class="header-title">Twoje urządzenie na mapie</div>
										<div class="header-subtitle">oszacowane na podstawie Wi-Fi 
										<button  data-toggle="modal" data-target="#howWiFi">
										  [?]
										</button></div>
											
										
									</div>
					</div>
				  <div class="row">
					<div class="col-12">
					<?php
					$ar_cor = getCor();
					
					$y1 = $ar_cor['0'] - 0.1132965088;
					$y2 = $ar_cor['0'] + 0.1132965088;
					$x1 = $ar_cor['1'] - 0.04787059;
					$x2 = $ar_cor['1'] + 0.04787059;		
					?>
						<iframe 
						width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
						src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo $y1;?>%2C<?php echo $x1;?>%2C<?php echo $y2;?>%2C<?php echo $x2;?>&amp;layer=mapnik&amp;marker=<?php echo $ar_cor['1'];?>%2C<?php echo $ar_cor['0'];?>"
						style="border: 1px solid black">
						</iframe><br/><small>
						<a href="https://www.openstreetmap.org/?mlat=52.4192&amp;mlon=21.1761#map=13/52.4192/21.1761">
						Wyświetl większą mapę</a></small>
					</div>
					
					
					
				  </div>
				  
					<div class="row">
						<div class="col-12">
							</br><center>www.zefiro.pl</center></br></br>
						</div>
					</div>
				  
				  
			
			</div>
		</section>
	</main>
	
	<!-- Modal -->
	<div class="modal fade" id="howWiFi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">Skąd znamy lokalizację urządzenia?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  	Szacujemy to na podstawie lokalizacji GPS telefonu podczas łączenia Zefiro z siecią Wi-Fi w miejscu instalacji urządzenia
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Już rozumiem</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="howAlgorithm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLongTitle">Jak obliczamy ogólną jakość powietrza?</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		 Na podstawie poniższego algorytmu</br>
		 <img src="assets/alg.png" class="img-fluid"/></br>
		 <b>J</b> - współczynnik jakości powietrza</br>
		 <b>P</b> - pomiar</br>
		 <b>m</b> - minimalna zdrowa wartość (może być 0)</br>
		 <b>M</b> - maksymalna zdrowa wartość</br>
		 <b>W</b> - waga pomiaru</br>
		 <b>N</b> - liczba pomiarów składających się na współczynnik <b>J</b></br>
		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Już rozumiem</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Nie rozumiem, ale dobra</button>
		  </div>
		</div>
	  </div>
	</div>
	
<script>

<?php generateAlgorithm(30);?>
</script>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
<?php $polaczenie -> close();?>