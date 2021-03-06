<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['idFactura'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese el id";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT * FROM factura WHERE idFactura = :idFactura";
    
    //acutalizamos el :email
    $query_params = array(
        ':idFactura' => $_POST['idFactura']
    );
    
    //ejecutamos la consulta
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
    
    //buscamos la información
    //como sabemos que el usuario ya existe lo matamos
    $row = $stmt->fetch();
    if ($row) {
        // Solo para testing
        //die("This username is already in use");
        
    $query =   "UPDATE factura 
				SET total = :total, pago = :pago, descuento = :descuento, fecha_creacion = :fecha_creacion, usuario_id = :usuario, caja_id = :caja, persona_id = :persona, tipo_operacion_id = :operacion 
				WHERE idFactura = :idFactura";
    
    //actualizamos los token
    $query_params = array(
        ':idFactura' => $_POST['idFactura'],
		':total' => $_POST['total'],
		':pago' => $_POST['pago'],
		':descuento' => $_POST['descuento'],
		':fecha_creacion' => $_POST['fecha_creacion'],
		':usuario' => $_POST['usuario_id'],
		':caja' => $_POST['caja_id'],
        ':persona' => $_POST['persona_id'],
		':operacion' => $_POST['tipo_operacion_id']);
    
    //ejecutamos la query y creamos el usuario
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
    //es que el usuario se modifico satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "La factura se ha modificado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");

    }else{
	$response["success"] = 0;
	$response["message"] = "No hay registros con ese id";
    echo json_encode($response);
	}
}
?>