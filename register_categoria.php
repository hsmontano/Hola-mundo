<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['fecha_creacion'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese todos los datos";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT * FROM categorias WHERE nombre = :nombre";
    
    //acutalizamos el :idFactura
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
        
        $response["success"] = 0;
        $response["message"] = "Lo siento la categoria ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO categorias (idCategorias, nombre, descripcion, fecha_creacion) 
                   VALUES (NULL, :nombre, :descripcion, :fecha_creacion)";
    
    //actualizamos los token
    $query_params = array(
        ':nombre' => $_POST['nombre'],
		':descripcion' => $_POST['descripcion'],
		':fecha_creacion' => $_POST['fecha_creacion']);
    
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
    //es que el usuario se agregado satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "La categoria se ha agregado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} 

?>