<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.06.14
 * Time: 15:46
 * To change this template use File | Settings | File Templates.
 */

include_once(__DIR__.'/../RemoteImageDownload.php');

class CoreTest extends  PHPUnit_Framework_TestCase{

    private $_targetObject;


    public function setUp()
    {
        $this->_targetObject = new RemoteImageDownload();
    }

    public function testIsImageDownloaded()
    {
        $url = 'http://platforma24.eu/media/products/42/order_firefox_error-s.png';
        $baseDir = __DIR__.'/../files/';

        $this->_targetObject->setDestinationDirBase($baseDir);
        $localFileName = $this->_targetObject->downloadImage($url,true,true);

        $this->assertTrue(file_exists($localFileName));
    }

    public function testIsImageSpaceInUrlDownloaded()
    {
        $url = 'http://platforma24.eu/media/products/58/F00000198-Umyagchenie zlyx serdec-s.jpg';
        $baseDir = __DIR__.'/../files/';

        $this->_targetObject->setDestinationDirBase($baseDir);
        $localFileName = $this->_targetObject->downloadImage($url,true,true);

        $this->assertTrue(file_exists($localFileName));
    }
}