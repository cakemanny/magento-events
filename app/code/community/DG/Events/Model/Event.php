<?php
/**
 * Event Model
 *
 * @method integer getEventId()
 * @method string getTitle()
 * @method string getDescription()
 * @method string getDestination()
 * @method string getDate() date in YYYY-MM-DD format
 * @method string getEnddate() date in YYYY-MM-DD format
 * @method string getImage()
 * @method string getStore() serialised array of storeids
 * @method boolean getDisabled()
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

    /**
     * Returns the names of the stores for the event as an array
     *
     * @return array
     */
    public function getStoreNames() {
        $stores = unserialize($this->getStore());

        $storenames = array();
        foreach ($stores as $store) {
            $storenames[] = Mage::helper('events')->storeIdToName($store);
        }

        return $storenames;
    }

    /**
     * Constructs the URL to the event's image by adding the base URL onto the
     * image filename stored in the database.
     *
     * @return string
     */
    public function getImageUrl() {
        return Mage::helper('events/image')->getBaseUrl() . '/' . $this->getImage();
    }

    /**
     * Returns the event date formatted int the way that you would like.
     * By default 2-digit day
     *
     * @param string $format
     * @return string
     */
    public function getFormattedDate($format = null) {

        if (is_null($format)) {
            $format = 'd M';
        }
        $formatted = date($format, strtotime($this->getDate()));
        return $formatted;
    }

    /**
     * Returns the event date formatted int the way that you would like.
     * By default 2-digit day
     *
     * @param string $format
     * @return string|null
     */
    public function getFormattedEndDate($format = null) {
        if (is_null($this->getEnddate()))
            return null;

        if (is_null($format)) {
            $format = 'd M';
        }
        $formatted = date($format, strtotime($this->getEnddate()));
        return $formatted;
    }

}
