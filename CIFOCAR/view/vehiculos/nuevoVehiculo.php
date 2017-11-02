<?php if(empty ($GLOBALS['index_access'])) die('no se puede acceder directamente a una vista.'); ?>
<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<title>Registro de marcas</title>
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
			<h2>Nuevo vehículo:</h2>
			<form method="post" enctype="multipart/form-data" autocomplete="off">
				<label>Marca:</label>
				<input type="text" name="marca" required="required" /><br/>                
				
				<label>Modelo:</label>
				<input type="text" name="modelo" required="required" /><br/>
				
				<label>Matrícula:</label>
				<input type="text" name="matricula" required="required" /><br/>
				
				<label>Color:</label>
				<input type="text" name="color" required="required" /><br/>
				
				<label>Precio de venta:</label>
				<input type="text" name="precio_venta" required="required" /><br/>
				
				<label>Precio de compra:</label>
				<input type="text" name="precio_compra" required="required" /><br/>
				
				<label>Kilómetros:</label>
				<input type="text" name="kms" required="required" /><br/>
				
				<label>Potencia:</label>
				<input type="text" name="caballos" required="required" /><br/>
				
				<label>Fecha de venta:</label>
				<input type="text" name="fecha_venta" required="required" /><br/>
							
				<label>Detalles:</label>
				<input type="text" name="detalles" required="required" /><br/>
						
				<label>Vendedor:</label>
				<input type="text" name="vendedor" required="required" /><br/>
				
				<!-- El formulario se debe enviar por POST y contener el atributo �enctype� con el valor �multipart/form-data� -->
                			
				<input type="submit" name="guardar" value="guardar"/><br/>
			</form>
		</section>
		
		<?php Template::footer();?>
    </body>
</html>