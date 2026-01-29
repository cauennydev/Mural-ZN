<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <title>Mural Eletronico - Administra&ccedil&atildeo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Sistema de Mural Digital">

	<title>MURAL - Menu</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
	<link rel="shortcut icon" href="img/favicon.ico">
	<meta charset="utf-8" />

<body>
<div class="container">	
		<!--  inserindo formulário		-->		
		<form class="form-signin" action="login.php" method="post" name="formlogin">
			<img src="img/logo_mural.jpg">
			<h3> Administra&ccedil&atildeo</h3>		
			<?php
				// Exibir mensagem de erro caso ocorra.
				if (isset($_GET["erro"])) 
				{
					$erro = $_GET["erro"];
					echo  $erro .'</div>';
				}
			?>
			<input class="input-block-level" placeholder="Login" type="text" 	name="login" size="50" /> 
			<input class="input-block-level" placeholder="Senha" type="password" name="senha" size="50" />			
			<label class="checkbox">
				<input type="checkbox" value="remember-me"> Lembrar senha
        	</label>

			<input class="btn btn-large btn-primary" type="submit" value="Entrar">
			<hr>
			<ul class="nav nav-pills">
				<li class="button"><a href="index.php">VOLTAR</a></li>
			</ul>
		</form>					
		<!--  Final do formulário		-->	
</div> <!-- Final do Container -->

<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

</body>
</html>