<?php
/**
 * Default controller
 *
 * @author Daniel Golding
 */
class DG_Events_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch() {
        parent::preDispatch();

        if (!Mage::helper('events')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('noRoute');
        }
    }

    /**
     * Module index, by default shows the events in the current month
     */
    public function indexAction() {

		$this->loadLayout();
		$this->renderLayout();

    }
}
