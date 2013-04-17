<?php
/**
 * The actual grid with the listing for all the events in
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Adminhtml_Events_Grid extends
        Mage_Adminhtml_Block_Widget_Grid {

    /**
     * Initialise default properties
     */
    public function __construct() {
        parent::__construct();
        $this->setId('events_grid');
        $this->setDefaultSort('date');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return DG_Events_Block_Adminhtml_Events_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('events/event')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Create the columns to appear on the manage events page
     */
    protected function _prepareColumns() {
        $this->addColumn('event_id', array(
            'header'    => Mage::helper('events')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'event_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('events')->__('Event Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('destination', array(
            'header'    => Mage::helper('events')->__('Destination Page'),
            'align'     => 'left',
            'index'     => 'destination',
        ));

        $this->addColumn('date', array(
            'header'    => Mage::helper('events')->__('Event Date'),
            'align'     => 'right',
            'sortable'  => true,
            'index'     => 'date',
            'type'      => 'date',
        ));

        $this->addColumn('enddate', array(
            'header'    => Mage::helper('events')->__('Event End Date'),
            'align'     => 'right',
            'sortable'  => true,
            'index'     => 'enddate',
            'type'      => 'date',
        ));


        $this->addColumn('action', array(
            'header'    => Mage::helper('events')->__('Action'),
            'align'     => 'left',
            'width'     => '100px',
            'getter'    => 'getEventId',
            'actions'   => array(
                array(
                'caption'   => Mage::helper('events')->__('Edit'),
                'url'       => array('base' => '*/*/edit'),
                'field'     => 'event_id',
                ),
                array(
                    'caption'   => Mage::helper('events')->__('Delete'),
                    'url'       => array('base' => '*/*/delete'),
                    'field'     => 'event_id',
                ),
            ),
            'sortable'  => false,
            'filter'    => false,
            'type'      => 'action',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('event_id' => $row->getEventId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}


