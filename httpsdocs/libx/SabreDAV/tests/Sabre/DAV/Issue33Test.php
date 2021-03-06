<?php

namespace Sabre\DAV;

use Sabre\HTTP;

require_once 'Sabre/TestUtil.php';

class Issue33Test extends \PHPUnit_Framework_TestCase {

    function setUp() {

        \Sabre\TestUtil::clearTempDir();

    }

    function testCopyMoveInfo() {

        $bar = new SimpleCollection('bar');
        $root = new SimpleCollection('webdav',array($bar));

        $server = new Server($root);
        $server->setBaseUri('/webdav/');

        $serverVars = array(
            'REQUEST_URI' => '/webdav/bar',
            'HTTP_DESTINATION' => 'http://dev2.tribalos.com/webdav/%C3%A0fo%C3%B3',
            'HTTP_OVERWRITE' => 'F',
        );

        $request = new HTTP\Request($serverVars);

        $server->httpRequest = $request;

        $info = $server->getCopyAndMoveInfo();

        $this->assertEquals('%C3%A0fo%C3%B3', urlencode($info['destination']));
        $this->assertFalse($info['destinationExists']);
        $this->assertFalse($info['destinationNode']);

    }

    function testTreeMove() {

        mkdir(SABRE_TEMPDIR . '/issue33');
        $dir = new FS\Directory(SABRE_TEMPDIR . '/issue33');

        $dir->createDirectory('bar');

        $tree = new ObjectTree($dir);
        $tree->move('bar',urldecode('%C3%A0fo%C3%B3'));

        $node = $tree->getNodeForPath(urldecode('%C3%A0fo%C3%B3'));
        $this->assertEquals(urldecode('%C3%A0fo%C3%B3'),$node->getName());

    }

    function testDirName() {

        $dirname1 = 'bar';
        $dirname2 = urlencode('%C3%A0fo%C3%B3');;

        $this->assertTrue(dirname($dirname1)==dirname($dirname2));

    }

    /**
     * @depends testTreeMove
     * @depends testCopyMoveInfo
     */
    function testEverything() {

        // Request object
        $serverVars = array(
            'REQUEST_METHOD' => 'MOVE',
            'REQUEST_URI' => '/webdav/bar',
            'HTTP_DESTINATION' => 'http://dev2.tribalos.com/webdav/%C3%A0fo%C3%B3',
            'HTTP_OVERWRITE' => 'F',
        );

        $request = new HTTP\Request($serverVars);
        $request->setBody('');

        $response = new HTTP\ResponseMock();

        // Server setup
        mkdir(SABRE_TEMPDIR . '/issue33');
        $dir = new FS\Directory(SABRE_TEMPDIR . '/issue33');

        $dir->createDirectory('bar');

        $tree = new ObjectTree($dir);

        $server = new Server($tree);
        $server->setBaseUri('/webdav/');

        $server->httpRequest = $request;
        $server->httpResponse = $response;
        $server->exec();

        $this->assertTrue(file_exists(SABRE_TEMPDIR  . '/issue33/' . urldecode('%C3%A0fo%C3%B3')));

    }

}
