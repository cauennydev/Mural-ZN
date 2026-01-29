<?php
	 include("conexao.php");
	 include("validar.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Mural Eletronico - Administra&ccedil&atildeo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Sistema de Mural Digital">

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
	<link rel="shortcut icon" href="img/favicon.ico">
	<meta charset="utf-8" />

<body>
<div class="container">			
	<div class="quadro">	

<h1><a href="principal.php">VOLTAR</a></h1>
<!-- RECEBENDO DADOS DO FORM E ENVIANDO PARA O BANCO DE DADOS -->
<?php
$cod_usuario = $_POST['cod_usuario'];
//if ($confirma == "SIM") 
{
$sql = mysql_query("delete from usuarios where cod_usuario = '$cod_usuario'");
	echo "<h4>Usuário excluido do banco de dados com sucesso!</h4>";	
	}
//	else
	{
//	echo "<h4>Operação não realizada</h4>";		
	}

?>
<hr>
					<ul class="nav nav-pills">
						<li class="button"><a href="principal.php">VOLTAR</a></li>
					</ul>
	</div>
</div> <!-- Final do Container -->

</body>
</html>
