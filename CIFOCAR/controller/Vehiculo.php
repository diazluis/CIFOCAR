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
				$u->matricula = $conexion->real_escape_string($_POST['matricula']);
				$u->modelo = $conexion->real_escape_string($_POST['modelo']);
				$u->color = $conexion->real_escape_string($_POST['color']);
				$u->precio_venta = $conexion->real_escape_string($_POST['precio_venta']);
				$u->precio_compra = $conexion->real_escape_string($_POST['precio_compra']);
				$u->kms = $conexion->real_escape_string($_POST['kms']);
				$u->caballos = $conexion->real_escape_string($_POST['caballos']);
				$u->fecha_venta = $conexion->real_escape_string($_POST['fecha_venta']);
				$u->estado = $conexion->real_escape_string($_POST['estado']);
				$u->any_matriculacion = $conexion->real_escape_string($_POST['any_matriculacion']);
				$u->detalles = $conexion->real_escape_string($_POST['detalles']);	
				$u->imagen = Config::get()->default_user_image;
				$u->vendedor = $conexion->real_escape_string($_POST['vendedor']);
				$u->marca = $conexion->real_escape_string($_POST['marca']);
				
				
								
				//guardar el vehiculo en BDD
				if(!$u->guardar())
					throw new Exception('No se pudo registrar la vehiculo');
				
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
		
		//PROCEDIMIENTO PARA MODIFICAR UN Vehiculo
		public function modificacion(){
			//si no hay vehiculo identificado... error
			if(!Login::getVehiculo())
				throw new Exception('Debes estar identificado para poder modificar tus datos');
				
			//si no llegan los datos a modificar
			if(empty($_POST['modificar'])){
				
				//mostramos la vista del formulario
				$datos = array();
				$datos[''] = Login::getVehiculo();
				$datos['max_image_size'] = Config::get()->user_image_max_size;
				$this->load_view('view/s/modificacion.php', $datos);
					
				//si llegan los datos por POST
			}else{
				//recuperar los datos actuales del Vehiculo
				$u = Login::getVehiculo();
				$conexion = Database::get();
				
				//comprueba que el vehiculo se valide correctamente
				$p = MD5($conexion->real_escape_string($_POST['password']));
				if($u->password != $p)
					throw new Exception('El password no coincide, no se puede procesar la modificación');
								
				//recupera el nuevo password (si se desea cambiar)
				if(!empty($_POST['newpassword']))
					$u->password = MD5($conexion->real_escape_string($_POST['newpassword']));
				
				//recupera el nuevo nombre y el nuevo email
				$u->nombre = $conexion->real_escape_string($_POST['nombre']);
				$u->email = $conexion->real_escape_string($_POST['email']);
						
				//TRATAMIENTO DE LA NUEVA IMAGEN DE PERFIL (si se indicó)
				if($_FILES['imagen']['error']!=4){
					//el directorio y el tam_maximo se configuran en el fichero config.php
					$dir = Config::get()->user_image_directory;
					$tam = Config::get()->user_image_max_size;
					
					//prepara la carga de nueva imagen
					$upload = new Upload($_FILES['imagen'], $dir, $tam);
					
					//guarda la imagen antigua en una var para borrarla 
					//después si todo ha funcionado correctamente
					$old_img = $u->imagen;
					
					//sube la nueva imagen
					$u->imagen = $upload->upload_image();
				}
				
				//modificar el vehiculo en BDD
				if(!$u->actualizar())
					throw new Exception('No se pudo modificar');
		
				//borrado de la imagen antigua (si se cambió)
				//hay que evitar que se borre la imagen por defecto
				if(!empty($old_img) && $old_img!= Config::get()->default_user_image)
					@unlink($old_img);
						
				//hace de nuevo "login" para actualizar los datos del vehiculo
				//desde la BDD a la variable de sesión.
				Login::log_in($u->user, $u->password);
					
				//mostrar la vista de éxito
				$datos = array();
				$datos['vehiculo'] = Login::getVehiculo();
				$datos['mensaje'] = 'Modificación OK';
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