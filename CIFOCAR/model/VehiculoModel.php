<?php
	class VehiculoModel{
		//PROPIEDADES
	    public $id, $imagen, $marca, $modelo, $color;
	    
		//METODOS
		//guarda el vehículo en la BDD
		public function guardar(){
			$consulta = "INSERT INTO vehiculos(imagen, marca, modelo, color)
			     VALUES ('$this->imagen', '$this->marca','$this->modelo', '$this->color');";
				
			return Database::get()->query($consulta);
		}
		
		
		//actualiza los datos del vehiculo en la BDD
		public function actualizar(){
		    $consulta = "UPDATE vehiculos
							  SET   marca='$this->marca',
                                    modelo='$this->modelo',
                                    color='$this->color'
                                                                
							  WHERE id=$this->id;";
		    return Database::get()->query($consulta);
		}
		
		
		//Método que borra una vehiculo de la BDD (estático)
		//PROTOTIPO: public static boolean borrar(int $id)
		public static function borrar($id){
		    $consulta = "DELETE FROM vehiculos
                         WHERE id=$id;";
		    
		    $conexion = Database::get(); //conecta
		    $conexion->query($consulta); //ejecuta consulta
		    return $conexion->affected_rows; //devuelve el num de filas afectadas
		}
		
		
		
				
			
		
		
		//método que me recupera todos las vehiculos
		//PROTOTIPO: public static array<VehiculoModel> getVehiculos()
		public static function getVehiculos($l=10, $o=0,$t='', $c='marca', $co='id', $so='ASC'){
		    //preparar la consulta
		    $consulta = "SELECT * FROM vehiculos
                         WHERE $c LIKE '%$t%'
                         ORDER BY $co $so
                         LIMIT $l
                         OFFSET $o;";
		    
		    //conecto a la BDD y ejecuto la consulta
		    $conexion = Database::get();
		    $resultados = $conexion->query($consulta);
		    
		    //creo la lista de VehiculoModel
		    $lista = array();
		    while($vehiculo = $resultados->fetch_object('VehiculoModel'))
		        $lista[] = $vehiculo;
		        
		        //liberar memoria
		        $resultados->free();
		        
		        //retornar la lista de VehiculoModel
		    return $lista;
		}
		
		//método que me recupera el total de registros (incluso con filtros)
		public static function getTotal($t='', $c='marca'){
		    $consulta = "SELECT * FROM vehiculos
                         WHERE $c LIKE '%$t%'";
		    
		     
		    $conexion = Database::get();
		    $resultados = $conexion->query($consulta);
		    $total = $resultados->num_rows;
		    $resultados->free();
		    return $total;
		}
		
		
		//Método que recupera una vehiculo a partir de su id
		//PROTOTIPO: public static VehiculoModel getVehiculo(number $id);
		
		public static function getVehiculo($id=0){
		    //preparar consulta
		    $consulta = "SELECT * FROM vehiculos WHERE id=$id;";
		    
		    //ejecutar consulta
		    $conexion = Database::get();
		    $resultado = $conexion->query($consulta);
		    
		    //si no había resultados, retornamos NULL
		    if(!$resultado) return null;
		    
		    //convertir el resultado en un objeto VehiculoModel
		    $vehiculo = $resultado->fetch_object('VehiculoModel');
		    
		    //liberar memoria
		    $resultado->free();
		    
		    //devolver el resultado
		    return $vehiculo;
		}
		
		}
		
		
	
?>