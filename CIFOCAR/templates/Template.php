<?php
class Template{
    
    //PONE EL HEADER DE LA PAGINA
    public static function header(){	?>
			
			<header>
			 			
				<hgroup>
					<h1>Cifocar</h1>
					<h3>Aplicaci�n de gesti�n de compra venta de veh�culos</h3>
				</hgroup>
				<!--<figure>
					 <a href="index.php">
						<img alt="The Guitar Data Base" src="/guitarras/images/guitar-logo.png" />
					</a> 
				     
				</figure>-->
			   
			</header>
		<?php }
		
		
		//PONE EL FORMULARIO DE LOGIN
		public static function login(){?>
			<form method="post" id="login" autocomplete="off">
				<label>User:</label><input type="text" name="user" required="required" />
				<label>Password:</label><input type="password" name="password" required="required"/>
				<input type="submit" name="login" value="Login" />
			</form>
		<?php }
		
		
		//PONE LA INFO DEL USUARIO IDENTIFICADO Y EL FORMULARIOD E LOGOUT
		public static function logout($usuario){	?>
			<div id="logout">
				<span>
					<a href="index.php?controlador=Usuario&operacion=modificacion" title="modificar datos">
						<?php echo $usuario->nombre;?></a>
					<span class="mini">
						<?php echo ' ('.$usuario->email.')';?>
					</span>
					<?php if($usuario->admin) echo ', eres administrador';?>
				</span>
								
				<form method="post">
					<input type="submit" name="logout" value="Logout" />
				</form>
				
				<div class="clear"></div>
			</div>
		<?php }
		
		
		//PONE EL MENU DE LA PAGINA
		public static function menu($usuario){ ?>
			<nav>
				<ul class="menu">
					<li><a href="index.php">Inicio</a></li>
					<li><a href="index.php?controlador=Usuario&operacion=registro">Registro</a></li>
					<li><a href="index.php?controlador=Marca&operacion=listar">Listar Marcas</a></li>
					
					
				
				<?php 
				//pone el men� del administrador
				if($usuario && $usuario->admin){	?>
					<li><a href="index.php?controlador=Marca&operacion=registro">Nueva Marca</a></li>
					<!-- <li><a href="index.php?controlador=Marca&operacion=nueva">Nueva Guitarra</a></li> -->
							
				<?php }	?>
				<li><a href="index.php?controlador=Vehiculo&operacion=listar">Listar Veh�culos</a></li>
					</ul>
			</nav>
		<?php }
		
		//PONE EL PIE DE PAGINA
		public static function footer(){	?>
			<footer>
				<p>
					<a href="http://recursos.robertsallent.com/mvc/robs_micro_fw_1.0.zip">
						Aplicaci�n concesionarios Cifocar</a> - solo para fines docentes
				</p>
				<p> 
					Luis D�az - 					 -  
					<a href="https://www.facebook.com/cifovalles">CIFO del Vall�s'17</a>. 
         		</p>
			</footer>
		<?php }
	}
?>