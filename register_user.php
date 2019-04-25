<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['email']) || empty($_POST['telefono']) || empty($_POST['clave']) || empty($_POST['estado']) || empty($_POST['rol_id'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese todos los datos";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT 1 FROM usuario WHERE email = :email";
    
    //acutalizamos el :email
    $query_params = array(
        ':email' => $_POST['email']
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
        $response["message"] = "Lo siento el usuario ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO usuario ( idUsuario, cedula, nombre, 
                                    apellido, telefono,email, clave,
                                    estado, rol_id) 
						  VALUES  ( null,:cedula, :nombre, 
									:apellido, :telefono, :email, 
									:clave, :estado, :rol) ";
    
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
        $response["message"] = "Error base de datos. Porfavor vuelve a intentarlo";
        die(json_encode($response));
    }
    
    //si hemos llegado a este punto
    //es que el usuario se agregado satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "El usuario se ha agregado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
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