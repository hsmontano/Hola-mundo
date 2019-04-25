<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['nombre'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el nombre del producto";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = "  SELECT p.idProductos, p.nombre, p.descripcion, p.valor_compra, p.valor_venta, p.cantidad, p.cantidad, u.nombre AS usuario, e.nombre AS estado, c.nombre AS categoria 
						FROM productos p INNER JOIN usuario u ON p.usuario_id = u.idUsuario 
										 INNER JOIN estado e ON e.idEstado = p.estado_id 
										 INNER JOIN categorias c ON p.categorias_id = c.idCategorias AND p.nombre = :nombre";
			               
			//acutalizamos la :cedula
			$query_params = array(
				':nombre' => $_POST['nombre']
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
		$response ["producto"] = $row;
		echo json_encode($response);
			
	}else{
		$response["success"] = 0;
		$response["message"] = "No se encontro ningun registro con ese nombre!";
		die(json_encode($response));
	}

        
}
?>