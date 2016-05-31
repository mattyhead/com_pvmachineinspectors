<?php
/**
 * Applicant View for Pvmachineinspectors Component
 *
 * @package    Philadelphia.Votes
 * @subpackage Components
 * @license     GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Applicant View
 *
 * @package    Philadelphia.Votes
 * @subpackage Components
 */
class PvmachineinspectorsViewApplicant extends JView
{
    /**
     * display method of Applicant view
     * @return void
     **/
    public function display($tpl = null)
    {

        $applicant = &$this->get('Data');

        $this->assignRef('applicant', $applicant);
        $this->assignRef('isNew', false);

        parent::display($tpl);
    }
}
