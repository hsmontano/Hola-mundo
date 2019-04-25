<?php

//carga y se conecta a la base de datos
require("config.inc.php");

if (!empty($_POST)) {
    //obtenemos los usuarios respecto a la configuracion que llega por parametro
    $query = " 
            SELECT u.email, u.clave, r.nombre_rol AS rol
            FROM usuario u INNER JOIN rol r ON u.rol_id = r.idRol AND email = :email AND u.estado = 1
        ";
    
    $query_params = array(
        ':email' => $_POST['email']
    );
    
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        //para testear pueden utilizar lo de abajo
        //die("la consulta murio " . $ex->getMessage());
        
        $response["success"] = 0;
        $response["message"] = "Problema con la base de datos, vuelve a intetarlo";
        die(json_encode($response));
        
    }
    
    //la variable a continuación nos permitirará determinar 
    //si es o no la información correcta
    //la inicializamos en "false"
    $validated_info = false;
    
    //vamos a buscar a todas las filas
    $row = $stmt->fetch();
	$response = array();
    if ($row) {
        //si el password viene encryptado debemos desencryptarlo acá
        //++ DESCRYPTAR ++

        //encaso que no lo este, solo comparamos como acontinuación
        if ($_POST['clave'] === $row['clave'] && $_POST['email'] === $row['email']) {
            $response["success"] = 1;
            $response["message"] = "Login correcto!";
		    $response["datos"] = $row;
            die(json_encode($response));
        }else{
            $response["success"] = 0;
            $response["message"] = "verifique la contraseña e intente de nuevo";
            die(json_encode($response));
        }
    }else{
            $response["success"] = 0;
            $response["message"] = "No se encuentra registrado en la base de datos";
            die(json_encode($response));
    }
    
} 
?> 