<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['idFactura'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el numero de identificacion";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = " SELECT * FROM factura WHERE idFactura = :idFactura";
			               
			//acutalizamos la :identificacion
			$query_params = array(':idFactura' => $_POST['idFactura']);
			
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

		if ($row) {
			//actualizamos los token
			$query_params = array(':idFactura' => $_POST['idFactura']);
		
			$query = "DELETE FROM factura WHERE idFactura = :idFactura ";
			
		try {
			$stmt   = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch (PDOException $ex) {
        // solo para testing
        //die("Failed to run query: " . $ex->getMessage());
        
			$response["success"] = 0;
			$response["message"] = "Error base de datos. Por favor vuelve a intentarlo";
			die(json_encode($response));
		}
    
		//si hemos llegado a este punto
		//es que el usuario se eliminado satisfactoriamente
			$response["success"] = 1;
			$response["message"] = "La factura se ha Eliminado correctamente";
			echo json_encode($response);
				
		}else{
			$response["success"] = 0;
			$response["message"] = "No se encontro ningun registro con ese id!";
			die(json_encode($response));
		}

        
}
?>