<?php
/**
 * Pvmachineinspectors register view
 *
 * @package    Philadelphia.Votes
 * @subpackage Components
 * @license        GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * HTML View class for the Registration component.
 *
 * @since 1.0
 */
class PvmachineinspectorsViewRegister extends JView
{
    public function display()
    {
        global $mainframe;
        $pathway = &$mainframe->getPathway();
        $document = &JFactory::getDocument();
        $params = &$mainframe->getParams();

        // Page Title
        $menus = &JSite::getMenu();
        $menu = $menus->getActive();

        // fallback title
        $title = JText::_('APPLY TO BE A MACHINE INSPECTOR');

        if (is_object($menu)) {
            $menu_params = new JParameter($menu->params);
            if (!$menu_params->get('page_title')) {
                $params->set('page_title', $title);
            }
        } else {
            $params->set('page_title', $title);
        }

        // assign
        $document->setTitle($params->get('page_title'));
        $this->assignRef('params', $params);

        // Load the form validation behavior
        JHTML::_('behavior.formvalidation');

        parent::display();
    }
}
