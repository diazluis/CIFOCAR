<?php
    require '../../config/Config.php';
    require '../../libraries/database_library.php';
    require '../VehiculoModelA.php';
    
    //TEST GUARDADO
    VehiculoModelA::guardar('34565', 'Auris', 'Azul', '10000', '8000', '9000', '177', '2017', 'Perfecto', '2016', 'Perfecto','Luis', 'Toyota');

    //TEST RECUPERAR
    //var_dump(VehiculoModelA::getVehiculos());
    
    $vehiculos = VehiculoModelA::getVehiculo(10,0,'','DESC');
    
    foreach($vehiculos as $m){
        echo "<p>$m->vehiculo</p>";
    }
    
    //TEST ACTUALIZAR
    //echo VehiculoModelA::actualizar('Citroen','citroen');
    
    
    //TEST BORRAR
    //echo VehiculoModelA::borrar('fiat');
    
    
?>