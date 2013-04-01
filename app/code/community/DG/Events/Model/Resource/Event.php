<?php
/**
 * Event Model Resource
 *
 * @author Daniel Golding
 */
class DG_Events_Model_Resource_Event extends
        Mage_Core_Model_Resource_Db_Abstract {
    
    protected function _construct() {
        $this->_init('events/event', 'event_id');
    }
}
