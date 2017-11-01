<?php
    require '../../config/Config.php';
    require '../../libraries/database_library.php';
    require '../MarcaModel.php';
    
    //TEST GUARDADO
    //MarcaModel::guardar('Toyota');

    //TEST RECUPERAR
    //var_dump(MarcaModel::getMarcas());
    
    $marcas = MarcaModel::getMarcas(10,0,'','DESC');
    
    foreach($marcas as $m){
        echo "<p>$m->marca</p>";
    }
    
    //TEST ACTUALIZAR
    //echo MarcaModel::actualizar('Citroën','citroen');
    
    
    //TEST BORRAR
    echo MarcaModel::borrar('fiat');
    
    
?>