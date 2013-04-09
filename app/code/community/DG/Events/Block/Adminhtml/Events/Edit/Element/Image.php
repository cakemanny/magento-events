<?php
/**
 * Our customised form image element. We override this so that it loads the
 * preview from the correct place i.e. the events image store in the media
 * folder.
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Adminhtml_News_Edit_Form_Element_Image extends
        Varien_Data_Form_Element_Image {
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl() {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('events/image')->getBaseUrl()
                . '/' . $this->getValue();
        }
        return $url;
    }
}