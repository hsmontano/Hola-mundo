<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['nombre'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el nombre de la categoria";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = " SELECT * FROM categorias WHERE nombre = :nombre";
			               
			//acutalizamos la :identificacion
			$query_params = array(':nombre' => $_POST['nombre']);
			
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
			$query_params = array(':nombre' => $_POST['nombre']);
		
			$query = "DELETE FROM categorias WHERE nombre = :nombre ";
			
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
			$response["message"] = "La categoria se ha Eliminado correctamente";
			echo json_encode($response);
				
		}else{
			$response["success"] = 0;
			$response["message"] = "No se encontro ningun registro con ese id!";
			die(json_encode($response));
		}

        
}
?>