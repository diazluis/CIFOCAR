<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo Config::get()->url_base;?>" />
		<meta charset="UTF-8">
		<title>Listado de marcas</title>
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
   <h2>Listado de marcas</h2>
   <p>Hay <?php echo $totalRegistros; ?> registros<?php echo $filtro? ' para el filtro indicado':'';?>.</p>
   <!--  <p>Mostrando del <?php echo ($paginaActual-1)*$regPorPagina+1;?> al <?php echo ($paginaActual)*$regPorPagina;?>.</p> -->
            
	<?php if(!$filtro){?>
   <form method="post" class="filtro" action="index.php?controlador=Marca&operacion=listar&parametro=1">
       <label>Filtro:</label>
        <input type="text" name="texto" placeholder="buscar..."/>
        <input type="submit" name="filtrar" value="Filtrar"/>
            </form>
            <?php }else{ ?>
                <form method="post" class="filtro" action="index.php?controlador=Marca&operacion=listar&parametro=1">
                    <label>Quitar filtro</label>
                    <input type="submit" name="quitarFiltro" value="Quitar" />
                </form>
            <?php }?>
            
            <table>
                <tr>
                    <th>Marca</th>
                    <th colspan="2">Acciones</th>
               </tr>
                <?php
                foreach($marcas as $marca){
                    echo "<tr>";
                        echo "<td>$marca->marca</td>";
                        echo "<td class='foto'><a href='index.php?controlador=Marca&operacion=editar&parametro=$marca->id'><img class='boton' src='images/buttons/edit.png' alt='modificar marca' title='modificar marca'/></a></td>";
                        echo "<td class='foto'><a href='index.php?controlador=Marca&operacion=borrar&parametro=$marca->id'><img class='boton' src='images/buttons/delete.png' alt='eliminar marca' title='eliminar marca'/></a></td>";
                        
                    echo "</tr>";
                }
                ?>
            </table>
            <p>Viendo la página <?php echo $paginaActual.' de '.$paginas; ?> páginas de resultados</p>
            <ul class="paginacion">
                <?php
                    //poner enlace a la página anterior
                    if($paginaActual>1){
                        echo "<li><a href='index.php?controlador=Marca&operacion=listar&parametro=1'>Primera</a></li>";
                    }
                
                    //poner enlace a la página anterior
                    if($paginaActual>2){
                        echo "<li><a href='index.php?controlador=Marca&operacion=listar&parametro=".($paginaActual-1)."'>Anterior</a></li>";
                    }
                    //poner enlace a la página siguiente
                    if($paginaActual<$paginas-1){
                        echo "<li><a href='index.php?controlador=Marca&operacion=listar&parametro=".($paginaActual+1)."'>Siguiente</a></li>";
                    }
                    
                    //Poner enlace a la última página
                    if($paginas>1 && $paginaActual<$paginas){
                        echo "<li><a href='index.php?controlador=Marca&operacion=listar&parametro=$paginas'>Última</a></li>";
                    }
                ?>
            </ul>
            
            
            
            <!--<p class="volver" onclick="history.back();">Atrás</p>-->
        
        </section>


    		
		
		<?php Template::footer();?>
    </body>
</html>