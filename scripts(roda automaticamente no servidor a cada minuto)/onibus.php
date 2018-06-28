<?php
	//Script php que utiliza curl para buscar os dados das posicoes dos onibus no site da sptrans e salvar na nossa base.
	define("COOKIE_FILE", "cookie.txt");
	// set post fields
	$post = [
	    'token' => 'abf0908628126d039fc9fda4cc286b19dd2e9b8c21d9308e7d888a743df67f9a',
	];
	//'http://api.olhovivo.sptrans.com.br/v2.1/Login/Autenticar?token=abf0908628126d039fc9fda4cc286b19dd2e9b8c21d9308e7d888a743df67f9a'
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, 'http://api.olhovivo.sptrans.com.br/v2.1/Login/Autenticar?token=aa97cb3911173dc3a3128867fc1dd53344f0a31ea60524cea3e52f9c7c36d8a8');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 3000);
	curl_setopt( $ch, CURLOPT_COOKIEJAR,  "Teste.ck" );
	curl_setopt( $ch, CURLOPT_COOKIEFILE,  "Teste.ck" );
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: 0'));
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

	// execute!
	$response = curl_exec($ch);

	// close the connection, release resources used
	//curl_close($ch);

	// do anything you want with your response
	if($response == true){
		curl_setopt($ch, CURLOPT_URL,"http://api.olhovivo.sptrans.com.br/v2.1/Posicao");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, false);
		// execute!
		$response2 = curl_exec($ch);
		$mysqli = new mysqli('localhost','sptrans','1234','sptrans');
		$json = json_decode($response2);
		if(count($json->l)){
			$mysqli->query("DELETE FROM Onibus WHERE id > 0");
			$mysqli->query("ALTER TABLE Onibus AUTO_INCREMENT = 0;");
		}
		for($i = 0;$i<count($json->l);$i++){
			$letreiro = ($json->l[$i]->sl == 1 ? $json->l[$i]->lt0 : $json->l[$i]->lt1);
			$codigo = $json->l[$i]->c;
			for($j = 0;$j < count($json->l[$i]->vs);$j++){
				$latitude = $json->l[$i]->vs[$j]->py;
				$longitude = $json->l[$i]->vs[$j]->px;
				$sql = "INSERT INTO Onibus(codigo,letreiro,latitude,longitude) VALUES('$codigo','$letreiro','$latitude','$longitude')";
				$result = $mysqli->query($sql);
				//echo $result;
			}
		}

		// close the connection, release resources used
		curl_close($ch);
		//echo $response2;
	}
?>