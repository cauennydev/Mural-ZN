<?php
	 include("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Mural Eletronico</title>

	<!-- JavaScrip ATUALIZANDO A PÁGINA DE TEMPOS EM TEMPOS PARA ATUALIZAR AS NOTICIAS - 3 MINUTOS -->
	<script language="JavaScript">
		<!--
		setTimeout('delayReload()', 15*60*1000);  /* TEMPO PARA RECARREGAR A PÁGINA EM ms. (60000ms = 60s = 1min.) */
											  /* CONF. 25s para cada slide = TOTAL DE 50 sides */
											  /* 650.000ms/1000 = 650s/60 = 10,8min */
		function delayReload()
		{
		if(navigator.userAgent.indexOf("MSIE") != -1){
		history.go(0);
		}else{
		window.location.reload();	}
	}
	//-->
	</script>

<link rel="stylesheet" type="text/css" href="css/mural.css"/>
<script language='javascript' src='js/clock.js'></script>			
<link rel="stylesheet" type="text/css" href="css/relogio.css">

<!-- LINK PARA JavaScrip JQuery -->
<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/jquery.cycle.all.js"></script>



<!--  SLIDER - Script para transição de slides -->
<script type="text/javascript">
$(document).ready(function() {
    $('.slideshow').cycle({
		fx: 'scrollUp', // choose your transition type, ex: fade, scrollUp, shuffle, zoom, turnDown, curtainX etc...
		speed: 1500,
		timeout: 25000
	});	
});
</script>
</head>


<body>
	<div class="slideshow">
			<?php 
				date_default_timezone_set('America/Manaus');
			?>

			<div class="relogio">
				<div class="hora" id='clock_tm'>Hora Atual</div>
				<div class="data" id='clock_dt'>Data Atual</div>
			</div>			

			<?php			
				
				/*$dia = date(" Y-m-d H:i:s"); // Data Atual do sistema. Pega a data do relogio do PC ou dispositivo.
				$month = substr($dia,6,2);
				$date = substr($dia,9,2);
				$year = substr($dia,1,4);

				$hoje = "'" .$year .'/' .$month .'/' .$date ."'";
				$hojeBR = "'".$date.'/'.$month.'/'.$year."'";

				$sql = mysql_query("select * from midias where data_inicio <= " .$hoje ." and data_final >= " .$hoje ."order by cod_midia desc");
				$row = mysql_num_rows($sql);
				if ($row > 0) {					
					while ( $linha = mysql_fetch_array($sql)) {
						$arquivo = $linha['arquivo'];
						echo '<img src="images/' .$arquivo .'">';				  
					}					
				}
				else { 
					echo "<h1>Nenhum registro encontrado</h1>";
				};
			*/
			
			
			?>
	</div>
	<script language='javascript'>
		StartClock('d/m/Y','H:i:s');

	</script>
	
</body>
</html>