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

  <title>MURAL - Menu</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="css/estilo.css"/>
  <link rel="shortcut icon" href="img/favicon.ico">
  <meta charset="utf-8" />
<body>
<div class="container">
  <div class="quadro">
      <?php
            // ENVIANDO ARQUIVO ANEXADO PARA SERVIDOR
      // Repassa a variável do upload
      $arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE; 
      // Caso a variável $arquivo contenha o valor FALSE, esse script foi acessado 
      // diretamente, então mostra um alerta para o usuário 
      if(!$arquivo)
       { echo "Não acesse esse arquivo diretamente!";

        } 

        // Imagem foi enviada, então a move para o diretório desejado 
        else { 
        // Diretório para onde o arquivo será movido 
        $diretorio = "./images/"; 
        // Move o arquivo 
        // Lembrando que se $arquivo não fosse declarado no começo do script, 
        // você estaria usando $_FILES["arquivo"]["tmp_name"] e $_FILES["arquivo"]["name"] 
        if (move_uploaded_file($arquivo["tmp_name"], $diretorio . $arquivo["name"])) 
        { 
        	echo "Arquivo Enviado com sucesso!"; 
        } else 
        	{ echo "Erro ao enviar seu arquivo!"; 
        }   
      }
      // FIM ----- ENVIANDO ARQUIVO ANEXADO PARA SERVIDOR
      ?>


      <?php 
      // GRAVANDO INFORMAÇÕES NO BANCO DE DADOS
      $descricao  = $_POST['descricao'];
      $arquivo    = $_FILES["arquivo"]["name"];
      $data_inicio = $_POST['data_inicio'];
      $data_final = $_POST['data_final'];
      $ativo      = $_POST['ativo'];

      $sql = "INSERT INTO midias (DESCRICAO, ARQUIVO, DATA_INICIO, DATA_FINAL, ATIVO) VALUES ('$descricao', '$arquivo', '$data_inicio', '$data_final', '$ativo')"; 
      mysql_query($sql) or die(error());



      // FIM  ---  GRAVANDO INFORMAÇÕES NO BANCO DE DADOS
      ?>




      <hr>
            <ul class="nav nav-pills">
              <li class="active"><a href="principal.php">VOLTAR</a></li>
            </ul>
  </div>            
</div> <!-- Final Containder -->
</body>
</html>
