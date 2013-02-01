<?php
foreach (array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
) as $fn)
{
    if (file_exists($fn))
    {
        require $fn;
        break;
    }
}

exit(ngyuki\SetUidGid\SetUidGid::main($argc, $argv));
