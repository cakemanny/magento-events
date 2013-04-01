<?php
/**
 * Event Model
 *
 * @author Daniel Golding
 */
class DG_Events_Model_Event extends Mage_Core_Model_Abstract {
    
    /**
     * Initiliase the model as type events/event
     */
    protected function _construct() {
        $this->_init('events/event');
    }

    public function getStoreNames() {
        $stores = unserialize($this->getStore());

        $storenames = array();
        foreach ($stores as $store) {
            $storenames[] = Mage::helper('events')->storeIdToName($store);
        }

        return $storenames;
    }
}
