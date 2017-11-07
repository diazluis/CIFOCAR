<?php if(empty ($GLOBALS['index_access'])) die('no se puede acceder directamente a una vista.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<title>Portada</title>
		<link rel="stylesheet" type="text/css" href="<?php echo Config::get()->css;?>" />
	</head>
	
	<body>
		<?php 
			Template::header(); //pone el header

			Template::menu($usuario); //pone el menú
		?>

		<section id="content">
		Bienvenido a la aplicación web de CIFOCAR.<br>
		<br>
		
		Para comenzar a operar primero debes loguearte, lo que te dará acceso a todas las funciones permitidas para tu nivel de usuario:<br><br>
		<?php if(!$usuario) Template::loginInicio(); //pone el formulario de login
		          else Template::logoutInicio($usuario); //pone el formulario de logout	
		?>
		
		</section>
		
		<?php Template::footer();?>
    </body> 
</html>
