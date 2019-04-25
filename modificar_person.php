<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['identificacion'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese el numero de identificacion";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT * FROM persona WHERE identificacion = :identificacion";
    
    //acutalizamos el :email
    $query_params = array(
        ':identificacion' => $_POST['identificacion']
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
        
    $query =   "UPDATE persona 
				SET nombre = :nombre, apellido = :apellido, fecha_nac = :fecha_nac, telefono = :telefono, email = :email, direccion = :direccion, tipo_id = :tipo 
				WHERE identificacion = :identificacion";
    
    //actualizamos los token
    $query_params = array(
        ':identificacion' => $_POST['identificacion'],
		':nombre' => $_POST['nombre'],
		':apellido' => $_POST['apellido'],
		':fecha_nac' => $_POST['fecha_nac'],
		':telefono' => $_POST['telefono'],
		':email' => $_POST['email'],
		':direccion' => $_POST['direccion'],
        ':tipo' => $_POST['tipo_id']);
    
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
    
} /*else {
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
}*/

?>