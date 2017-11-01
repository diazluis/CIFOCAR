<?php
    class MarcaModel{
        //PROPIEDADES
        public $marca;
        
        //METODOS
        //guardar marca
        public function guardar(){
            $user_table = Config::get()->db_user_table;
            $consulta = "INSERT INTO $user_table(marca)
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
        public static function actualizar($new, $old){
            //preparar consulta
            $consulta = "UPDATE marcas 
                         SET marca='$new'
                         WHERE marca='$old';";
            //ejecutar consulta
            Database::get()->query($consulta);
            
            //retornar número de filas afectadas
            return Database::get()->affected_rows;
        }
        
        //borrar marca
        public static function borrar($marca){
            //preparar consulta
            $consulta = "DELETE FROM marcas
                         WHERE marca='$marca';";
            
            //ejecutar consulta
            Database::get()->query($consulta);
            
            //retornar número de filas afectadas
            return Database::get()->affected_rows;
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
        
        
       
    }
  
?>