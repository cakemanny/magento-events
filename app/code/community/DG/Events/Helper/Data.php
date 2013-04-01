<?php
/**
 * News Admin helper
 *
 * @author Daniel Golding
 */
class DG_Events_Helper_Data extends Mage_Core_Helper_Data {
    
    const XML_PATH_ENABLED = 'events/view/enabled';

    // TODO change stores into a Model with a table in the database so that 
    // stores can be added and removed as the business changes (slash so this
    // can be used by other companies
    protected $stores = array('Torquay','Harrogate', 'Wilmslow', 'Tunbridge Wells');

    /**
     * Checks whether events should be displayed in the frontend
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }

    /**
     * return an array of the stores names
     *
     * @return array
     */
    public function getStores() {
        return $stores;
    }

    /**
     * Returns the current month as a 2-digit number string eg if in april
     * then "04"
     *
     * @return string 2-digit month
     */
    public function getCurrentMonth() {
        return date('m');
    }

    /**
     * Returns the current month as a 4-digit string e.g. '2013'
     *
     * @return string 4-digit year
     */
    public function getCurrentYear() {
        return date('Y');
    }

    /**
     * Return the full name of the month from its digits e.g. monthName('04')
     * = April
     *
     * string|intger
     * @return string
     */
    public function monthName($month) {
        return date('F', mktime(0,0,0, $month,1));
    }

    /**
     * Returns the name of the store given its id#
     *
     * @param integer|string $storeid
     * @return string
     */
    public function storeIdToName($storeid) {
        if (array_key_exists($storeid, $this->stores)) {
            return $this->stores[$storeid];
        } else {
            return '';
        }
    }
}
