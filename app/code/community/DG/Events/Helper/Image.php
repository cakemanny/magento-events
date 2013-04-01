<?php
/**
 * Events Helper to deal with the event associated
 *
 * @author Daniel Golding
 */
class DG_Events_Helper_Image extends Mage_Core_Helper_Abstract {
    /**
     * Media path to images
     *
     * @var string
     */
    const MEDIA_PATH = 'events';

    /**
     * Maximum size for images in bytes
     * Default value is 1M
     *
     * @var int
     */
    const MAX_FILE_SIZE = 1048576;

    /**
     * Minimum image height in pixels
     *
     * @var int
     */
    const MIN_HEIGHT = 50;

    /**
     * Maximum image Height in pixels
     *
     * @var int
     */
    const MAX_HEIGHT = 300;

    /**
     * Minimum image width in pixels
     *
     * @var int
     */
    const MIN_WIDTH = 50;

    /**
     * Max image width in pixels
     *
     * @var int
     */
    const MAX_WIDTH = 300;

    /**
     * Array of image size limitations
     *
     * @var array
     */
    protected $_imageSize = array(
        'minheight'     => self::MIN_HEIGHT,
        'minwidth'      => self::MIN_WIDTH,
        'maxheight'     => self::MAX_WIDTH,
        'maxwidth'      => self::MAX_HEIGHT,
    );

    /**
     * Array of allowed file extensions
     *
     * @var array
     */
    protected $_allowedExtensions = array('jpg', 'gif', 'png');

    /**
     * Return the base media directory for Event images
     *
     * @return string
     */
    public function getBaseDir() {
        return Mage::getBaseDir('media') . DS . self::MEDIA_PATH;
    }

    /**
     * Return the Base URL for Event images
     *
     * @return string
     */
    public function getBaseUrl() {
        return Mage::getBaseUrl('media') . '/' . self::MEDIA_PATH;
    }

    /**
     * Remove image by image filename
     *
     * @param string $imageFile
     * @return bool
     */
    public function removeImage($imageFile) {
        $io = new Varien_Io_File();
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }

    /**
     * Upload image and return uploaded image file name or (if fails) false
     *
     * @throws Mage_Core_Exception
     * @param string $scope the request key for file
     * @return bool|string
     */
    public function uploadImage($scope) {

        $adapter = new Zend_File_Transfer_Adapter_Http();
        $adapter->addValidator('ImageSize', true, $this->_imageSize);
        $adapter->addValidator('Size', true, self::MAX_FILE_SIZE);
        if ($adapter->isUploaded($scope)) {
            // validate image
            if (!$adapter->isValid($scope)) {
                Mage::throwException(Mage::helper('events')
                    ->__('Uploaded image is not valid'));
            }
            $upload = new Varien_File_Uploaded($scope);
            $upload->setAllowCreateFolders(true);
            $upload->setAllowedExtensions($this->_allowedExtensions);
            $upload->setAllowRenameFiles(true);
            $upload->setFilesDispersion(false);
            if ($upload->save($this->getBaseDir())) {
                return $upload->getUploadedFileName();
            }
        }
        return false;
    }

    /**
     * Return URL for resized image
     *
     * @param DG_Events_Model_Event $event
     * @param integer $width
     * @param integer $height
     * @return bool|string
     */
    public function resize(DG_Events_Model_Event $event,
            $width, $height=null) {
        // TODO implement this and continue from here
    }

    /**
     * Removes a folder with cached images
     *
     * @return boolean
     */
    public function flushImagesCache() {

        $cacheDir = $this->getBaseDir() . DS . 'cache' . DS;
        $io = new Varien_Io_File();
        if ($io->fileExists($cacheDir, false)) {
            return $io->rmdir($cacheDir, true);
        }
        return true;
    }
}


            

