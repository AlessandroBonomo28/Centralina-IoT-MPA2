<?php
if (!isset($_REQUEST['SelCentralina'])) die("Parametri non impostati");
$idCentralina = $_REQUEST['SelCentralina'];
include "connessione.php";
$query = "
			select Sensori.id as id,Sensori.nome as nome from Sensori 
			inner join Letture on  Sensori.id = Letture.idSensore
            where idCentralina = ".$idCentralina." and
			temperatura is not null group by id,nome;
		 ";

$risultato = $mysqli->query($query);
if(!$risultato) echo "La query ha fallito";
else if(mysqli_num_rows($risultato) > 0)
{
	$sensori = array();
	while($riga = mysqli_fetch_assoc($risultato))
	{
		$istanzaSensore = array( "id" => $riga['id'] ,"nome" => $riga['nome'] );
		array_push($sensori,$istanzaSensore);
	}
	echo json_encode($sensori);
}
else echo "Nessun sensore trovato";
$mysqli->close();

?>