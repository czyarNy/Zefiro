<?php

	session_start();



	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: desktop.php');
		exit();
	}
	
	
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>zefiro.pl</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="shortcut icon" href="assets/small_logo.png">
<style>
.login-form {
    width: 340px;
    margin: 50px auto;
  	font-size: 15px;
}
.login-form form {
    margin-bottom: 15px;
    background: #f7f7f7;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    padding: 30px;
}
.login-form h2 {
    margin: 0 0 15px;
}
.form-control, .btn {
    min-height: 38px;
    border-radius: 2px;
}
.btn {        
    font-size: 15px;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="login-form">
    <form action="zaloguj.php" method="post">
        <h2 class="text-center">Zaloguj się</h2>       
		<?php
			if(isset($_SESSION['blad']))
			{
				echo '<center><div id="error">'.$_SESSION['blad'].'</div></center>';
				unset($_SESSION['blad']);
			}
		?>
        <div class="form-group">
            <input type="text" name="login" <?php if(isset($_SESSION['saved_login'])){echo 'value="'.$_SESSION['saved_login'].'"'; ; unset($_SESSION['saved_login']);}?> class="form-control" placeholder="Numer urządzenia" required="required">
        </div>
        <div class="form-group">
            <input type="password" name="haslo" class="form-control" placeholder="Hasło" required="required">
        </div>
        <div class="form-group">
            <button type="submit"  class="btn btn-primary btn-block">Logowanie</button>
        </div>
		
		<a href="https://zefiro.pl">Strona główna</a>
       
    </form>
    
</div>
</body>
</html>




<!---
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Zaloguj się</title>
	<link href="index_style.css" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Oswald|Rubik+Mono+One&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

	
	<div>Zaloguj się</div>
	<?php
		if(isset($_GET['error']))
		{
			echo '<center>'.$_GET['error'].'</center>';
		}
	?>
	<form action="zaloguj.php" method="post">

		

		<?php unset($_SESSION['saved_user']); ?>

		<input type="text" name="login" <?php if(isset($_SESSION['saved_login'])){echo 'value="'.$_SESSION['saved_login'].'"'; ; unset($_SESSION['saved_login']);}?>/>
		<input type="password" name="haslo" />
		
		<input type="submit" value="Zaloguj się" /></br></br>
	
	
	</form>
	
</body>
</html>
