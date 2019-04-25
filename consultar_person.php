<?php

require("config.inc.php");

if (!empty($_POST)){
		
		if (empty($_POST['identificacion'])){
			$response["success"] = 0;
			$response["message"] = "Por favor ingrese el numero de identificacion";
			
			die(json_encode($response));
		}
		    //si no hemos muerto (die), realizamos la consulta a la base de datos con el numero de cedula
			$query =  " SELECT p.idPersona, p.nombre, p.apellido, p.fecha_nac, p.telefono, p.email, p.direccion, t.nombre AS tipo 
						FROM persona p INNER JOIN tipo t ON p.tipo_id = t.idTipo AND identificacion = :identificacion;";
			               
			//acutalizamos la :cedula
			$query_params = array(
				':identificacion' => $_POST['identificacion']
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
		$response ["persona"] = $row;
		echo json_encode($response);
			
	}else{
		$response["success"] = 0;
		$response["message"] = "No se encontro ningun registro con ese numero de identificacion!";
		die(json_encode($response));
	}

        
} /*else {
?>
  <h1>Cosulta</h1> 
  <form action="consultar_user.php" method="POST"> 
     Cedula:<br /> 
      <input type="text" name="cedula" placeholder="cedula" /> 
      <br /><br /> 
      <input type="submit" value="Consultar" /> 
  </form> 
 <?php
}*/

?> 