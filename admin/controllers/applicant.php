<?php
/**
 * Pvmachineinspector Controller for Pvmachineinspectors Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license        GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Pvmachineinspector Pvmachineinspector Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class PvmachineinspectorsControllerApplicant extends PvmachineinspectorsController
{
    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    public function __construct()
    {
        d($this);
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('add', 'edit');
        $this->registerTask('edit', 'display');
    }

    /**
     * display the edit form
     * @return void
     */
    public function edit()
    {
        JRequest::setVar('view', 'pvmachineinspector');
        JRequest::setVar('layout', 'form');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    /**
     * save a record (and redirect to main page)
     * @return void
     */
    public function save()
    {
        $model = $this->getModel('applicant');

        if ($model->store($post)) {
            $msg = JText::_('Greeting Saved!');
        } else {
            $msg = JText::_('Error Saving Greeting');
        }

        // Check the table in so it can be edited.... we are done with it anyway
        $link = 'index.php?option=com_pvmachineinspectors';
        $this->setRedirect($link, $msg);
    }

    /**
     * remove record(s)
     * @return void
     */
    public function remove()
    {
        $model = $this->getModel('applicant');
        if (!$model->delete()) {
            $msg = JText::_('Error: One or More Greetings Could not be Deleted');
        } else {
            $msg = JText::_('Greeting(s) Deleted');
        }

        $this->setRedirect('index.php?option=com_pvmachineinspectors', $msg);
    }

    /**
     * cancel editing a record
     * @return void
     */
    public function cancel()
    {
        $msg = JText::_('Operation Cancelled');
        $this->setRedirect('index.php?option=com_pvmachineinspectors', $msg);
    }
}
