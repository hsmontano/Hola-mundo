<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['cedula'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese el numero de cedula";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT 1 FROM usuario WHERE cedula = :cedula";
    
    //acutalizamos el :email
    $query_params = array(
        ':cedula' => $_POST['cedula']
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
        
    $query = "UPDATE usuario SET nombre = :nombre, apellido = :apellido, telefono = :telefono, email = :email, clave = :clave, estado = :estado, rol_id = :rol WHERE cedula = :cedula";
    
    //actualizamos los token
    $query_params = array(
        ':cedula' => $_POST['cedula'],
		':nombre' => $_POST['nombre'],
		':apellido' => $_POST['apellido'],
		':telefono' => $_POST['telefono'],
		':email' => $_POST['email'],
		':clave' => $_POST['clave'],
		':estado' => $_POST['estado'],
        ':rol' => $_POST['rol_id']);
    
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
    $response["message"] = "El usuario se ha modificado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");

    }
	$response["success"] = 0;
	$response["message"] = "No hay registros con ese numero de cedula";
    
} else {
?>
 <h1>Register</h1> 
 <form action="register.php" method="post"> 
     Nombre de usuario:<br /> 
     <input type="text" name="cedula" value="" /> 
     <br /><br /> 
	 Nombre:<br /> 
     <input type="text" name="nombre" value="" /> 
     <br /><br />
	 Apellido:<br /> 
     <input type="text" name="apellido" value="" /> 
     <br /><br />
	 Telefono:<br /> 
     <input type="text" name="telefono" value="" /> 
     <br /><br />
	 Email:<br /> 
     <input type="text" name="email" value="" /> 
     <br /><br />	 
     Clave:<br /> 
     <input type="text" name="clave" value="" /> 
     <br /><br /> 
	 Estado:<br /> 
     <input type="text" name="estado" value="" /> 
     <br /><br />
	 Rol:<br /> 
     <input type="text" name="rol_id" placeholder = "empleado, admin" value="" /> 
     <br /><br />	 
     <input type="submit" value="Register New User" /> 
 </form>
 <?php
}

?>