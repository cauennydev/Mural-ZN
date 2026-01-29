<?php
	 include("conexao.php");
	 include("validar.php");

?>
<?php
$sql = mysql_query("select * from midias order by cod_midia desc");
$row = mysql_num_rows($sql);
if ($row > 0) {
	
	while ( $linha = mysql_fetch_array($sql)) {
		$arquivo = $linha['arquivo'];
		echo "<img src='images/	$arquivo'>";				  

	}
	
}
else { 

	echo "<h4>Nenhum registro encontrado</h4>";
}
?>