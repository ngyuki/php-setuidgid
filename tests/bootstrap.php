<?php
//require_once 'vendor/autoload.php';

call_user_func(function () {
    
    $list = array(
        'ngyuki\\Tests\\' => __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR,
        'ngyuki\\' => __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
    );
    
    foreach ($list as $ns => $dir)
    {
        $ns = trim($ns, '\\') . '\\';
        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        spl_autoload_register(function ($klass) use ($ns, $dir) {
            
            if (strncmp($ns, $klass, strlen($ns)) === 0)
            {
                return include $dir . str_replace("\\", DIRECTORY_SEPARATOR, $klass) . '.php';
            }
            
            return false;
        });
    }
});
