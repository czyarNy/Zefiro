<?php
	session_start();
	date_default_timezone_set("Europe/Warsaw");

	if (!isset($_SESSION['id']))
	{
		header('Location: log_form.php');
		exit();
	}
	
	require_once('funct.php');
	
	$id  = $_SESSION['id']; 

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}else
		{
			if ($rez = $polaczenie->query(
				"SELECT * FROM `devices` WHERE id='$id'"))
				{
					$row = $rez->fetch_assoc();
					$dev_color = $row['color'];
					$dev_mode = $row['mode'];
				}
			
			
			if(isset($_POST['options']))
			{
				$mode = $_POST['options'];
				$color = substr($_POST['color'], 1, 6);
				$pass = $_POST['password'];
				$pass2 = $_POST['password2'];
				
				
				
				
				if(strlen($pass) > 0)
				{
					$pass = password_hash("$pass", PASSWORD_DEFAULT);
					if ($polaczenie->query(			
					"UPDATE `devices` SET `color` = '$color', `mode`=$mode, `password`='$pass' WHERE `id` = '$id'"))
					{		
						header("Location: settings.php?change=true&pass=true");
						
					}
				}else
					{
						if ($polaczenie->query(			
						"UPDATE `devices` SET `color` = '$color', `mode`=$mode WHERE `id` = '$id'"))
						{		
							header("Location: settings.php?change=true&pass=false");
							
						}
					}
			}
		}
	
	$polaczenie->close();
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Piazzolla:wght@200;400&display=swap" rel="stylesheet">
	<link href="index_style.css" rel="stylesheet" type="text/css"/>
	<title>zefiro.pl</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
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
								<img class="img-fluid" src="assets/sett.png"/>
							</div>
							<div class="float-left">
								<div class="header-title">Ustawienia urządzenia</div>
								<div class="header-subtitle">Zarządzaj swoim urządzeniem</div>
									
								
							</div>
			</div>
				<form method="post">
					<div class="row">
						 
						<div class="col-sm text-center">
							<div class="form-group">
								<div class="h4 text-left">Tryb oświetlenia</div><hr>	
								<div class="btn-group-vertical btn-group-toggle" data-toggle="buttons" >
								  <label class="btn btn-outline-dark active">						  
									<input type="radio" name="options" id="option0" value="0" <?php if($dev_mode == 0)echo 'checked';?>> Jednolity
								  </label>
								  <label class="btn btn-outline-dark">
									<input type="radio" name="options" id="option1" value="1" <?php if($dev_mode == 1)echo 'checked';?>> Tęcza
								  </label>
								  <label class="btn btn-outline-dark">
									<input type="radio" name="options" id="option2" value="2" <?php if($dev_mode == 2)echo 'checked';?>> Piksel Przy Pikselu
								  </label>
								  <label class="btn btn-outline-dark">
									<input type="radio" name="options" id="option3" value="3" <?php if($dev_mode == 3)echo 'checked';?>> Losowy Piksel Przy Pikselu
								  </label>
								  <label class="btn btn-outline-dark">
									<input type="radio" name="options" id="option4" value="4" <?php if($dev_mode == 4)echo 'checked';?>> Znikanie
								  </label>
								  <label class="btn btn-outline-dark">
									<input type="radio" name="options" id="option5" value="5" <?php if($dev_mode == 5)echo 'checked';?>> Znkinanie - Fala
								  </label>
								</div>
							</div>
						</div>	
						
						<div class="col-sm">
							<div class="form-group">
								<div class="h4">Hasło</div><hr>	
								Nowe hasło:
								<input name="password" type="password" class="form-control"><br/>
								Powtórz hasło:
								<input name="password2" type="password" class="form-control">
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<br/><br/><br/>
								<div class="h4">Kolor oświetlenia</div><hr>	
								Nowy kolor:&emsp;
								<input value="#<?php echo $dev_color?>" type="color" name="color">
							</div>
						</div>
						
					</div>
					
					<div class="row">
						
						<div class="col-sm text-center">
							<button type="submit" class="btn btn-dark">Zapisz</button>
						</div>
					</div>
					
				</form>
			</div>
		</section>
	</main>
	



<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
