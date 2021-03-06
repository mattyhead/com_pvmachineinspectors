<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Pvmachineinspector Controller for Pvmachineinspectors Component
 *
 * @package    Philadelphia.Votes
 * @subpackage Components
 * @license        GNU/GPL
 */
class PvmachineinspectorsControllerApplicants extends PvmachineinspectorsController
{
    public function display()
    {
        // if 'raw' isn't explicit, set to 'html'
        $view = $this->getView('applicants', JRequest::getWord('format', 'html'));
        $view->setModel($this->getModel('Applicants'), true);
        $view->setModel($this->getModel('Wards'), false);

        if (JRequest::getVar('ward', false)) {
            $view->setModel($this->getModel('Divisions'), false);
        }

        $view->display();
    }

    public function edit()
    {
        $mainframe = JFactory::getApplication();
        $cid       = JRequest::getVar('cid');
        $mainframe->redirect('index.php?option=com_pvmachineinspectors&controller=applicant&task=edit&cid=' . $cid[0]);
    }

    public function add()
    {
        $mainframe = JFactory::getApplication();
        $mainframe->redirect('index.php?option=com_pvmachineinspectors&controller=applicant&task=add&&cid=' . $cid[0]);
    }

    /**
     * remove record(s)
     *
     * @return void
     */
    public function remove()
    {
        JRequest::checkToken() or jexit('Invalid Token');

        $model = $this->getModel('applicant');
        if (!$model->delete()) {
            $msg = JText::_('Error: One or More Applicants Could not be Deleted');
        } else {
            $msg = JText::_('Applicants(s) Deleted');
        }

        $this->setRedirect('index.php?option=com_pvmachineinspectors', $msg);
    }
}
