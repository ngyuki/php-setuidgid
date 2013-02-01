<?php
namespace ngyuki\Tests;

use ngyuki\SetUidGid\SetUidGid;
  
/**
 *
 * @runTestsInSeparateProcesses
 *
 */
class SetUidGidTest extends \PHPUnit_Framework_TestCase
{
    private $user;
    private $gid;
    private $uid;
    private $none;
    
    protected function setUp()
    {
        $this->user = getenv('PHPUNIT_USER');
        $this->uid  = getenv('PHPUNIT_UID');
        $this->gid  = getenv('PHPUNIT_GID');
        $this->none = getenv('PHPUNIT_NONE');
    }
    
    /**
     * @test
     * @outputBuffering enabled
     */
    public function main()
    {
        $this->assertNotEquals($this->gid, posix_getgid());
        $this->assertNotEquals($this->gid, posix_getegid());
        $this->assertNotEquals($this->uid, posix_getuid());
        $this->assertNotEquals($this->uid, posix_geteuid());
        
        $script = sys_get_temp_dir() . '/dummy.php';
        
        @unlink("$script");
        @unlink("$script.txt");
        
        file_put_contents($script, "<?php echo 'sdfdaigangera'; touch(__FILE__ . '.txt'); ");
        
        $argv = array(__FILE__, $this->user, $script);
        $ret = SetUidGid::main(count($argv), $argv);
        $this->assertSame(0, $ret);
        
        $this->assertEquals($this->gid, posix_getgid());
        $this->assertEquals($this->gid, posix_getegid());
        $this->assertEquals($this->uid, posix_getuid());
        $this->assertEquals($this->uid, posix_geteuid());
        
        $this->assertEquals("sdfdaigangera", ob_get_contents());
        
        $stat = stat("$script.txt");
        $this->assertEquals($this->uid, $stat['uid']);
        $this->assertEquals($this->gid, $stat['gid']);
    }
    
    /**
     * @test
     * @outputBuffering enabled
     */
    public function main_error()
    {
        $argv = array(__FILE__, $this->user);
        $ret = SetUidGid::main(count($argv), $argv);
        $this->assertSame(-1, $ret);
        $this->assertEquals("Usage: php SetUidGid.php <user> <script.php>\n", ob_get_contents());
    }
    
    /**
     * @test
     */
    public function setuidgid()
    {
        $this->assertNotEquals($this->gid, posix_getgid());
        $this->assertNotEquals($this->gid, posix_getegid());
        $this->assertNotEquals($this->uid, posix_getuid());
        $this->assertNotEquals($this->uid, posix_geteuid());
        
        SetUidGid::setuidgid($this->user);
        
        $this->assertEquals($this->gid, posix_getgid());
        $this->assertEquals($this->gid, posix_getegid());
        $this->assertEquals($this->uid, posix_getuid());
        $this->assertEquals($this->uid, posix_geteuid());
    }
    
    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage setuidgid is only root
     */
    public function setuidgid_no_root()
    {
        $this->assertNotEquals($this->gid, posix_getgid());
        $this->assertNotEquals($this->gid, posix_getegid());
        $this->assertNotEquals($this->uid, posix_getuid());
        $this->assertNotEquals($this->uid, posix_geteuid());
        
        SetUidGid::setuidgid($this->user);
        
        $this->assertEquals($this->gid, posix_getgid());
        $this->assertEquals($this->gid, posix_getegid());
        $this->assertEquals($this->uid, posix_getuid());
        $this->assertEquals($this->uid, posix_geteuid());
        
        SetUidGid::setuidgid($this->user);
    }
    
    /**
     * @test
     */
    public function setuidgid_unkonwn_user()
    {
        $this->assertNotEquals($this->gid, posix_getgid());
        $this->assertNotEquals($this->gid, posix_getegid());
        $this->assertNotEquals($this->uid, posix_getuid());
        $this->assertNotEquals($this->uid, posix_geteuid());
        
        $this->setExpectedException("RuntimeException", "unkonwn user \"$this->none\"");
        
        SetUidGid::setuidgid($this->none);
    }
}
