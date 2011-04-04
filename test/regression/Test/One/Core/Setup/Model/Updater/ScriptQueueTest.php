<?php

class Test_One_Core_Setup_Model_Updater_ScriptQueueTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('setup'));
    }

    public function testInstallerAlone()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.1.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testMultipleInstallerUseLowest()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('install-0.2.0.php'));
        $root->addChild(new vfsStreamFile('install-0.3.0.php'));
        $root->addChild(new vfsStreamFile('install-0.4.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.1.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testMultipleInstallerUseHighest()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('install-0.2.0.php'));
        $root->addChild(new vfsStreamFile('install-0.3.0.php'));
        $root->addChild(new vfsStreamFile('install-0.4.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.4.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.4.0.php'),
            'version' => array(
                'version' => '0.4.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testMultipleInstallerUseIntermediate()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('install-0.2.0.php'));
        $root->addChild(new vfsStreamFile('install-0.3.0.php'));
        $root->addChild(new vfsStreamFile('install-0.4.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.3.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.3.0.php'),
            'version' => array(
                'version' => '0.3.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testInstallerWithSingleUpgrade()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.0-0.2.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.2.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.1.0-0.2.0.php'),
            'version' => array(
                'version' => '0.2.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testInstallerWithMultipleUpgrade()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.0-0.1.1.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.1-0.1.2.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.2-0.2.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.2.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.1.0-0.1.1.php'),
            'version' => array(
                'version' => '0.1.1',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.1.1-0.1.2.php'),
            'version' => array(
                'version' => '0.1.2',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.1.2-0.2.0.php'),
            'version' => array(
                'version' => '0.2.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testInstallerWithMultipleUpgradeSolutionsChosingBiggestPaths()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('install-0.1.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.0-0.1.1.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.1-0.1.2.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.2-0.2.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.2.0-0.2.1.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.2.1-0.3.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.1.0-0.2.0.php'));
        $root->addChild(new vfsStreamFile('upgrade-0.2.0-0.3.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), null, '0.3.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\install-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.1.0-0.2.0.php'),
            'version' => array(
                'version' => '0.2.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $scriptQueue->next();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\upgrade-0.2.0-0.3.0.php'),
            'version' => array(
                'version' => '0.3.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct upgrade file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testUninstallerAlone()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('uninstall-0.1.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), '0.1.0', '0.0.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\uninstall-0.1.0.php'),
            'version' => array(
                'version' => '0.1.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }

    public function testMultipleUninstallerUseLowest()
    {
        $root = vfsStreamWrapper::getRoot();
        $root->addChild(new vfsStreamFile('uninstall-0.1.0.php'));
        $root->addChild(new vfsStreamFile('uninstall-0.2.0.php'));
        $root->addChild(new vfsStreamFile('uninstall-0.3.0.php'));
        $root->addChild(new vfsStreamFile('uninstall-0.4.0.php'));

        $scriptQueue = new One_Core_Setup_Model_Updater_ScriptQueue(vfsStream::url('setup'), '0.1.0', '0.0.0');

        $scriptQueue->rewind();
        $this->assertEquals(array(
            'script' => vfsStream::url('setup\\uninstall-0.4.0.php'),
            'version' => array(
                'version' => '0.4.0',
                'stage'   => 'stable',
                'level'   => 0
                )
            ), $scriptQueue->current(), 'The script queue did not find the correct installer file.');

        $this->assertNull($scriptQueue->next(), 'The script queue found more files than it should');
    }
}