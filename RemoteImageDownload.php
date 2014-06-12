<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.06.14
 * Time: 14:57
 * To change this template use File | Settings | File Templates.
 */

class RemoteImageDownload {

    private $_destinationDirBase = '';
    private $_returnedName = '';

    public function setDestinationDirBase($destinationDirBase)
    {
        $this->_destinationDirBase = $destinationDirBase;
    }

    public function getDestinationDirBase()
    {
        return $this->_destinationDirBase;
    }

    public function getReturnedName()
    {
        return $this->_returnedName;
    }



    public function downloadImage($url,$returnFullDir = true, $overrideImage = false, $forceSaveFormat = false, $destinationBaseUrl = false, $newFileName = false)
    {
        $image = file_get_contents(str_replace(' ', '%20', $url));

        if(!$image)
        {
            throw new Exception('Failed to download image @ '.$url);
        }

        if(!$forceSaveFormat)
        {
            $extension = $this->getExtension($url);
        }
        else
        {
            $extension = $forceSaveFormat;
        }


        if(!$extension)
        {
            throw new Exception('Failed to recognize extension @ '.$url);
        }

        $destination = $this->resolveImageName($url,$extension,$destinationBaseUrl,$newFileName,$returnFullDir);

        $result = $this->saveFile($image,$destination,$overrideImage);

        if(!$result)
        {
            throw new Exception('Failed to save file @ '.$url);
        }

        if(!$this->_returnedName)
        {
            throw new Exception('Failed to retrieve saved file name');
        }

        return $this->_returnedName;
    }

    public function getExtension($url)
    {
        $extension = false;

        if(stripos($url,'.jpg')!==false)
        {
            $extension = 'jpg';
        }
        elseif(stripos($url,'.jpeg')!==false)
        {
            $extension = 'jpg';
        }
        elseif(stripos($url,'.png')!==false)
        {
            $extension = 'png';
        }
        elseif(stripos($url,'.bmp')!==false)
        {
            $extension = 'bmp';
        }

        return $extension;
    }

    public function resolveImageName($url, $extension,$baseDir = false,$imageNewName = false,$returnFullDir = true)
    {
        if(!$baseDir)
        {
            $baseDir = $this->_destinationDirBase;
        }

        if(!$imageNewName)
        {
            $t = explode('/',$url);

            if(count($t) < 2)
            {
                $t = explode('\\',$url);

                if(count($t)<2)
                {
                    throw new Exception('Failed to find old file name @ '.$url);
                }
            }

            $lastPart = array_pop($t);
            $t2 = explode('.',$lastPart);
            $imageNewName = array_shift($t2);
        }

        if(!file_exists($baseDir))
        {
            mkdir($baseDir, 0777, true);
        }

        $destinationName = $baseDir.$imageNewName.'.'.$extension;

        if($returnFullDir)
        {
            $this->_returnedName = $destinationName;
        }
        else
        {
            $this->_returnedName = $imageNewName.'.'.$extension;
        }

        return $destinationName;


    }

    public function saveFile($image,$destinationFile,$overrideImage)
    {
        if(!$overrideImage && file_exists($destinationFile))
        {
            return true;
        }

        return file_put_contents($destinationFile,$image);

    }
}