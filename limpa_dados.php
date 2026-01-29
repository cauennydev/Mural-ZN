<?php
	 include("conexao.php");
	 include("validar.php");
?>
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <title>Mural Eletronico - Administra&ccedil&atildeo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Sistema de Mural Digital">


	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
	<link rel="shortcut icon" href="img/favicon.ico">
	<meta charset="utf-8" />
</head>
<body>
<div class="container">	
	<div class="quadro">				
			<!-- RECEBENDO DADOS DO FORM E ENVIANDO PARA O BANCO DE DADOS -->
			<?php
			$confirma = $_POST['confirma'];
			if ($confirma == "SIM") {
			$sql = mysql_query("delete from midias");
				echo "<h3>Todos os dados foram removidos do banco de dados</h3>";	
				}
				else
				{
				echo "<h3>Operação não realizada</h3>";		
				}
			?>
			<hr>
				<ul class="nav nav-pills">
					<li class="active"><a href="principal.php">VOLTAR</a></li>
				</ul>
	</div>	
</div> <! fim do container -->
</body>
</html>
