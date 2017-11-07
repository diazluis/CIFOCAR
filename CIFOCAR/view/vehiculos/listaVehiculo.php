<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta charset="UTF-8">
		<title>Listado de vehículos</title>
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
   <h2>Listado de vehiculos</h2>
   <p>Hay <?php echo $totalRegistros; ?> registros<?php echo $filtro? ' para el filtro indicado':'';?>.</p>
   <!--  <p>Mostrando del <?php echo ($paginaActual-1)*$regPorPagina+1;?> al <?php echo ($paginaActual)*$regPorPagina;?>.</p> -->
            
	<?php if(!$filtro){?>
   <form method="post" class="filtro" action="index.php?controlador=Vehiculo&operacion=listar&parametro=1">
       <label>Filtro:</label>
        <input type="text" name="texto" placeholder="buscar..."/>
        <select name="campo">
                    <option value="marca">marca</option>
                    <option value="modelo">modelo</option>
                    <option value="matricula">matricula</option>
                    <option value="estado">estado</option>
                    <option value="color">color</option>
                </select>
                <label>Orden:</label>
                <select name="campoOrden">
                    <option value="marca">marca</option>
                    <option value="modelo">modelo</option>
                    <option value="matricula">matricula</option>
                    <option value="estado">estado</option>
                    <option value="color">color</option>
                </select>
                <select name="sentidoOrden">
                    <option value="ASC">ascendente</option>
                    <option value="DESC">descendente</option>
                </select>
                <input type="submit" name="filtrar" value="Filtrar"/>
            </form>
            <?php }else{ ?>
                <form method="post" class="filtro" action="index.php?controlador=Vehiculo&operacion=listar&parametro=1">
                    <label>Quitar filtro</label>
                    <input type="submit" name="quitarFiltro" value="Quitar" />
                </form>
            <?php }?>
            
            <table>
                <tr>
                	<th>Imagen</th>
                	<th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th colspan="3">Operaciones</th>
                </tr>
                <?php
                foreach($vehiculos as $vehiculo){
                    echo "<tr>";
                        echo "<td class='foto'><img class='miniatura' src='$vehiculo->imagen' alt='Imagen de $vehiculo->marca' title='Imagen de $->marca'/></td>";
                        echo "<td>$vehiculo->marca</td>";
                        echo "<td>$vehiculo->modelo</td>";
                        echo "<td>$vehiculo->color</td>";
                        echo "<td class='foto'><a href='index.php?controlador=Vehiculo&operacion=ver&parametro=$->id'><img class='boton' src='images/buttons/view.png' alt='ver detalles' title='ver detalles'/></a></td>";
                        echo "<td class='foto'><a href='index.php?controlador=Vehiculo&operacion=editar&parametro=$vehiculo->id'><img class='boton' src='images/buttons/edit.png' alt='editar vehiculo' title='editar vehiculo'/></a></td>";
                        echo "<td class='foto'><a href='index.php?controlador=Vehiculo&operacion=borrar&parametro=$vehiculo->id'><img class='boton' src='images/buttons/delete.png' alt='ver detalles' title='ver detalles'/></a></td>";
                        
                    echo "</tr>";
                }
                ?>
            </table>
            <p>Viendo la página <?php echo $paginaActual.' de '.$paginas; ?> páginas de resultados</p>
            <ul class="paginacion">
                <?php
                    //poner enlace a la página anterior
                    if($paginaActual>1){
                        echo "<li><a href='index.php?controlador=Vehiculo&operacion=listar&parametro=1'>Primera</a></li>";
                    }
                
                    //poner enlace a la página anterior
                    if($paginaActual>2){
                        echo "<li><a href='index.php?controlador=Vehiculo&operacion=listar&parametro=".($paginaActual-1)."'>Anterior</a></li>";
                    }
                    //poner enlace a la página siguiente
                    if($paginaActual<$paginas-1){
                        echo "<li><a href='index.php?controlador=Vehiculo&operacion=listar&parametro=".($paginaActual+1)."'>Siguiente</a></li>";
                    }
                    
                    //Poner enlace a la última página
                    if($paginas>1 && $paginaActual<$paginas){
                        echo "<li><a href='index.php?controlador=Vehiculo&operacion=listar&parametro=$paginas'>Ultima</a></li>";
                    }
                ?>
            </ul>
            
            
            
            <!--<p class="volver" onclick="history.back();">Atrás</p>-->
        
        </section>


    		
		
		<?php Template::footer();?>
    </body>
</html>