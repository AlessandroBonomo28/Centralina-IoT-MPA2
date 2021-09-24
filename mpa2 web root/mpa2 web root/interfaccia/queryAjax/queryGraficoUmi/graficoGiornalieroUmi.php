<?php
if (!isset($_REQUEST['SelSensore']) || !isset($_REQUEST['SelAnno']) || !isset($_REQUEST['SelMese']) || 
	!isset($_REQUEST['SelGiorno'])) die("Parametri non impostati");
$idSensore = $_REQUEST['SelSensore'];
$anno = $_REQUEST['SelAnno'];
$mese = $_REQUEST['SelMese'];
$giorno = $_REQUEST['SelGiorno'];
include "connessione.php";
$query = "
			 select  avg(umidita) as umidita, hour(dataOra) as ora from Letture 
			 where year(dataOra) like '".$anno."' and
			 month(dataOra) like '".$mese."' and
			 day(dataOra) like '".$giorno."' and
			 umidita is not null and
			 idSensore =".$idSensore." group by ora;
		 ";
$risultato = $mysqli->query($query);
if(!$risultato) echo "La query ha fallito";
else if(mysqli_num_rows($risultato) > 0)
{
	$datiGrafico = array();
	while($riga = mysqli_fetch_assoc($risultato))
	{
		$istanza = array( "ora" => $riga['ora'] ,"umidita" => $riga['umidita'] );
		array_push($datiGrafico,$istanza);
	}
	echo json_encode($datiGrafico);
}
else echo "Nessun dato trovato";
$mysqli->close();

?>