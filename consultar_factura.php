<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['idFactura'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el id de factura";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = "  SELECT f.idFactura, f.total, f.pago, f.descuento, f.fecha_creacion, p.nombre AS persona, c.fecha_creacion, u.nombre AS usuario, t.nombre AS operacion 
						FROM factura f INNER JOIN persona p ON f.persona_id = p.idPersona 
						INNER JOIN usuario u ON f.usuario_id = u.idUsuario 
						INNER JOIN tipo_operacion t ON f.tipo_operacion_id = t.idTipo_operacion 
						INNER JOIN caja c ON f.caja_id = c.idCaja AND idFactura = :idFactura";
			               
			//acutalizamos la :cedula
			$query_params = array(
				':idFactura' => $_POST['idFactura']
			);
			
		try {
        // estas son las dos consultas que se van a hacer en la bse de datos
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
		}
		catch (PDOException $ex) {
			// solo para testing
			//die("Failed to run query: " . $ex->getMessage());
			
			$response["success"] = 0;
			$response["message"] = "Error en la base de datos por favor intente de nuevo!";
			die(json_encode($response));
		}
		$row = $stmt->fetchALL(PDO::FETCH_ASSOC);
		
		$response = array();
		if ($row) {
			
		/*$response["success"] = 1;
		$response["message"] = "Consulta exitosa!";
		die(json_encode($response));*/
		$response ["success"] = 1;
		$response ["factura"] = $row;
		echo json_encode($response);
			
	}else{
		$response["success"] = 0;
		$response["message"] = "No se encontro ningun registro con ese id!";
		die(json_encode($response));
	}

        
}
?>