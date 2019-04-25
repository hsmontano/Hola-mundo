<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['nombre'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese el id";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT * FROM productos WHERE nombre = :nombre";
    
    //acutalizamos el :email
    $query_params = array(
        ':nombre' => $_POST['nombre']
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
        
    $query =   "UPDATE productos 
				SET descripcion = :descripcion, valor_compra = :valor_compra, valor_venta = :valor_venta, cantidad = :cantidad, estado_id = :estado, categorias_id = :categoria, usuario_id = :usuario 
				WHERE nombre = :nombre";
    
    //actualizamos los token
    $query_params = array(
        ':nombre' => $_POST['nombre'],
		':descripcion' => $_POST['descripcion'],
		':valor_compra' => $_POST['valor_compra'],
		':valor_venta' => $_POST['valor_venta'],
		':cantidad' => $_POST['cantidad'],
		':estado' => $_POST['estado_id'],
        ':categoria' => $_POST['categorias_id'],
		':usuario' => $_POST['usuario_id']);
    
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
    $response["message"] = "El producto se ha modificado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");

    }else{
	$response["success"] = 0;
	$response["message"] = "No hay productos registrados con ese nombre";
	echo json_encode($response);
    }
}
?>