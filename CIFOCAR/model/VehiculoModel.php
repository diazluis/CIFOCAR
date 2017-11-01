<?php
	class VehiculoModel{
		//PROPIEDADES
		public $id, $matricula, $modelo, $color, $precio_venta, $precio_compra, $kms, $caballos, $fecha_venta, $estado, $any_matriculacion, $detalles, $imagen='', $vendedor, $marca;
			
		//METODOS
		
		//recuperar vehiculos (con filtros)
		public static function getvehiculos($l=10, $o=0, $texto='', $sentido='ASC'){
		    //preparar la consulta
		    $consulta = "SELECT * FROM vehiculos
                         WHERE vehiculo LIKE '%$texto%'
                         ORDER BY vehiculo $sentido
                         LIMIT $l
                         OFFSET $o;";
		    
		    //ejecutar la consulta
		    $resultados = Database::get()->query($consulta);
		    
		    //prepara la lista para los resultados
		    $lista=array();
		    
		    //rellenar la lista con los resultados
		    while($vehiculo = $resultados->fetch_object('vehiculoModel'))
		        $lista[] = $vehiculo;
		        
		        //liberar memoria
		        $resultados->free();
		        
		        //retornar la lista
		        return $lista;
		}
		
		//guarda el vehiculo en la BDD
		public function guardar(){
			$user_table = Config::get()->db_user_table;
			$consulta = "INSERT INTO $user_table(id, matricula, modelo, color, precio_venta, precio_compra, kms, caballos, fecha_venta, estado, any_matriculacion, detalles, imagen='', vendedor, marca)
			VALUES ('$this->matricula','$this->modelo','$this->color','$this->precio_venta', '$this->precio_compra', '$this->kms', '$this->caballos', '$this->fecha_venta', '$this->estado', '$this->any_matriculacion', '$this->detalles', '$this->imagen', '$this->vendedor', '$this->marca');";
				
			return Database::get()->query($consulta);
		}
		
		//m�todo que me recupera el total de registros (incluso con filtros)
		public static function getTotal($t='', $c='vehiculo'){
		    $consulta = "SELECT * FROM vehiculos
                         WHERE $c LIKE '%$t%'";
		    
		    
		    $conexion = Database::get();
		    $resultados = $conexion->query($consulta);
		    $total = $resultados->num_rows;
		    $resultados->free();
		    return $total;
		}
		
		
		
		//actualiza los datos del vehiculo en la BDD
		public function actualizar(){
		    $user_table = Config::get()->db_user_table;
			$consulta = "UPDATE $user_table
							  SET matricula='$this->matricula', 
							  		modelo='$this->modelo', 
							  		color='$this->color', 
							  		precio_venta='$this->precio_venta',
                                    precio_compra='$this->precio_compra',
                                    kms='$this->kms',
                                    caballos='$this->caballos',
                                    fecha_venta='$this->fecha_venta',
                                    estado='$this->estado',
                                    any_matriculacion='$this->any_matriculacion',
                                    detalles='$this->detalles',
                                    imagen='$this->imagen',
                                    vendedor='$this->vendedor',
                                    marca='$this->marca'    
							  WHERE id='$this->id';";
			return Database::get()->query($consulta);
		}
		
		
		//elimina el vehiculo de la BDD
		public function borrar(){
			$user_table = Config::get()->db_user_table;
			$consulta = "DELETE FROM $user_table WHERE id='$this->id';";
			return Database::get()->query($consulta);
		}
		
		
		
		//este método sirve para comprobar user y password (en la BDD)
		public static function validar($u, $p){
			$user_table = Config::get()->db_user_table;
			$consulta = "SELECT * FROM $user_table WHERE user='$u' AND password='$p';";
			$resultado = Database::get()->query($consulta);
			
			//si hay algun vehiculo retornar true sino false
			$r = $resultado->num_rows;
			$resultado->free(); //libera el recurso resultset
			return $r;
		}
		
		//este método debería retornar un vehiculo creado con los datos 
		//de la BDD (o NULL si no existe), a partir de un nombre de vehiculo
		public static function getVehiculo($u){
			$user_table = Config::get()->db_user_table;
			$consulta = "SELECT * FROM $user_table WHERE user='$u';";
			$resultado = Database::get()->query($consulta);
			
			$us = $resultado->fetch_object('VehiculoModel');
			$resultado->free();
			
			return $us;
		}	
	}
?>