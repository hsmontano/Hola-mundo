<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si el ussuario o la contraseña esta vacia
    //sino muere
    if (empty($_POST['identificacion']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['fecha_nac']) || empty($_POST['telefono']) || empty($_POST['email']) || empty($_POST['direccion']) || empty($_POST['tipo_id'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese todos los datos";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT 1 FROM persona WHERE identificacion = :identificacion";
    
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
        
        $response["success"] = 0;
        $response["message"] = "Lo siento el usuario ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO persona ( idPersona, identificacion, nombre, 
                                    apellido, fecha_nac, telefono,email, 
                                    direccion, tipo_id) 
						  VALUES  ( null,:identificacion, :nombre, 
									:apellido, :fecha_nac, :telefono, 
									:email, :direccion, :tipo) ";
    
    //actualizamos los token
    $query_params = array(
        ':identificacion' => $_POST['identificacion'],
		':nombre' => $_POST['nombre'],
		':apellido' => $_POST['apellido'],
		':fecha_nac' => $_POST['fecha_nac'],
		':telefono' => $_POST['telefono'],
		':email' => $_POST['email'],
		':direccion' => $_POST['direccion'],
        ':tipo' => $_POST['tipo_id']
    );
    
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
    $response["message"] = "La persona se ha agregado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} else {
?>
 <h1>Register</h1> 
 <form action="registrar_persona.php" method="post"> 
     Nombre de usuario:<br /> 
     <input type="text" name="identificacion" placeholder="Identificacion" value="" /> 
     <br /><br /> 
	 Nombre:<br /> 
     <input type="text" name="nombre" placeholder="Nombre" value="" /> 
     <br /><br />
	 Apellido:<br /> 
     <input type="text" name="apellido" placeholder="Apellido" value="" /> 
     <br /><br />
	 Fecha de nacimiento:<br /> 
     <input type="date" name="fecha_nac" placeholder="fecha de nacimiento" value="" /> 
     <br /><br /> 
	 Telefono:<br /> 
     <input type="text" name="telefono" placeholder="Telefono" value="" /> 
     <br /><br />
	 Email:<br /> 
     <input type="text" name="email" placeholder="Email" value="" /> 
     <br /><br />	 
	 Direccion:<br /> 
     <input type="text" name="direccion" placeholder="Direccion" value="" /> 
     <br /><br />
	 Tipo:<br /> 
     <input type="text" name="tipo_id" placeholder = "proveedor, cliente" value="" /> 
     <br /><br />	 
     <input type="submit" value="Registrar persona" /> 
 </form>
 <?php
}

?>