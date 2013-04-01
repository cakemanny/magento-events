<?php
/**
 * Block displaying all the events for that month
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Month extends Mage_Core_Block_Template {
    
    protected $_eventCollection = null;
    
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
    public function getEvents() {
        $month = $this->getMonth();
        $year = $this->getYear();
        $storeid = $this->getRequest()->getParam('store');
        
        if (is_null($this->_eventCollection)) {
            $this->_eventCollection = $this->_getCollection();
        }
        
        $collection = $this->_eventCollection
                ->addMonthFilter($month, $year)
                ->setOrder('date', 'asc');

        // Only display the relevant stores information
        if (!is_null($storeid)) {
            $collection->addStoreFilter($storeid);
        }
        
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

        if ($storeid = $this->getRequest()->getParam('store')) {
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

        if ($storeid = $this->getRequest()->getParam('store')) {
            $params['store'] = $storeid;
        }

        return Mage::getUrl('*/*/', $params);
    }
    
}
