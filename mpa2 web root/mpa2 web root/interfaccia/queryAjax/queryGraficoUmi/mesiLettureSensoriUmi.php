<?php
if (!isset($_REQUEST['SelAnno']) || !isset($_REQUEST['SelSensore']))die("Parametri non impostati");
$anno = $_REQUEST['SelAnno'];
$idSensore = $_REQUEST['SelSensore'];
include "connessione.php";
$query = "
			select distinct month(dataOra) as mese from Letture
			where umidita is not null AND
			year(dataOra) like '".$anno."' AND
			idSensore = ".$idSensore.";
		 ";

$risultato = $mysqli->query($query);
if(!$risultato) echo "La query ha fallito";
else if(mysqli_num_rows($risultato) > 0)
{
	$mesi = array();
	while($riga = mysqli_fetch_assoc($risultato))
	{
		array_push($mesi,$riga['mese']);
	}
	echo json_encode($mesi);
}
else echo "Non sono presenti mesi di letture per l'anno ed il sensore scelto";
$mysqli->close();

?>