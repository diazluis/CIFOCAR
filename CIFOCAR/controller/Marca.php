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
				
				//mostrar la vista de 茅xito
				$datos = array();
				$datos['usuario'] = Login::getUsuario();
				$datos['mensaje'] = 'Operaci贸n de registro completada con 茅xito';
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
		        $f->campo = htmlspecialchars($_POST['campo']);
		        $f->campoOrden = htmlspecialchars($_POST['campoOrden']);
		        $f->sentidoOrden = htmlspecialchars($_POST['sentidoOrden']);
		        
		        //guarda el filtro en un var de sesi髇
		        $_SESSION['filtroMarcas'] = serialize($f);
		    }
		    
		    //si me piden QUITAR un filtro
		    if(!empty($_POST['quitarFiltro']))
		        unset($_SESSION['filtroMarcas']);
		        
		        
		        //comprobar si hay filtro
		        $filtro = empty($_SESSION['filtroMarcas'])? false : unserialize($_SESSION['filtroMarcas']);
		        
		        //para la paginaci髇
		        $num = 5; //numero de resultados por p醙ina
		        $pagina = abs(intval($pagina)); //para evitar cosas raras por url
		        $pagina = empty($pagina)? 1 : $pagina; //p醙ina a mostrar
		        $offset = $num*($pagina-1); //offset
		        
		        //si no hay que filtrar los resultados...
		        if(!$filtro){
		            //recupera todas las marcas
		            $marcas = MarcaModel::getMarcas($num, $offset);
		            //total de registros (para paginaci髇)
		            $totalRegistros = MarcaModel::getTotal();
		        }else{
		            //recupera las Marcas con el filtro aplicado
		            $marcas = MarcaModel::getMarcas($num, $offset, $filtro->texto, $filtro->campo, $filtro->campoOrden, $filtro->sentidoOrden);
		            //total de registros (para paginaci髇)
		            $totalRegistros = MarcaModel::getTotal($filtro->texto, $filtro->campo);
		        }
		        
		        //cargar la vista del listado
		        $datos = array();
		        $datos['usuario'] = Login::getUsuario();
		        $datos['marcas'] = $marcas;
		        $datos['filtro'] = $filtro;
		        $datos['paginaActual'] = $pagina;
		        $datos['paginas'] = ceil($totalRegistros/$num); //total de p醙inas (para paginaci髇)
		        $datos['totalRegistros'] = $totalRegistros;
		        $datos['regPorPagina'] = $num;
		        
		        if(Login::isAdmin())
		            $this->load_view('view/marcas/listaMarca.php', $datos);
		            else
		                $this->load_view('view/marcas/listaMarca.php', $datos);
		}
		
		//PROCEDIMIENTO PARA MODIFICAR UN Marca
		public function modificacion(){
			//si no hay marca identificado... error
			if(!Login::getMarca())
				throw new Exception('Debes estar identificado para poder modificar tus datos');
				
			//si no llegan los datos a modificar
			if(empty($_POST['modificar'])){
				
				//mostramos la vista del formulario
				$datos = array();
				$datos[''] = Login::getMarca();
				$datos['max_image_size'] = Config::get()->user_image_max_size;
				$this->load_view('view/s/modificacion.php', $datos);
					
				//si llegan los datos por POST
			}else{
				//recuperar los datos actuales del Marca
				$u = Login::getMarca();
				$conexion = Database::get();
				
				//comprueba que el marca se valide correctamente
				$p = MD5($conexion->real_escape_string($_POST['password']));
				if($u->password != $p)
					throw new Exception('El password no coincide, no se puede procesar la modificaci贸n');
								
				//recupera el nuevo password (si se desea cambiar)
				if(!empty($_POST['newpassword']))
					$u->password = MD5($conexion->real_escape_string($_POST['newpassword']));
				
				//recupera el nuevo nombre y el nuevo email
				$u->nombre = $conexion->real_escape_string($_POST['nombre']);
				$u->email = $conexion->real_escape_string($_POST['email']);
						
				//TRATAMIENTO DE LA NUEVA IMAGEN DE PERFIL (si se indic贸)
				if($_FILES['imagen']['error']!=4){
					//el directorio y el tam_maximo se configuran en el fichero config.php
					$dir = Config::get()->user_image_directory;
					$tam = Config::get()->user_image_max_size;
					
					//prepara la carga de nueva imagen
					$upload = new Upload($_FILES['imagen'], $dir, $tam);
					
					//guarda la imagen antigua en una var para borrarla 
					//despu茅s si todo ha funcionado correctamente
					$old_img = $u->imagen;
					
					//sube la nueva imagen
					$u->imagen = $upload->upload_image();
				}
				
				//modificar el marca en BDD
				if(!$u->actualizar())
					throw new Exception('No se pudo modificar');
		
				//borrado de la imagen antigua (si se cambi贸)
				//hay que evitar que se borre la imagen por defecto
				if(!empty($old_img) && $old_img!= Config::get()->default_user_image)
					@unlink($old_img);
						
				//hace de nuevo "login" para actualizar los datos del marca
				//desde la BDD a la variable de sesi贸n.
				Login::log_in($u->user, $u->password);
					
				//mostrar la vista de 茅xito
				$datos = array();
				$datos['marca'] = Login::getMarca();
				$datos['mensaje'] = 'Modificaci贸n OK';
				$this->load_view('view/exito.php', $datos);
			}
		}
		
		
		//PROCEDIMIENTO PARA DAR DE BAJA UN Marca
		//solicita confirmaci贸n
		public function baja(){		
			//recuperar marca
			$u = Login::getMarca();
			
			//asegurarse que el marca est谩 identificado
			if(!$u) throw new Exception('Debes estar identificado para poder darte de baja');
			
			//si no nos est谩n enviando la conformaci贸n de baja
			if(empty($_POST['confirmar'])){	
				//carga el formulario de confirmaci贸n
				$datos = array();
				$datos['marca'] = $u;
				$this->load_view('view/marcas/baja.php', $datos);
		
			//si nos est谩n enviando la confirmaci贸n de baja
			}else{
				//validar password
				$p = MD5(Database::get()->real_escape_string($_POST['password']));
				if($u->password != $p) 
					throw new Exception('El password no coincide, no se puede procesar la baja');
				
				//de borrar el marca actual en la BDD
				if(!$u->borrar())
					throw new Exception('No se pudo dar de baja');
						
				//borra la imagen (solamente en caso que no sea imagen por defecto)
				if($u->imagen!=Config::get()->default_user_image)
					@unlink($u->imagen); 
			
				//cierra la sesion
				Login::log_out();
					
				//mostrar la vista de 茅xito
				$datos = array();
				$datos['marca'] = null;
				$datos['mensaje'] = 'Eliminado OK';
				$this->load_view('view/exito.php', $datos);
			}
		}
		
	}
?>