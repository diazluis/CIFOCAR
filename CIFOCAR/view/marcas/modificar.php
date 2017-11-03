<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta charset="UTF-8">
		<title>Edición de marcas</title>
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
			<h2>Modificar marca <?php echo $marca->marca;?></h2>
			<form method="post" enctype="multipart/form-data" autocomplete="off">
				<label>Marca:</label>
				<input type="text" name="marca" required="required" value="<?php echo $marca->marca;?>" /><br/>
				
				<br>
				<input type="submit" name="modificar" value="MODIFICAR"/><br/>
			</form>
			
			<h3>Eliminar marca</h3>
			<input type="submit" name="borrar" value="BORRAR"/><br/>
		</section>
		
		<?php Template::footer();?>
    </body>
</html>