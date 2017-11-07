<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta charset="UTF-8">
		<title>Edición de vehiculos</title>
		<link rel="stylesheet" type="text/css" href="<?php echo Config::get()->css;?>" />
	</head>
	
	<body>
		<?php 
			Template::header(); //pone el header

			if(!$usuario) Template::login(); //pone el formulario de login
			else Template::logout($usuario); //pone el formulario de logout
			 
			Template::menu($usuario); //pone el menú
		?>
		
		<section id="content">
			<h2>Modificar vehiculo <?php echo $vehiculo->vehiculo;?></h2>
			<form method="post" enctype="multipart/form-data" autocomplete="off">
				<label>Vehiculo:</label>
				<input type="text" name="vehiculo" required="required" value="<?php echo $vehiculo->vehiculo;?>" /><br/>
				
				<br>
				<input type="submit" name="actualizar" value="MODIFICAR"/><br/>
			</form>
			
			<h3>Eliminar vehiculo</h3>
			<input type="submit" name="borrar" value="BORRAR"/><br/>
		</section>
		
		<?php Template::footer();?>
    </body>
</html>