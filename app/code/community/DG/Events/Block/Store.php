<?php
/**
 * Block displaying the names of the stores and so linksto view only events
 * Relevant to that particular store
 */
class DG_Events_Block_Store extends Mage_Core_Block_Template {
    
    getStoreNames() {
        $stores = Mage::helper('events')->getStores();
    }
}
