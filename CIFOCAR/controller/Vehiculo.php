<?php
	//CONTROLADOR VEHICULO 
	// implementa las operaciones que puede realizar el vehiculo
	class Vehiculo extends Controller{

		//PROCEDIMIENTO PARA REGISTRAR UNA VEHICULO
		public function registro(){

			//si no llegan los datos a guardar
			if(empty($_POST['guardar'])){
				
				//mostramos la vista del formulario
				$datos = array();
				$datos['usuario'] = Login::getUsuario();
				$datos['max_image_size'] = Config::get()->user_image_max_size;
				$this->load_view('view/vehiculos/nuevoVehiculo.php', $datos);
			
			//si llegan los datos por POST
			}else{
				//crear una instancia de vehiculo
			    $this->load('model/VehiculoModel.php');
				$u = new VehiculoModel();
				$conexion = Database::get();
								
				//tomar los datos que vienen por POST
				//real_escape_string evita las SQL Injections
				$u->vehiculo = $conexion->real_escape_string($_POST['vehiculo']);
				$u->modelo = $conexion->real_escape_string($_POST['modelo']);
				$u->color = $conexion->real_escape_string($_POST['color']);
				$u->imagen = Config::get()->default_user_image;
				
				//Subir imagen
				$fichero = $_FILES['imagen']; //fichero
				$destino = 'images/coches/'; //ruta de destino en el servidor
				$tam_maximo = 10000000; //10MB aprox
				$renombrar = true; //cambia el nombre del fichero original para evitar sobreescrituras
				
				$upload = new Upload($fichero, $destino, $tam_maximo, $renombrar);
				$vehiculo->imagen = $upload->upload_image();
				
								
				//guardar el vehiculo en BDD
				if(!$u->guardar())
				    
					throw new Exception('No se pudo registrar el vehiculo');
				
				//mostrar la vista de éxito
				$datos = array();
				$datos['usuario'] = Login::getUsuario();
				$datos['mensaje'] = 'Operación de registro completada con éxito';
				$this->load_view('view/exito.php', $datos);
			}
		}
		
		//PROCEDIMIENTO PARA LISTAR LAS VEHICULOS
		public function listar($pagina){
		    $this->load('model/VehiculoModel.php');
		    
		    //si me piden APLICAR un filtro
		    if(!empty($_POST['filtrar'])){
		        //recupera el filtro a aplicar
		        $f = new stdClass(); //filtro
		        $f->texto = htmlspecialchars($_POST['texto']);
		        $f->campo = htmlspecialchars($_POST['campo']);
		        $f->campoOrden = htmlspecialchars($_POST['campoOrden']);
		        $f->sentidoOrden = htmlspecialchars($_POST['sentidoOrden']);
		        
		        //guarda el filtro en un var de sesi�n
		        $_SESSION['filtroVehiculos'] = serialize($f);
		    }
		    
		    //si me piden QUITAR un filtro
		    if(!empty($_POST['quitarFiltro']))
		        unset($_SESSION['filtroVehiculos']);
		        
		        
		        //comprobar si hay filtro
		        $filtro = empty($_SESSION['filtroVehiculos'])? false : unserialize($_SESSION['filtroVehiculos']);
		        
		        //para la paginaci�n
		        $num = 5; //numero de resultados por p�gina
		        $pagina = abs(intval($pagina)); //para evitar cosas raras por url
		        $pagina = empty($pagina)? 1 : $pagina; //p�gina a mostrar
		        $offset = $num*($pagina-1); //offset
		        
		        //si no hay que filtrar los resultados...
		        if(!$filtro){
		            //recupera todas las vehiculos
		            $vehiculos = VehiculoModel::getVehiculos($num, $offset);
		            //total de registros (para paginaci�n)
		            $totalRegistros = VehiculoModel::getTotal();
		            
		        }else{
		            //recupera las Vehiculos con el filtro aplicado
		            $vehiculos = VehiculoModel::getVehiculos($num, $offset, $filtro->texto, $filtro->campo, $filtro->campoOrden, $filtro->sentidoOrden);
		            //total de registros (para paginaci�n)
		            $totalRegistros = VehiculoModel::getTotal($filtro->texto, $filtro->campo);
		        }
		        
		        //cargar la vista del listado
		        $datos = array();
		        $datos['usuario'] = Login::getUsuario();
		        $datos['vehiculos'] = $vehiculos;
		        $datos['filtro'] = $filtro;
		        $datos['paginaActual'] = $pagina;
		        $datos['paginas'] = ceil($totalRegistros/$num); //total de p�ginas (para paginaci�n)
		        $datos['totalRegistros'] = $totalRegistros;
		        $datos['regPorPagina'] = $num;
		        
		        if(Login::isAdmin())
		            $this->load_view('view/vehiculos/listaVehiculo.php', $datos);
		            else
		                $this->load_view('view/vehiculos/listaVehiculo.php', $datos);
		}
		
		//PROCEDIMIENTO PARA MODIFICAR UNA VEHICULO
		public function editar($id=0){
		    //comprobar que el usuario es admin
		    if(!Login::isAdmin())
		        throw new Exception('Debes ser admin');
		        
		        //comprobar que me llega un id
		        if(!$id)
		            throw new Exception('No se indicó la vehiculo');
		            
		            //recuperar la vehiculo con esa id
		            $this->load('model/VehiculoModel.php');
		            $vehiculo = VehiculoModel::getVehiculo($id);
		            
		            //comprobar que existe la vehiculo
		            if(!$vehiculo)
		                throw new Exception('No existe el vehiculo');
		                
		                //si no me están enviando el formulario
		                if(empty($_POST['actualizar'])){
		                    //poner el formulario
		                    $datos = array();
		                    $datos['usuario'] = Login::getUsuario();
		                    $datos['vehiculo'] = $vehiculo;
		                    $this->load_view('view/vehiculos/modificarVehiculo.php', $datos);
		                    
		                }else{
		                    //en caso contrario
		                    $conexion = Database::get();
		                    //actualizar los campos de la vehiculo con los datos POST
		                    $vehiculo->marca = $conexion->real_escape_string($_POST['marca']);
		                    $vehiculo->modelo = $conexion->real_escape_string($_POST['modelo']);
		                    $vehiculo->color = $conexion->real_escape_string($_POST['color']);
		                    //modificar la vehiculo en la BDD
		                    if(!$vehiculo->actualizar())
		                        throw new Exception('No se pudo actualizar');
		                        
		                        //cargar la vista de éxito
		                        $datos = array();
		                        $datos['usuario'] = Login::getUsuario();
		                        $datos['mensaje'] = "Datos de la vehiculo actualizados correctamente.";
		                        
		                        $this->load_view('view/exito.php', $datos);
		                }
		}
		
		
		//PROCEDIMIENTO PARA DAR DE BAJA UN Vehiculo
		//solicita confirmación
		public function baja(){		
			//recuperar vehiculo
			$u = Login::getVehiculo();
			
			//asegurarse que el vehiculo está identificado
			if(!$u) throw new Exception('Debes estar identificado para poder darte de baja');
			
			//si no nos están enviando la conformación de baja
			if(empty($_POST['confirmar'])){	
				//carga el formulario de confirmación
				$datos = array();
				$datos['vehiculo'] = $u;
				$this->load_view('view/vehiculos/baja.php', $datos);
		
			//si nos están enviando la confirmación de baja
			}else{
				//validar password
				$p = MD5(Database::get()->real_escape_string($_POST['password']));
				if($u->password != $p) 
					throw new Exception('El password no coincide, no se puede procesar la baja');
				
				//de borrar el vehiculo actual en la BDD
				if(!$u->borrar())
					throw new Exception('No se pudo dar de baja');
						
				//borra la imagen (solamente en caso que no sea imagen por defecto)
				if($u->imagen!=Config::get()->default_user_image)
					@unlink($u->imagen); 
			
				//cierra la sesion
				Login::log_out();
					
				//mostrar la vista de éxito
				$datos = array();
				$datos['vehiculo'] = null;
				$datos['mensaje'] = 'Eliminado OK';
				$this->load_view('view/exito.php', $datos);
			}
		}
		
	}
?>