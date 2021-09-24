<?php
if (!isset($_REQUEST['SelAnno']) || !isset($_REQUEST['SelMese']) || !isset($_REQUEST['SelSensore']))die("Parametri non impostati");
$anno = $_REQUEST['SelAnno'];
$mese = $_REQUEST['SelMese'];
$idSensore = $_REQUEST['SelSensore'];
include "connessione.php";
$query = "
			select distinct day(dataOra) as giorno from Letture
			where umidita is not null AND
			year(dataOra) like '".$anno."' AND
			month(dataOra) like '".$mese."' AND
			idSensore = ".$idSensore.";
		 ";

$risultato = $mysqli->query($query);
if(!$risultato) echo "La query ha fallito";
else if(mysqli_num_rows($risultato) > 0)
{
	$giorni = array();
	while($riga = mysqli_fetch_assoc($risultato))
	{
		array_push($giorni,$riga['giorno']);
	}
	echo json_encode($giorni);
}
else echo "Non sono presenti giorni di letture per l'anno, il mese ed il sensore scelto";
$mysqli->close();

?>