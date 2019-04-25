<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese las fechas";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = " SELECT f.idFactura, f.total, f.pago, f.descuento, u.nombre AS empleado, u.cedula, p.nombre AS persona, p.apellido, p.identificacion, t.nombre AS operacion 
						FROM factura f INNER JOIN usuario u ON f.usuario_id = u.idUsuario 
						INNER JOIN persona p ON f.persona_id = p.idPersona 
						INNER JOIN tipo_operacion t ON f.tipo_operacion_id = t.idTipo_operacion AND f.fecha_creacion BETWEEN :fecha_inicio AND :fecha_fin";
			               
			//acutalizamos la :cedula
			$query_params = array(
				':fecha_inicio' => $_POST['fecha_inicio'],
				':fecha_fin' => $_POST['fecha_fin']
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
		$response ["reporte"] = $row;
		echo json_encode($response);
			
	}else{
		$response["success"] = 0;
		$response["message"] = "No se encontro ningun registro con ese rango de fecha!";
		die(json_encode($response));
	}

        
}
?>