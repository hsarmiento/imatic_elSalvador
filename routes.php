<?php

defined("ROOT")  
    or define("ROOT", dirname(__FILE__).'/');

defined("MODELS")  
    or define("MODELS", ROOT.'models/');

defined("VIEWS")  
    or define("VIEWS", ROOT.'views/');

defined("CONTROLLERS")  
    or define("CONTROLLERS", ROOT.'controllers/');

defined("CONFIG")  
    or define("CONFIG", ROOT.'config/');

defined("RESOURCES")  
    or define("RESOURCES", 'assets/');

$aRoutes = array(
    'urls' => array(  
        'root' => ROOT,        
    ),  
    'paths' => array(
        'models' => array(
            'usuario' => MODELS.'bs_usuario.php'
        ),
        'controllers' => array(
            'usuario' => CONTROLLERS.'bs_usuario_controller.php'
        ),
        'layout' => array(
            'header' => VIEWS.'layout/header.php',
            'footer' => VIEWS.'layout/footer.php'
        ),
        'config' => CONFIG,
        'images' => array(
            'base' => RESOURCES . 'images/',            
        ),
        'js' => RESOURCES . 'js/',
        'css' => RESOURCES . 'css/',
    ),    
    'return' => array(        
        '1' => '/../',
        '2' => '/../../',
    )    
);

?>