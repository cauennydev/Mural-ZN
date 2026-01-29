<?php
	 include("conexao.php");
	 include("validar.php");
?>

<!-- RECEBENDO DADOS DO FORM E ENVIANDO PARA O BANCO DE DADOS -->

<?php
include("validar.php");
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
	 // echo "Arquivo <b>" .$arq ." </b>deletado com sucesso do servidor!";
	}
	//echo "<h4>Mídia removida do banco de dados com sucesso!</h4>";	
		}
	//	else
		{
	//	echo "<h4>Operação não realizada</h4>";		
		}
header('Location: lista_midias.php');
?>
