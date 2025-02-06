<?php
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";

	global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

	function obtenerProductos($conexion, $id_producto = null, $nombre = null, $precio = null) {
		
		$condiciones = [];
		$parametros = [];
		$tipos = "";
		
		if (!is_null($id_producto) && is_numeric($id_producto)) {
			$condiciones[] = "id_producto = ?";
			$parametros[] = $id_producto;
			$tipos .= "i"; 
		}
		
		if (!is_null($nombre) && $nombre !== "") {
			$condiciones[] = "nombre LIKE ?";
			$parametros[] = "%$nombre%"; 
			$tipos .= "s"; 
		}
		

		if (!is_null($precio) && is_numeric($precio)) {
			$condiciones[] = "precio BETWEEN ? AND ?";
			$parametros[] = $precio - 0.01; 
			$parametros[] = $precio + 0.01; 
			$tipos .= "dd"; 
		}
	
		// Construcción de la consulta SQL
		$condicion_filtro = !empty($condiciones) ? " WHERE " . implode(" AND ", $condiciones) : "";
		$consulta = "SELECT * FROM productos " . $condicion_filtro;

	
		echo "Consulta: " . $consulta . "\n"; 
	
		$sentencia = $conexion->prepare($consulta);
		
		if (!$sentencia) {
			return ["http" => 500, "respuesta" => ["error" => "Error en la consulta: " . $conexion->error]];
		}
		
		if (!empty($parametros)) {
			$sentencia->bind_param($tipos, ...$parametros);
		}
		
		// Ejecutar la consulta
		$sentencia->execute();
		$resultado = $sentencia->get_result();
		
		if ($resultado->num_rows > 0) {
			$productos = [];
			while ($fila = $resultado->fetch_assoc()) {
				$productos[] = $fila;
			}
			$salida = ["http" => 200, "respuesta" => ["datos" => $productos]];
		} else {
			$salida = ["http" => 404, "respuesta" => ["error" => "No se encontraron productos"]];
		}
		
		$sentencia->close();
		return $salida;
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

	function modificarProducto($conexion,$id,$nombre,$precio,$descripcion,$stock,$membresia){
		
		if(is_integer($id) && 
		   trim($nombre)!="" && is_float($precio) && trim($descripcion)!="" && is_integer($stock) && is_bool($membresia)){
            if ($stock>0){
				$estado="disponible";
			}else{
				$estado="agotado";
			}
			$consulta="UPDATE productos 
			           SET nombre=?,precio=?,descripcion=?,stock=?,estado=?,membresia=?
		               WHERE id_producto=?";
			$sentencia=$conexion->prepare($consulta);
			$sentencia->bind_param("sisissii",$nombre,$precio,$descripcion,$stock,$estado,$membresia,$id);
			$sentencia->execute();
			$salida["http"]=200;
			$salida["respuesta"]=["mensaje"=>"Modificacion realizada"];

			$sentencia->close();
		}else{
			$salida["http"]=400;
			$salida["respuesta"]=["error"=>"Error en los datos"];
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