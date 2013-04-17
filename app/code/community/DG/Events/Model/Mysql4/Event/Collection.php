<?php
/**
 * Event Collection resource
 */
class DG_Events_Model_Mysql4_Event_Collection extends
        Mage_Core_Model_Mysql4_Collection_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('events/event');
    }

    /**
     * Filters the Events collection to just get the month we are looking at
     *
     * @param string $month the month number as a string e.g. for april $month='04'
     * @param string $year year as 4-digits e.g. 2013
     * @return DG_Events_Model_Mysql4_Event_Collection
     */
    public function addMonthFilter($month, $year) {

        $nextmonth = $month + 1;
        $firstdayofmonth = $year.'-'.$month.'-01' ;
        $firstdayofnextmonth = $year.'-'.$nextmonth.'-01';

        $yesend = sprintf('(enddate IS NOT NULL AND enddate >= %s) '
            .'OR date >= %s',
            $this->getConnection()->quote($firstdayofmonth),
            $this->getConnection()->quote($firstdayofmonth)
        );
        $this->getSelect()
            ->where('date < ?', $firstdayofnextmonth)
            ->where($yesend);

        return $this;
    }

    /**
     * Filters the event by the store number defined in the helper module
     *
     * @param integer $storeid
     * @return DG_Events_Model_Resource_Event_Collection
     */
    public function addStoreFilter($storeid) {
        if (is_null($storeid))
            return $this;

        $storeid = intval($storeid);
        if ($storeid >= 0 && $storeid < count(Mage::helper('events')->getStores())) {
            $wherestring = sprintf('main_table.store LIKE %s',
                    $this->getConnection()->quote('%"'.$storeid.'"%'));
            $this->getSelect()->where($wherestring);
        }
        return $this;
    }

}
