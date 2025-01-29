<?php
    require_once "../../connection/config.php";
    require_once "../../connection/funciones.php";

	global $nombre_db, $nombre_host, $nombre_usuario, $password_db;  

    $conexion = conectar($nombre_host, $nombre_usuario, $password_db, $nombre_db);

	function obtenerAsignaturas($conexion,$id_producto){
		global $productos;

		$consulta="SELECT * FROM $productos WHERE id=?";
		$sentencia=$conexion->prepare($consulta);
		$sentencia->bind_param("i",$id_producto);
		$sentencia->execute();
		$resultado=$sentencia->get_result();
		if($resultado->num_rows==1){
			$producto=$resultado->fetch_assoc();
			$salida["http"]=200;
			$salida["respuesta"]=["datos"=>$producto];
		}else{
			$salida["http"]=404;
			$salida["respuesta"]=["error"=>"No se encuentra el producto"];
		}

		$sentencia->close();

		return $salida;
	}

	function obtenerAsignaturasPag($conexion,$pagina,$limite){
		global $productos;

		$offset=($pagina-1)*$limite;
		$consulta="SELECT * FROM $productos 
		           LIMIT ? OFFSET ?";

		$sentencia=$conexion->prepare($consulta);
		$sentencia->bind_param("ii",$limite,$offset);
		$sentencia->execute();
		$resultado=$sentencia->get_result();

		if($resultado->num_rows>0){
			$datos=[];
			while($fila=$resultado->fetch_assoc()){
				$datos[]=[
					"id"=>$fila["id"],
					"nombre"=>$fila["nombre"],
					"precio"=>$fila["precio"],
                    "descripcion"=>$fila["descripcion"],
                    "stock"=>$fila["stock"],
                    "estado"=>$fila["estado"],
                    "imagen"=>$fila["imagen"]
				];
			}

			$consulta="SELECT count(*) FROM $productos";
			$resultado=$conexion->query($consulta);
			$fila=$resultado->fetch_row();
			$total=$fila[0];

			$salida["http"]=200;
			$salida["respuesta"]=["datos"=>$datos,
								  "paginacion"=>[
									"actual"=>$pagina,
									"limite"=>$limite,
									"total"=>$total,
									"paginas"=>ceil($total/$limite)
									// "anterior"=>null
									// "siguiente"=>"http://...api.php?page=3&limit=$limit"

								  ]	
								];
			$sentencia->close();
		}else{
			$salida["http"]=404;
			$salida["respuesta"]=["error"=>"No hay resultados"];
		}
		
		return $salida;
	}

	function crearAsignatura($conexion,$nombre,$precio,$descripcion,$stock,$imagen,$membresia){
		
		if(trim($nombre)!="" && is_float($precio) && trim($descripcion)!="" && is_integer($stock) && trim($imagen)!="" && is_bool($membresia)){
            $consulta="INSERT INTO productos (nombre, precio, descripcion, stock, imagen, membresia) 
			           VALUES (?,?,?,?,?,?)";
			$sentencia=$conexion->prepare($consulta);
			$sentencia->bind_param("sisisi",$nombre,$precio,$descripcion,$stock,$imagen,$membresia);
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

	function modificarAsignatura($conexion,$id,$nombre,$precio,$descripcion,$stock,$imagen,$membresia){
		
		if(is_integer($id) && 
		   trim($nombre)!="" && is_float($precio) && trim($descripcion)!="" && is_integer($stock) && trim($imagen)!="" && is_bool($membresia)){
            
			$consulta="UPDATE productos 
			           SET nombre=?,precio=?,descripcion=?,stock=?,imagen=?,membresia=?
		               WHERE id=?";
			$sentencia=$conexion->prepare($consulta);
			$sentencia->bind_param("sisisii",$nombre,$precio,$descripcion,$stock,$imagen,$membresia,$id);
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

	function borrarAsignatura($conexion,$id){

		if(is_numeric($id)){
			$consulta="DELETE FROM asignaturas WHERE id=?";
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