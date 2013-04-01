<?php
/**
 * Controller for managing events (create, edit, delete)
 * 
 * @author Daniel Golding
 */
class DG_Events_Adminhtml_EventsController extends
        Mage_Adminhtml_Controller_Action {
    /**
     * @return DG_Events_adminhtml_EventsController
     */
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('events/manage')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Events'),
                Mage::helper('adminhtml')->__('Events'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Events'),
                Mage::helper('adminhtml')->__('Manage Events'));
        return $this;
    }

    /**
     * Default page for managing all the events -- looks like the default
     */
    public function indexAction() {
        $this->_title($this->__('Events'))
            ->_title($this->__('Manage Events'));
        
        $this->_initAction();
        $this->renderLayout();
    }
    
    /**
     * Create new event
     */
    public function newAction() {
        // creating = editing a blank event
        $this->_forward('edit');
    }
    
    /**
     * Page for editing or creating a new event item
     */
    public function editAction() {
        $this->_title($this->__('Events'))
            ->_title($this->__('Manage Events'));
        
        /** @var $model DG_Events_Model_Event */
        $model = Mage::getModel('events/event');
        
        $eventId = $this->getRequest()->getParam('event_id');
        if ($eventId) {
            $model->load($eventId);

            // Check that the event exists
            if (!$model->getEventId()) {
                $this->_getSession()->addError(
                    Mage::helper('events')->__('Event does not exist.'));
                return $this->_redirect('*/*/');
            }

            //prepare title
            $this->_title($model->getTitle());
            $breadCrumb = Mage::helper('events')->__('Edit Event');
        } else {
            $this->_title(Mage::helper('events')->__('New Event'));
            $breadCrumb = Mage::helper('events')->__('New Event');
        }
        
        // Inititialise Breadcurmbs
        $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);
        
        // Set entered data from registry if there was an error during save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        
        // Register model to use later in blocks
        Mage::register('event_data', $model);
        
        // finally render layout!
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
        
    }
    
    public function saveAction() {
        $redirectPath = '*/*';
        $redirectParams = array();
        
        // check if data sent
        $data = $this->getRequest()->getPost();
        if ($data) {
            $data = $this->_filterPostData($data);
            
            // initialise a model object to put our data into
            /** @var $model DG_Events_Model_Event */
            $model = Mage::getModel('events/event');
            
            $eventId = $this->getRequest()->getParam('event_id');
            if ($eventId) {
                $model->load($eventId);
            }
            
            $data['store'] = serialize($data['store']);

            $model->addData($data);
            try {
                $model->save();
                $this->_getSession()->addSuccess(
                    Mage::helper('events')
                    ->__('The event has been saved successfully')
                );
                
                if ($this->getRequest()->getParam('back')) {
                    $redirectPath = '*/*/edit';
                    $redirectParams = array('event_id' => $model->getId());
                } else {
                    $redirectPath = '*/*/';
                    $redirectParams = array();
                }
                
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('events')
                    ->__('An error occured while trying to save the news item.'));
            }
        }
        $this->_redirect($redirectPath, $redirectParams);
    }
    /******     Not Finished Continue Here       ***********/
    //TODO deleteAction() ... etc
    /**
     * Delete action
     * deletes a particular event
     */
    public function deleteAction() {
        
        // Check an event id has been given to us
        if ($eventId = $this->getRequest()->getParam('event_id')) {
            try {
                // initialise the model and then do the delete
                /** @var $model DG_Events_Model_Event */
                $model = Mage::getModel('events/event');
                $model->load($eventId);
                if (!$model->getEventId()) {
                    Mage::throwException(Mage::helper('events')
                        ->__('Unable to find event in database')
                    );
                }
                $model->delete();

                // display a succes message
                $this->_getSession()->addSuccess(
                    Mage::helper('events')
                    ->__('The event has been deleted succesfully')
                );
            } catch (Mage_core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('events')->__('And Error occurec while trying to delete the event.')
                );
            }
        }

        // Go back to the management grid
        $this->_redirect('*/*/');
    }
    
    /**
     * Check if we have permission to run the corresponding action
     *
     * @return boolean
     */
    protected function _isAllowed() {
        switch ($this->getRequest()->getActionName()) {
        case 'new':
        case 'save':
            return Mage::getSingleton('Admin/session')
                ->isAllowed('events/manage/save');
            break;
        case 'delete':
            return MAge::getSingleton('Admin/session')
                ->isAllowed('events/manage/delete');
            break;
        default:
            return Mage::getSingleton('Admin/session')
                ->isAllowed('news/manage');
            break;
        }
    }

    /**
     * Filtering posted data. Converting localised data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data) {
        $data = $this->_filterDates($data, array('date'));
        return $data;
    }

    /**
     * Grid ajax action
     */
    public function gridAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}

