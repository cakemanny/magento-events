<?php
/**
 * Form for editing and event in  the backend
 *
 * @author Daniel Golding
 */
class DG_Events_Block_Adminhtml_Events_Edit_Form extends
        Mage_Adminhtml_Block_Widget_Form {
    /**
     * Setup the form and return it
     *
     * @return
     */
    protected function _prepareForm() {
        if (($data = Mage::getSingleton('adminhtml/session')->getEventData())) {
            Mage::getSingleton('adminhtml/session')->getEventData(null);
        } elseif (Mage::registry('event_data')) {
            $data = Mage::registry('event_data')->getData();
        } else {
            $data = array();
        }

        if (array_key_exists('store', $data)) {
            $data['store'] = unserialize($data['store']);
        }

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save',
                array('event_id' => $this->getRequest()->getParam('event_id'))
            ),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data',
        ));
        
        $form->setUseContainer(true);
        $this->setForm($form);
        
        // Here we go about creating the elements of the form itself
        $fieldset = $form->addFieldset('event_form', array(
            'legend' => Mage::helper('events')->__('Event Information'),
        ));
        
        $this->_addElementTypes($fieldset);
        
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('events')->__('Title'),
            'title'     => Mage::helper('events')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        
        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('events')->__('Short Description'),
            'title'     => Mage::helper('events')->__('Short Description'),
            'class'     => 'required-entry',
            'style'     => 'height: 150px;',
            'required'  => true,
        ));
        
        $fieldset->addField('store', 'multiselect', array(
            'name'      => 'store[]',
            'label'     => Mage::helper('events')->__('Store(s)'),
            'title'     => Mage::helper('events')->__('Store(s)'),
            'values'    => Mage::helper('events')->storesValueLabelArray(),
            'style'     => 'height: 100px;',
        ));
        
        $fieldset->addField('destination', 'text', array(
            'name'      => 'destination',
            'label'     => Mage::helper('events')->__('Destination URL'),
            'title'     => Mage::helper('events')->__('Destination URL'),
            'note' => 'This is optional, but useful if the event has it\'s own page',
        ));
        
        /*
        $fieldset->addField('image', 'text', array(
            'name'      => 'image',
            'label'     => Mage::helper('events')->__('Image'),
            'title'     => Mage::helper('events')->__('Image'),
        ));
        */
        
        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('events')->__('Image'),
            'title'     => Mage::helper('events')->__('Image'),
            'required'  => false,
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('date', 'date', array(
            'name'      => 'date',
            'label'     => Mage::helper('events')->__('Date of Event'),
            'title'     => Mage::helper('events')->__('Date of Event'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'class'     => 'required-entry',
            'required'  => true,
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'    => $dateFormatIso,
        ));
        
        // We dispatch an event that we can hook into in case we need to do any
        // other work while preparing this form.
        Mage::dispatchEvent('adminhtml_events_edit_form_prepare_form',
            array('form' => $form));
        
        $form->setValues($data);

        return parent::_prepareForm();
    }
    
    /**
     * Get our custom image form element type that loads from the right place
     *
     * @return array
     */
    protected function _getAdditionalElementTypes() {
        return array('image' => Mage::getConfig()
            ->getBlockClassName('events/adminhtml_events_edit_form_element_image')
        );
    }
}

