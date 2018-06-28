	<?php
	//Pagina que retorna as paradas de onibus no banco de dados em formato json para uso do javascript na pagina principal.
	$mysqli = new mysqli("localhost", "sptrans", "1234", "sptrans");
	$query = "SELECT descricao,latitude,longitude FROM paradas ORDER BY latitude";

	$results = array();

	if ($result = $mysqli->query($query)) {

	    while ($row = $result->fetch_assoc()) {
	        $results[] = $row;
	    }

	    $result->free();
	}

	$mysqli->close();

	echo json_encode($results);
?>