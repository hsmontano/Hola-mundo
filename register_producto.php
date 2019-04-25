<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si los siguientes datos estan vacios
    //sino muere
    if (empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['valor_compra']) || empty($_POST['valor_venta']) || empty($_POST['cantidad']) || empty($_POST['estado_id']) || empty($_POST['categorias_id']) || empty($_POST['usuario_id'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor ingrese todos los datos";
        
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
        
        $response["success"] = 0;
        $response["message"] = "Lo siento el usuario ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO productos ( idProductos, nombre, descripcion, valor_compra, valor_venta, cantidad, estado_id, categorias_id, usuario_id) 
						    VALUES  ( null, :nombre, :descripcion, :valor_compra, :valor_venta, :cantidad, :estado, :categoria, :usuario)";
    
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
    //es que el usuario se agregado satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "El usuario se ha agregado correctamente";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} else {
?>
 <h1>Register Productos</h1> 
 <form action="registrar_producto.php" method="post"> 
     Nombre:<br /> 
     <input type="text" name="nombre" value="" /> 
     <br /><br /> 
	 Descripcion:<br /> 
     <input type="text" name="descripcion" value="" /> 
     <br /><br />
	 Valor compra:<br /> 
     <input type="text" name="valor_compra" value="" /> 
     <br /><br />
	 Valor venta:<br /> 
     <input type="text" name="valor_venta" value="" /> 
     <br /><br />
	 Imagen del producto:<br /> 
     <input type="text" name="imagen_producto" value="" /> 
     <br /><br />	 
     Cantidad:<br /> 
     <input type="text" name="cantidad" value="" /> 
     <br /><br /> 
	 Estado del producto:<br /> 
     <input type="text" name="estado_id" value="" /> 
     <br /><br />
	 Categoria:<br /> 
     <input type="text" name="categorias_id" value="" /> 
     <br /><br />	 
	 Usuario:<br /> 
     <input type="text" name="usuario_id" value="" /> 
     <br /><br />
     <input type="submit" value="Registrar producto" /> 
 </form>
 <?php
}

?>