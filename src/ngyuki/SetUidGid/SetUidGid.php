<?php
/**
 * SetUidGid
 *
 * @copyright 2012 tsyk goto
 * @author    tsyk goto
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 */

namespace ngyuki\SetUidGid;

/**
 * setuidgid
 *
 * @copyright 2012 tsyk goto
 * @author    tsyk goto
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 */
class SetUidGid
{
    public static function main($argc, $argv)
    {
        try
        {
            if ($argc <= 2)
            {
                $name = basename(__FILE__);
                throw new \RuntimeException("Usage: php $name <user> <script.php>");
            }
            
            list (, $user, $script) =  $argv;
            
            self::setuidgid($user);
            
            require $script;
            
            return 0;
        }
        catch (\Exception $ex)
        {
            if (defined('STDERR') && is_resource(STDERR))
            {
                fputs(STDERR, $ex->getMessage() . PHP_EOL);
            }
            else
            {
                echo $ex->getMessage() . PHP_EOL;
            }
            
            return -1;
        }
    }
    
    public static function setuidgid($user)
    {
        $uid = posix_getuid();
        
        if ($uid !== 0)
        {
            throw new \RuntimeException("setuidgid is only root");
        }
        
        $nam = posix_getpwnam($user);
        
        if (!$nam)
        {
            throw new \RuntimeException("unkonwn user \"$user\"");
        }
        
        $uid = $nam['uid'];
        $gid = $nam['gid'];
        
        if (!posix_setgid($gid))
        {
            throw new \RuntimeException("unable setgid($gid)");
        }
        
        if (!posix_setegid($gid))
        {
            throw new \RuntimeException("unable setegid($gid)");
        }
        
        if (!posix_setuid($uid))
        {
            throw new \RuntimeException("unable setuid($uid)");
        }
        
        if (!posix_seteuid($uid))
        {
            throw new \RuntimeException("unable seteuid($uid)");
        }
    }
}
