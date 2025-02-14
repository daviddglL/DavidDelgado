<?php
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";

	global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

	function obtenerProductos($conn, $search = '', $page = 1, $limit = 4) {
		$offset = ($page - 1) * $limit;
		$search = "%$search%";
		
		$stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ? LIMIT ?, ?");
		$stmt->bind_param("ssii", $search, $search, $offset, $limit);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$productos = [];
		while ($row = $result->fetch_assoc()) {
			$productos[] = $row;
		}
		
		$result_total = $conn->query("SELECT FOUND_ROWS() as total");
		$total = $result_total->fetch_assoc()['total'];
		
		$paginacion = [
			'actual' => $page,
			'total' => ceil($total / $limit),
			'limite' => $limit
		];
		
		return [
			'datos' => $productos,
			'paginacion' => $paginacion
		];
	}

	function obtenerProductosPag($conexion, $pagina, $limite) {
		$offset = ($pagina - 1) * $limite;
		$consulta = "SELECT id_producto, nombre, precio, descripcion, stock, estado, imagen, membresia FROM productos 
					 LIMIT ? OFFSET ?";
	
		$sentencia = $conexion->prepare($consulta);
		$sentencia->bind_param("ii", $limite, $offset);
		$sentencia->execute();
		$resultado = $sentencia->get_result();
	
		if ($resultado->num_rows > 0) {
			$datos = [];
			while ($fila = $resultado->fetch_assoc()) {
				$datos[] = $fila;
			}
	
			$consulta = "SELECT count(*) FROM productos";
			$resultado = $conexion->query($consulta);
			$fila = $resultado->fetch_row();
			$total = $fila[0];
	
			$salida["http"] = 200;
			$salida["respuesta"] = [
				"datos" => $datos,
				"paginacion" => [
					"actual" => $pagina,
					"limite" => $limite,
					"total" => $total,
					"paginas" => ceil($total / $limite)
				]
			];
			$sentencia->close();
		} else {
			$salida["http"] = 404;
			$salida["respuesta"] = ["error" => "No hay resultados"];
		}
	
		return $salida;
	}
	

	function crearProducto($conexion,$nombre,$precio,$descripcion,$stock,$imagen,$membresia){
		
		if(trim($nombre)!="" && is_float($precio) && trim($descripcion)!="" && is_integer($stock) && trim($imagen)!="" && is_bool($membresia)){
			if ($stock>0){
				$estado="disponible";
			}else{
				$estado="agotado";
			}
            $consulta="INSERT INTO productos (nombre, precio, descripcion, stock, estado, imagen, membresia) 
			           VALUES (?,?,?,?,?,?,?)";
			$sentencia=$conexion->prepare($consulta);
			$sentencia->bind_param("sisissi",$nombre,$precio,$descripcion,$stock,$estado,$imagen,$membresia);
			$sentencia->execute();
			$salida["http"]=200;
			$salida["respuesta"]=["id"=>$sentencia->insert_id];

			$sentencia->close();
		}else{
			$salida["http"]=400;
			$salida["respuesta"]=["error"=>"Error en los datos"];
		}

		return $salida;
	}

	function modificarProducto($conexion, $id, $nombre, $precio, $descripcion, $stock, $membresia) {
    // Convertir los datos a los tipos correctos
    $id = (int) $id;
    $precio = (float) $precio;
    $stock = (int) $stock;
    $membresia = (bool) $membresia;

    // Validar los datos
    if (is_int($id) && 
        trim($nombre) !== "" && is_float($precio) && trim($descripcion) !== "" && is_int($stock) && is_bool($membresia)) {
        
        $estado = ($stock > 0) ? "disponible" : "agotado";
        
        $consulta = "UPDATE productos 
                     SET nombre = ?, precio = ?, descripcion = ?, stock = ?, estado = ?, membresia = ?
                     WHERE id_producto = ?";
        $sentencia = $conexion->prepare($consulta);
        
        if ($sentencia === false) {
            return ["http" => 500, "respuesta" => ["error" => "Error en la preparación de la consulta: " . $conexion->error]];
        }
        
        $sentencia->bind_param("sdsisii", $nombre, $precio, $descripcion, $stock, $estado, $membresia, $id);
        $sentencia->execute();
        
        if ($sentencia->affected_rows > 0) {
            $salida = ["http" => 200, "respuesta" => ["mensaje" => "Modificación realizada"]];
        } else {
            $salida = ["http" => 404, "respuesta" => ["error" => "Producto no encontrado o datos no modificados"]];
        }
        
        $sentencia->close();
    } else {
        $salida = ["http" => 400, "respuesta" => ["error" => "Error en los datos"]];
    }

    return $salida;
}

	function borrarProducto($conexion,$id){

		if(is_numeric($id)){
			$consulta="DELETE FROM productos WHERE id_producto=?";
			$sentencia=$conexion->prepare($consulta);
			$sentencia->bind_param("i",$id);
			$sentencia->execute();
			$salida["http"]=200;
			$salida["respuesta"]=["mensaje"=>"Borrado realizado"];
			$sentencia->close();

		}else{
			$salida["http"]=400;
			$salida["respuesta"]=["error"=>"Error en los datos"];
		}

		return $salida;
	}







?>