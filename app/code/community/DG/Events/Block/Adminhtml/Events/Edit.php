<?php
/**
 * Container for the Event edit pages
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Adminhtml_Events_Edit extends
        Mage_Adminhtml_Block_Widget_Form_Container {
    
    public function __construct() {
        $this->_objectId = 'event_id';
        $this->_blockGroup = 'events';
        $this->_controller = 'adminhtml_events';
        $this->_mode = 'edit';
        
        parent::__construct();
        
        $this->_updateButton('save', 'label',
            Mage::helper('events')->__('Save Event'));

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('events')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_updateButton('delete', 'label',
            Mage::helper('events')->__('Delete Event'));

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
            function saveAndContinueEdit() {
                if (typeof $ !== 'undefined') {
                    editForm.submit($('edit_form').action+'back/edit/');
                } else if (typeof jQuery !== 'undefined') {
                    editForm.submit(jQuery('edit_form').action+'back/edit/');
                }
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('event_data') && Mage::registry('event_data')->getEventId()) {
            return Mage::helper('events')->__("Edit Event '%s'",
                $this->htmlEscape(Mage::registry('event_data')->getTitle()));
        } else {
            return Mage::helper('events')->__('New Event');
        }
    }

}
