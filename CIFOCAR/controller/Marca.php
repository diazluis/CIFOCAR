<?php
	//CONTROLADOR MARCA 
	// implementa las operaciones que puede realizar el marca
	class Marca extends Controller{

		//PROCEDIMIENTO PARA REGISTRAR UNA MARCA
		public function registro(){

			//si no llegan los datos a guardar
			if(empty($_POST['guardar'])){
				
				//mostramos la vista del formulario
				$datos = array();
				$datos['usuario'] = Login::getUsuario();
				$datos['max_image_size'] = Config::get()->user_image_max_size;
				$datos['usuario'] = Login::getUsuario();
				$this->load_view('view/marcas/nuevaMarca.php', $datos);
			
			//si llegan los datos por POST
			}else{
				//crear una instancia de marca
			    $this->load('model/MarcaModel.php');
				$u = new MarcaModel();
				$conexion = Database::get();
				
				//tomar los datos que vienen por POST
				//real_escape_string evita las SQL Injections
				$u->marca = $conexion->real_escape_string($_POST['marca']);
				
								
				//guardar el marca en BDD
				if(!$u->guardar())
					throw new Exception('No se pudo registrar la marca');
				
				//mostrar la vista de éxito
				$datos = array();
				$datos['usuario'] = Login::getUsuario();
				$datos['mensaje'] = 'Operación de registro completada con éxito';
				$this->load_view('view/exito.php', $datos);
			}
		}
		
		//PROCEDIMIENTO PARA LISTAR LAS MARCAS
		public function listar($pagina){
		    $this->load('model/MarcaModel.php');
		    
		    //si me piden APLICAR un filtro
		    if(!empty($_POST['filtrar'])){
		        //recupera el filtro a aplicar
		        $f = new stdClass(); //filtro
		        $f->texto = htmlspecialchars($_POST['texto']);
		        
		        
		        //guarda el filtro en un var de sesi�n
		        $_SESSION['filtroMarcas'] = serialize($f);
		    }
		    
		    //si me piden QUITAR un filtro
		    if(!empty($_POST['quitarFiltro']))
		        unset($_SESSION['filtroMarcas']);
		        
		        
		        //comprobar si hay filtro
		        $filtro = empty($_SESSION['filtroMarcas'])? false : unserialize($_SESSION['filtroMarcas']);
		        
		        //para la paginaci�n
		        $num = 5; //numero de resultados por p�gina
		        $pagina = abs(intval($pagina)); //para evitar cosas raras por url
		        $pagina = empty($pagina)? 1 : $pagina; //p�gina a mostrar
		        $offset = $num*($pagina-1); //offset
		        
		        //si no hay que filtrar los resultados...
		        if(!$filtro){
		            //recupera todas las marcas
		            $marcas = MarcaModel::getMarcas($num, $offset);
		            //total de registros (para paginaci�n)
		            $totalRegistros = MarcaModel::getTotal();
		        }else{
		            //recupera las Marcas con el filtro aplicado
		            $marcas = MarcaModel::getMarcas($num, $offset, $filtro->texto);
		            //total de registros (para paginaci�n)
		            $totalRegistros = MarcaModel::getTotal($filtro->texto);
		        }
		        
		        //cargar la vista del listado
		        $datos = array();
		        $datos['usuario'] = Login::getUsuario();
		        $datos['marcas'] = $marcas;
		        $datos['filtro'] = $filtro;
		        $datos['paginaActual'] = $pagina;
		        $datos['paginas'] = ceil($totalRegistros/$num); //total de p�ginas (para paginaci�n)
		        $datos['totalRegistros'] = $totalRegistros;
		        $datos['regPorPagina'] = $num;
		        
		        if(Login::isAdmin())
		            $this->load_view('view/marcas/listaMarca.php', $datos);
		            else
		                $this->load_view('view/marcas/listaMarca.php', $datos);
		}
		
		//PROCEDIMIENTO PARA MODIFICAR UNA MARCA
		public function editar($id=0){
		    //comprobar que el usuario es admin
		    if(!Login::isAdmin())
		        throw new Exception('Debes ser admin');
		        
	        //comprobar que me llega un id
	        if(!$id)
	            throw new Exception('No se indicó la marca');
	            
            //recuperar la marca con esa id
            $this->load('model/MarcaModel.php');
            $marca = MarcaModel::getMarca($id);
            
            //comprobar que existe la marca
            if(!$marca)
                throw new Exception('No existe la marca');
                
            //si no me están enviando el formulario
            if(empty($_POST['actualizar'])){
                //poner el formulario
                $datos = array();
                $datos['usuario'] = Login::getUsuario();
                $datos['marca'] = $marca;
                $this->load_view('view/marcas/modificar.php', $datos);
                
            }else{
                //en caso contrario
                $conexion = Database::get();
                //actualizar los campos de la marca con los datos POST
                $marca->marca = $conexion->real_escape_string($_POST['marca']);
                	                    
                
                //modificar la marca en la BDD
                if(!$marca->actualizar())
                    throw new Exception('No se pudo actualizar');
                    
                //cargar la vista de éxito
                $datos = array();
                $datos['usuario'] = Login::getUsuario();
                $datos['mensaje'] = "Datos de la marca actualizados correctamente.";
                
                $this->load_view('view/exito.php', $datos);
            }
		}
		
		
		//PROCEDIMIENTO PARA BORRAR UNA MARCA
		public function borrar($id=0){
		    //comprobar que el usuario sea admin
		    if(!Login::isAdmin())
		        throw new Exception('Debes ser ADMIN');
		        
		        //comprobar que se ha indicado un id
		        if(!$id)
		            throw new Exception('No se indicó la marca a borrar');
		            
		            $this->load('model/MarcaModel.php');
		            
		            //si no me envian el formulario de confirmación
		            if(empty($_POST['confirmarborrado'])){
		                //recuperar la marca con esa id
		                $marca = MarcaModel::getMarca($id);
		                
		                //comprobar que existe dicha marca
		                if(!$marca)
		                    throw new Exception('No existe la marca con id '.$id);
		                    
		                    //mostrar el formularion de confirmación junto con los datos de la marca
		                    $datos = array();
		                    $datos['marca'] = $marca;
		                    $datos['usuario'] = Login::getUsuario();
		                    $this->load_view('view/marcas/baja.php', $datos);
		                    
		                    //si me envian el formulario...
		            }else{
		                //borramos la marca de la BDD
		                if(!MarcaModel::borrar($id))
		                    throw new Exception('No se pudo borrar, es posible que se haya borrado ya.');
		                    
	                    //cargar la vista de éxito
	                    $datos = array();
	                    
	                    $datos['mensaje'] = 'Operación de borrado ejecutada con éxito.';
	                    $datos['usuario'] = Login::getUsuario();
	                    $this->load_view('view/exito.php', $datos);
		                    
		            }
		}
		
	}
?>