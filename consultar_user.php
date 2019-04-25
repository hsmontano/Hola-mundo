<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['cedula'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el numero de cedula";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query = " SELECT u.idUsuario, u.cedula, u.nombre, u.apellido, u.email, u.telefono, u.clave, e.nombre as estado, r.nombre_rol 
						FROM usuario u INNER JOIN estado e ON u.estado = e.idEstado INNER JOIN rol r ON u.rol_id = r.idRol AND cedula = :cedula";
			               
			//acutalizamos la :cedula
			$query_params = array(
				':cedula' => $_POST['cedula']
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
		$response ["usuario"] = $row;
		echo json_encode($response);
			
	}else{
		$response["success"] = 0;
		$response["message"] = "No se encontro ningun registro con ese numero de cedula!";
		die(json_encode($response));
	}

        
} else {
?>
  <h1>Cosulta</h1> 
  <form action="consultar_user.php" method="POST"> 
     Cedula:<br /> 
      <input type="text" name="cedula" placeholder="cedula" /> 
      <br /><br /> 
      <input type="submit" value="Consultar" /> 
  </form> 
 <?php
}

?> 