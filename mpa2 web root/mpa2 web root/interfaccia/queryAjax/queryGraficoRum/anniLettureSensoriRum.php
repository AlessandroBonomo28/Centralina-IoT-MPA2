<?php
if (!isset($_REQUEST['SelSensore']))die("Parametri non impostati");
$idSensore = $_REQUEST['SelSensore'];

include "connessione.php";
$query = "
			select distinct year(dataOra) as anno from Letture
			where rumore is not null AND
			idSensore = ".$idSensore.";
		 ";

$risultato = $mysqli->query($query);
if(!$risultato) echo "La query ha fallito";
else if(mysqli_num_rows($risultato) > 0)
{
	$anni = array();
	while($riga = mysqli_fetch_assoc($risultato))
	{
		array_push($anni,$riga['anno']);
	}
	echo json_encode($anni);
}
else echo "Non sono presenti anni di letture per il sensore scelto";
$mysqli->close();

?>