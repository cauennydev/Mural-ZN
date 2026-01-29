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

<!-- RECEBENDO DADOS DO FORM E ENVIANDO PARA O BANCO DE DADOS -->
<?php
$cod_midia = $_POST['cod_midia'];
$arq = $_POST['arquivo'];
//if ($confirma == "SIM") 
{
	$sql = mysql_query("delete from midias where cod_midia = '$cod_midia'");
	// Script para deletar arquivos
	// unlink -> função do php para deletar arquivo 
	if (!unlink($arq))	{
	  echo "Erro ao deletar" .$arq;
	}
	else	{
	  echo "Arquivo <b>" .$arq ." </b>deletado com sucesso do servidor!";
	}
	echo "<h4>Mídia removida do banco de dados com sucesso!</h4>";	
		}
	//	else
		{
	//	echo "<h4>Operação não realizada</h4>";		
		}
	header('Location: lista_midias_hoje.php');
?>

	</div>
</div> <!-- Final do Container -->
</body>
</html>
