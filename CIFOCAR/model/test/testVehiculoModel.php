<?php
    require '../../config/Config.php';
    require '../../libraries/database_library.php';
    require '../VehiculoModel.php';
    
    //TEST GUARDADO
    VehiculoModel::guardar('34565', 'Auris', 'Azul', '10000', '8000', '9000', '177', '2017', 'Perfecto', '2016', 'Perfecto','Luis', 'Toyota');

    //TEST RECUPERAR
    //var_dump(VehiculoModel::getVehiculos());
    
    $vehiculos = VehiculoModel::getVehiculo(10,0,'','DESC');
    
    foreach($vehiculos as $m){
        echo "<p>$m->vehiculo</p>";
    }
    
    //TEST ACTUALIZAR
    //echo VehiculoModel::actualizar('Citroen','citroen');
    
    
    //TEST BORRAR
    //echo VehiculoModel::borrar('fiat');
    
    
?>