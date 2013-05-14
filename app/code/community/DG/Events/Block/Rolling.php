<?php
/**
 * Block displaying all the events for that month
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Rolling extends Mage_Core_Block_Template {

    protected $_eventCollection = null;

    protected function _prepareLayout() {
        // Add our breadcrumbs to to the top of the page
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => Mage::helper('events')->__('Home'),
                'title' => Mage::helper('events')->__('Go to Home Page'),
                'link' => Mage::getBaseUrl(),
            ));
            $breadcrumbs->addCrumb('events', array(
                'label' => Mage::helper('events')->__('Events'),
                'title' => Mage::helper('events')->__('See Events in All Stores'),
                'link' => $this->getUrl('events'),
            ));
            if ($storename = $this->getStoreName()) {
                $params = array('store' => $this->getStoreId());
                $breadcrumbs->addCrumb('store', array(
                    'label' => Mage::helper('events')->__($storename),
                    'title' => Mage::helper('events')->__($storename),
                    'link' => $this->getUrl('*/*/', $params),
                ));
            }
        }
    }

    /**
     * Returns the name of the current month being viewed
     *
     * @return string
     */
    public function getMonthName() {
        return Mage::helper('events')->monthName($this->getMonth());
    }

    /**
     * Returns the month that was requested, or the current month if none was
     *
     * @return string
     */
    public function getMonth() {
        if ($this->getRequest()->getParam('month')) {
            return $this->getRequest()->getParam('month');
        } else {
            return Mage::helper('events')->getCurrentMonth();
        }
    }

    /**
     * Returns the year that was requested, or the current year if none was
     *
     * @return string
     */
    public function getYear() {
        if ($this->getRequest()->getParam('year')) {
            return $this->getRequest()->getParam('year');
        } else {
            return Mage::helper('events')->getCurrentYear();
        }
    }

    /**
     * @return DG_Events_Model_Resource_Event_Collection
     */
    protected function _getCollection() {
        return Mage::getModel('events/event')->getCollection();
    }
    /**
     * Returns the prepared collection of events for the particular month to be
     * rendered.
     *
     * @return DG_Events_Model_Resource_Event_Collection
     */
    public function getEvents($days = 30) {
        $month = $this->getMonth();
        $year = $this->getYear();
        $storeid = $this->getRequest()->getParam('store');

        if (is_null($this->_eventCollection)) {
            $this->_eventCollection = $this->_getCollection();
        }

        $collection = $this->_eventCollection
                ->addUpcomingEventsFilter($days)
                ->addStoreFilter($storeid)
                ->setOrder('date', 'asc');

        return $collection;
    }

    /**
     * Returns the URL for the next calendar month's events
     *
     * @return string
     */
    public function getNextMonthURL() {
        // if we are on december we need to increment year
        $month = $this->getMonth();
        $year = $this->getYear();
        if (intval($month) == 12) {
            $year += 1;
            $month = '01';
        } else {
            $month += 1;
        }

        $params = array('_current' => true, 'month' => $month,
                'year' => $year,);

        if ($storeid = $this->getStoreId()) {
            $params['store'] = $storeid;
        }

        return Mage::getUrl('*/*/', $params);
    }

    /**
     * Returns the URL for the previous calendar month's events
     *
     * @return string
     */
    public function getPrevMonthURL() {
        // if we are on January we need to decrement year
        $month = $this->getMonth();
        $year = $this->getYear();
        if (intval($month) == 1) {
            $year -= 1;
            $month = '12';
        } else {
            $month -= 1;
        }

        $params = array('_current' => true, 'month' => $month,
                'year' => $year,);

        if ($storeid = $this->getStoreId()) {
            $params['store'] = $storeid;
        }

        return Mage::getUrl('*/*/', $params);
    }

    /**
     * Returns array of '<a>StoreName</a>' s. We return an array rather than an
     * unordered list.
     *
     * @return array
     */
    public function getStoreLinks() {
        $stores = Mage::helper('events')->getStores();

        $params = array('_current' => true, 'month' => $this->getMonth(),
                'year' => $this->getYear(),);

        $current_store = $this->getRequest()->getParam('store');

        $output = array();
        foreach ($stores as $storeid => $store) {
            $link = Mage::getUrl('*/*/', array_merge(
                    $params, array('store' => $storeid)));
            if (!is_null($current_store) && $current_store == $storeid)
                $store = '<em>'.$store.'</em>';

            $output[] = sprintf('<a href="%s">%s</a>',$link, $store);
        } unset($store, $storeid);

        return $output;
    }

    /**
     * Save some typing; quick access to the storeid
     *
     * @return string|integer|null
     */
    public function getStoreId() {
        return $this->getRequest()->getParam('store');
    }

    /**
     * Save some typing; quick access to the storeid
     *
     * @return string|null
     */
    public function getStoreName() {
        if (!is_null($storeid = $this->getStoreId())) {
            $stores = Mage::helper('events')->getStores();
            return $stores[$storeid];
        }
        return false;
    }

    /**
     * Returns a link to get us back to all stores events. Text is "All Stores"
     * by default
     *
     * @param string $text text to go in the link if you don't want "All Stores"
     * @return string
     */
    public function getAllStoresLink($text = null) {
        $params = array('month' => $this->getMonth(),
                'year' => $this->getYear(), 'store' => '-1');
        $link = Mage::getUrl('*/*/', $params);

        if (is_null($text))
            $text = 'All Stores';
        if ($this->getStoreId() < 0 ||
                $this->getStoreId() >= count(Mage::helper('events')->getStores()))
            $text = '<em>'.$text.'</em>';

        $output = sprintf('<a href="%s">%s</a>',$link, $text);

        return $output;
    }
}
