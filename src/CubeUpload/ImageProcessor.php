<?php namespace CubeUpload;

    use \SplFileInfo;

    class ImageProcessor
    {
        private $fileInfo;
        private $handler;

        public static $handlers = [
            'jpg'   => 'CubeUpload\Handlers\JpgImageHandler',
            'jpeg'  => 'CubeUpload\Handlers\JpgImageHandler',
            'png'   => 'CubeUpload\Handlers\PngImageHandler',
            'gif'   => 'CubeUpload\Handlers\GifImageHandler',
            'bmp'   => 'CubeUpload\Handlers\BmpImageHandler',
            'tif'   => 'CubeUpload\Handlers\TifImageHandler',
            'pdf'   => 'CubeUpload\Handlers\PdfImageHandler'
        ]; 

        public function load($path)
        {
            if( file_exists( $path ) )
            {
                $this->fileInfo = new SplFileInfo($path);
                $this->loadHandler();
            }
            else
                throw new \Exception( "File doesn't exist" );
        }
        
        private function loadHandler()
        {
            $extn = strtolower( $this->fileInfo->getExtension() );
            $class = "";

            if( array_key_exists( $extn, self::$handlers) )
            {
                $class = self::$handlers[$extn];
                $this->handler = new $class();
            }
            else {
                throw new \Exception( "File extension {$extn} not supported");
            }
        }
        
        public function getHandler()
        {
            return $this->handler;
        }
        
        public function getFilename()
        {
            return $this->fileInfo->getFilename();
        }
        
        public function getSplFileInfo()
        {
            return $this->fileInfo;
        }
        
        public function getMagicBytes()
        {
            return $this->handler->getMagicBytes();
        }

        public function isValid()
        {
            $this->handler->open( $this->fileInfo->getPathname() );
            $valid = $this->handler->valid();
            $this->handler->close(); 
            return $valid;
        }

        public function process()
        {

        }
    }