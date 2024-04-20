<?php
	function canLogin($pEmail, $pPassword){
		if($pEmail === "milana_is@hotmail.com" && $pPassword === "123"){
			return true;
		}	
		else{
			return false;
		}
	}


	if(!empty($_POST)){
		$email = $_POST['email'];
		$password = $_POST['password'];
		//var_dump($password);
		if(canLogin($email, $password)){
			$salt = "kjhfndjlksj!"; //random keys 
			$cookieValue = $email . "," . md5($email.$salt); // maak de cookie uniek zodat een hacker het niet kan bereiken 
			setcookie('loggedin', $cookieValue, time()+60*60*24*30); //maand //console - application - cookies - expires
			header('Location: dashboard.php');
		} else{
			$error = true;
		}
	}