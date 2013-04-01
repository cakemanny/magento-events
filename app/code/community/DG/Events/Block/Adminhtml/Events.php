<?php
/**
 * The manage page for the events - displays a grid of all the events, allows
 * new ones to be created and existing ones to edited or deleted.
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Adminhtml_Events extends
        Mage_Adminhtml_Block_Widget_Grid_Container {
    
    public function __construct() {
        $this->_blockGroup = 'events';
        $this->_controller = 'adminhtml_events';
        $this->_headerText = Mage::helper('events')->__('Manage Events');
        parent::__construct();

        $this->_updateButton('add', 'label',
            Mage::helper('events')->__('Add New Event'));
    }
}
