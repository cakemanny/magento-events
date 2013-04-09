<?php
/**
 * News Admin helper
 *
 * @author Daniel Golding
 */
class DG_Events_Helper_Data extends Mage_Core_Helper_Data {
    
    /**
     * Path to store config is module is displayed on the frontend
     *
     * @var string
     */
    const XML_PATH_ENABLED = 'events/view/enabled';

    // Has not yet been implemented in the module
    /**
     * Path to store config where the number of events are displayed on a page
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE = 'events/view/items_per_page';

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
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }

    /**
     * Returns the maximum number of events that should be displayed on a page
     *
     * @param integer|string|Mage_Core_Model_Store $store
     */
    public function getEventsPerPage($store=null) {
        return abs((int) Mage::getStoreConfig(self::XML_PATH_ITEMS_PER_PAGE,
            $store));
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
     * @param string|intger $month
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
    /**
     * Returns an array of arrays like
     * array(array('value'=0, 'label'='storename'), ...)
     * @return array
     */
    public function storesValueLabelArray() {
        $stores = $this->stores;
        $output = array();
        if (!is_null($stores)) {
            $i = 0;
            foreach ($stores as $store) {
                $output[] = array(
                    'value' => strval($i),
                    'label' => $this->__($store),
                );
                
                ++$i;
            }
        }
        return $output;
    }
}
