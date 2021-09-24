<html>
<head>
	<title>Situazione attuale</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="img/Smart_Home_icon.png" />
    <link rel="stylesheet" href="../bootStrap/materialize.min.css">
    <link href="../bootStrap/icon.css" rel="stylesheet">
    <link href="../bootStrap/style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../bootStrap/bootstrap.min.css">
	<script src="../bootStrap/jquery-3.3.1.min.js"></script>
	<script src="../bootStrap/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			// Funzione che ricarica la pagina ogni 10 sec
			setTimeout(function(){
			   window.location.reload(1);
			}, 10000);
		});
	</script>
</head>
<body>
<ul class="sidenav sidenav-fixed">
    <li class="sidenav-select active">
	<a href=""><i class="material-icons icons-white">home</i>Situazione</a></li>
    <li class="sidenav-select"><a href="graficoTemp.php"><i class="material-icons icons-white">ac_unit</i>Temperatura</a></li>
    <li class="sidenav-select"><a href="graficoUmi.php"><i class="material-icons icons-white">invert_colors</i>Umidità</a></li>
    <li class="sidenav-select"><a href="graficoRum.php"><i class="material-icons icons-white">volume_up</i>Rumore</a></li> 
</ul>
<nav class="navbar-wrapper admin-root blue lighten-2">
    <a href="" class="brand-logo center">MPA 2</a>
</nav>

<div class="container">
	<h1>Situazione attuale</h1>
	<div class="row">
		<div class="col-8">
		  <br>
		  <img src="img/piantine/secondoPianoTriennio.PNG" 
			class="img-fluid" alt="Responsive image">
		</div>
		<div class="col">
		<br>
		<h3>Ultime letture dei sensori:</h3>
		  <?php
			include "../connessione.php";
			$query = "
					 select Letture.id as id,Centraline.nome as centralina,
					 Sensori.nome as sensore, dataOra, temperatura, umidita, rumore
					 from Centraline inner join Sensori on Centraline.id = Sensori.idCentralina
					 inner join Letture on Sensori.id = Letture.idSensore
					 where Letture.id in
							(
								 select max(id) as id from Letture 
								 where umidita is null AND rumore is null
								 and hour(dataOra) like hour(current_timestamp())
								 and date(dataOra) like current_date()
								 group by idSensore 
								 UNION
								 select max(id) as id from Letture 
								 where temperatura is null AND rumore is null
								 and hour(dataOra) like hour(current_timestamp())
								 and date(dataOra) like current_date()
								 group by idSensore 
								 UNION
								 select max(id) as id from Letture 
								 where umidita is null AND temperatura is null
								 and hour(dataOra) like hour(current_timestamp())
								 and date(dataOra) like current_date()
								 group by idSensore 
								 UNION
								 select max(id) as id from Letture
								 group by idSensore 
								 UNION
								 select max(id) as id from Letture
								 group by idSensore 
								 UNION
								 select max(id) as id from Letture
								 group by idSensore 
							);
					 ";
			$risultato = $mysqli->query($query);
			if (mysqli_num_rows($risultato) > 0)
			{
				echo "
						<table class='responsive-table highlight'>
							<thead>
							  <tr>
								  <th>Centralina (Aula)</th>
								  <th>Sensore</th>
								  <th>Data-ora lettura</th>
								  <th>Temperatura</th>
								  <th>Umidità</th>
								  <th>Rumore</th>
							  </tr>
							</thead>
							<tbody>
					 ";
				while($riga = mysqli_fetch_assoc($risultato))
				{
					$rangeMinTemp = 18; 
					$rangeMaxTemp = 23;
					
					$rangeMinUmi = 30; 
					$rangeMaxUmi = 40;
					
					$rangeMinRum = 55; 
					$rangeMaxRum = 75;
					
					$coloreSfondoTemp = ($rangeMinTemp <= $riga['temperatura'] &&
										 $riga['temperatura'] <= $rangeMaxTemp) ? "LightGreen" : "tomato";
					$coloreSfondoUmi = ($rangeMinUmi <= $riga['umidita'] &&
										 $riga['umidita'] <= $rangeMaxUmi) ? "LightGreen" : "tomato";
					$coloreSfondoRum = ($rangeMinRum <= $riga['rumore'] &&
										 $riga['rumore'] <= $rangeMaxRum) ? "LightGreen" : "tomato";
					if($riga['temperatura'] == "") $coloreSfondoTemp = "white";
					if($riga['umidita'] == "") $coloreSfondoUmi = "white";
					if($riga['rumore'] == "") $coloreSfondoRum = "white";
					//foreach($riga as $k=>$val)echo $k.":".$val."<br>";
					echo "
							  <tr>
								<td>".$riga['centralina']."</td>
								<td>".$riga['sensore']."</td>
								<td>".$riga['dataOra']."</td>
								<td bgcolor='".$coloreSfondoTemp."'>".$riga['temperatura']."</td>
								<td bgcolor='".$coloreSfondoUmi."'>".$riga['umidita']."</td>
								<td bgcolor='".$coloreSfondoRum."'>".$riga['rumore']."</td>
							  </tr>
						 "; 
				}
				echo "
							</tbody>
					    </table>
					 ";
			}
			else echo "<h3>Nessuna centralina impostata</h3>";
			$mysqli->close();
		  ?>
		</div>
	</div>
</div>
</body>
</html>