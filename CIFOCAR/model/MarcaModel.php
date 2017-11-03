<?php
    class MarcaModel{
        //PROPIEDADES
        public $marca;
        
        //METODOS
        //guardar marca
        public function guardar(){
            $consulta = "INSERT INTO marcas(marca)
			VALUES ('$this->marca');";
            
            return Database::get()->query($consulta);
        }
                 
        //recuperar marcas (con filtros)
        public static function getMarcas($l=10, $o=0, $texto='', $sentido='ASC'){
            //preparar la consulta
            $consulta = "SELECT * FROM marcas
                         WHERE marca LIKE '%$texto%' 
                         ORDER BY marca $sentido
                         LIMIT $l
                         OFFSET $o;";
            
            //ejecutar la consulta
            $resultados = Database::get()->query($consulta);
            
            //prepara la lista para los resultados
            $lista=array();
            
            //rellenar la lista con los resultados
            while($marca = $resultados->fetch_object('MarcaModel'))
                $lista[] = $marca;
            
            //liberar memoria
            $resultados->free();
            
            //retornar la lista
            return $lista;
        }
        
        //actualizar marca
        public function actualizar(){
            $consulta = "UPDATE marcas
							  SET marca='$this->marca',
    					  WHERE id=$this->id;";
            return Database::get()->query($consulta);
        }
        
        //borrar marca
        public static function borrar($id){
            //preparar consulta
            $consulta = "DELETE FROM marcas
                         WHERE id='$id';";
            
            $conexion = Database::get(); //conecta
            $conexion->query($consulta); //ejecuta consulta
            return $conexion->affected_rows; //devuelve el num de filas afectadas
        }
        
        
        //m�todo que me recupera el total de registros (incluso con filtros)
        public static function getTotal($t='', $c='marca'){
            $consulta = "SELECT * FROM marcas
                         WHERE $c LIKE '%$t%'";
            
            $conexion = Database::get();
            $resultados = $conexion->query($consulta);
            $total = $resultados->num_rows;
            $resultados->free();
            return $total;
        }
        
        public static function getMarca($id=0){
            //preparar consulta
            $consulta = "SELECT * FROM marcas WHERE id=$id;";
            
            //ejecutar consulta
            $conexion = Database::get();
            $resultado = $conexion->query($consulta);
            
            //si no había resultados, retornamos NULL
            if(!$resultado) return null;
            
            //convertir el resultado en un objeto MarcaModel
            $marca = $resultado->fetch_object('MarcaModel');
            
            //liberar memoria
            $resultado->free();
            
            //devolver el resultado
            return $marca;
        }
        
        
        
        
       
    }
  
?>