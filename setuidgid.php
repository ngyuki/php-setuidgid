<?php
/*
 * example)
 *   sudo php setuidgid.php apache test.php
 *
 */

try
{
    if ($argc <= 2)
    {
        $name = basename(__FILE__);
        throw new RuntimeException("Usage: php $name <user> <script.php>");
    }
    
    list (, $user, $script) =  $argv;
    
    call_user_func(function() {
        
        global $user;
        
        $uid = posix_getuid();
        
        if ($uid !== 0)
        {
            throw new RuntimeException("setuidgid is only root");
        }
        
        $nam = posix_getpwnam($user);
        
        if (!$nam)
        {
            throw new RuntimeException("unkonwn user \"$user\"");
        }
        
        $uid = $nam['uid'];
        $gid = $nam['gid'];
        
        if (!posix_setgid($gid))
        {
            throw new RuntimeException("unable setgid($gid)");
        }
        
        if (!posix_setegid($gid))
        {
            throw new RuntimeException("unable setegid($gid)");
        }
        
        if (!posix_setuid($uid))
        {
            throw new RuntimeException("unable setuid($uid)");
        }
        
        if (!posix_seteuid($uid))
        {
            throw new RuntimeException("unable seteuid($uid)");
        }
    });
    
    require_once $script;
}
catch (Exception $ex)
{
    fputs(STDERR, $ex->getMessage() . PHP_EOL);
    exit(-1);
}
