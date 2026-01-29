<?php
	 include("conexao.php");
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
        // GRAVANDO INFORMAÇÕES NO BANCO DE DADOS
        $nome  = $_POST['nome'];
        $login      = $_POST['login'];
        $email      = $_POST['email'];
        $senha      = $_POST['senha1'];

        $sql = "INSERT INTO usuarios (nome, email, senha, login) VALUES ('$nome', '$email', '$senha', '$login')"; 
        mysql_query($sql) or die(error());



        // FIM  ---  GRAVANDO INFORMAÇÕES NO BANCO DE DADOS
        ?>


        <hr>
      <ul class="nav nav-pills">
        <li class="active"><a href="principal.php">VOLTAR</a></li>
      </ul>
</div> <!-- Final do Container -->        
</body>
</html>
