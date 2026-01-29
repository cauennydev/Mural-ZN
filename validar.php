<?php 
 session_start();
 if((!isset($_SESSION['login']) == true) and (!isset($_SESSION['senha']) == true))
	{
		session_destroy();
		header('location:formlogin.php?erro=<div class="alert alert-error">Faça o login novamente!');
	} 

else {

		$logado = $_SESSION['login'];
		echo 'Usuario:<strong> ' .$logado .'</strong>';
		
	}

?>









		

