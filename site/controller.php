<?php

/**
 * @version         $Id: controller.php 16385 2010-04-23 10:44:15Z matthew.murphy $
 *
 * @copyright   Copyright (C) 2015 City of Philadelphia Elections Commission
 * @license         GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Applicant Controller.
 *
 * @since 1.5
 */
class PvmachineinspectorsController extends JController
{
    /**
     * Display signup form.
     *
     * @since   1.5
     */
    public function display()
    {
        JRequest::setVar('view', 'register');
        parent::display();
    }

    /**
     * Display signup acknowledgement.
     *
     * @since   1.5
     */
    public function thanks()
    {
        JRequest::setVar('view', 'thanks');
        //		$document   =& JFactory::getDocument();
        //		$document->setTitle( JText::_( 'Testing jText and $document->setTitle...' ) );

        parent::display();
    }

    /**
     * Save registration and notify users and admins if required.
     */
    public function register_save()
    {
        $db = &JFactory::getDBO();
        d('processing the save', $_POST);

        // call to validate save, and ditch out to form on failure
        if (!$this->validate_save()) {
            // load the form and a message
            $message = 'Form invalidated, sucka!';
            // load the form again
            return $this->display();
        }

        if (!$this->save()) {
            $message = 'Could not save. -- replace with a JError call';
            return $this->display();
        }

        // hey, we have good data!  let's set a message for the redirect
        $message = "Thank you for registering to be a Machine Inspector.";

        //
        $email = JRequest::getVar('email', null, 'post', 'string');
        if ($email) {
            $message .= "<br>...And thank you for providing an email address.  <br>At your convenience please check your email for a confirmation email and click the link within to <b>verify</b> your email.";
        }
        //dd('stopping before we redirect');
        $this->setRedirect('index.php', $message);
    }

    /**
     * Validation tests for length only on fname, lname, address1, city, province, postcode, email
     *
     */
    public function validate_save()
    {
        return (JRequest::getVar('fname', null, 'post', 'string') &&
            JRequest::getVar('lname', null, 'post', 'string') &&
            JRequest::getVar('address1', null, 'post', 'string') &&
            JRequest::getVar('city', null, 'post', 'string') &&
            JRequest::getVar('province', null, 'post', 'string') &&
            JRequest::getVar('postcode', null, 'post', 'string') &&
            JRequest::getVar('email', null, 'post', 'string'));
    }

    /**
     * Save the form data in the various proper locations
     */
    public function save()
    {

        //get division data
        $address1 = JRequest::getVar('address1', null, 'post', 'string');

        $url = "http://gis.phila.gov/arcgis/rest/services/ElectionGeocoder/GeocodeServer/findAddressCandidates";
        // shape,score,match_addr,house,side,predir,pretype,streetname,suftype,sufdir,city,state,zip,ref_id,blockid,division,match,addr_type
        $fields = "division";
        $params = "Street=".urlencode($address1)."&outFields=".urlencode($fields)."&f=pjson";
        $curl   = curl_init($url."?".$params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response);
        switch (sizeof($json->candidates)) {
            case 0:
                $division = '';
                break;
            case 1:
                $division = (string) $json->candidates[0]->attributes->division;
                break;
            default:
                $this->candidates($json);
                return;
                break;
        }

        //save pv_person data and return a person_id

        //save applicant data

        //save pv_address data and return an address_id
        //save pv_address_xref
        //
        //save pv_link data
        //save pv_link_xref

        return true;
    }

    /**
     * Password Reset Request Method.
     */
    public function requestreset()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        // Get the input
        $email = JRequest::getVar('email', null, 'post', 'string');

        // Get the model
        $model = &$this->getModel('Reset');

        // Request a reset
        if ($model->requestReset($email) === false) {
            $message = JText::sprintf('PASSWORD_RESET_REQUEST_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=reset', $message);

            return false;
        }

        $this->setRedirect('index.php?option=com_user&view=reset&layout=confirm');
    }

    /**
     * Username Reminder Method.
     */
    public function remindusername()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        // Get the input
        $email = JRequest::getVar('email', null, 'post', 'string');

        // Get the model
        $model = &$this->getModel('Remind');

        // Send the reminder
        if ($model->remindUsername($email) === false) {
            $message = JText::sprintf('USERNAME_REMINDER_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=remind', $message);

            return false;
        }

        $message = JText::sprintf('USERNAME_REMINDER_SUCCESS', $email);
        $this->setRedirect('index.php?option=com_user&view=login', $message);
    }

    public function _sendMail(&$user, $password)
    {
        global $mainframe;

        $db = &JFactory::getDBO();

        $name     = $user->get('name');
        $email    = $user->get('email');
        $username = $user->get('username');

        $usersConfig    = &JComponentHelper::getParams('com_users');
        $sitename       = $mainframe->getCfg('sitename');
        $useractivation = $usersConfig->get('useractivation');
        $mailfrom       = $mainframe->getCfg('mailfrom');
        $fromname       = $mainframe->getCfg('fromname');
        $siteURL        = JURI::base();

        $subject = sprintf(JText::_('Account details for'), $name, $sitename);
        $subject = html_entity_decode($subject, ENT_QUOTES);

        if ($useractivation == 1) {
            $message = sprintf(JText::_('SEND_MSG_ACTIVATE'), $name, $sitename, $siteURL.'index.php?option=com_user&task=activate&activation='.$user->get('activation'), $siteURL, $username, $password);
        } else {
            $message = sprintf(JText::_('SEND_MSG'), $name, $sitename, $siteURL);
        }

        $message = html_entity_decode($message, ENT_QUOTES);

        //get all super administrator
        $query = 'SELECT name, email, sendEmail'.
        ' FROM #__users'.
        ' WHERE LOWER( usertype ) = "super administrator"';
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        // Send email to user
        if (!$mailfrom || !$fromname) {
            $fromname = $rows[0]->name;
            $mailfrom = $rows[0]->email;
        }

        JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

        // Send notification to all administrators
        $subject2 = sprintf(JText::_('Account details for'), $name, $sitename);
        $subject2 = html_entity_decode($subject2, ENT_QUOTES);

        // get superadministrators id
        foreach ($rows as $row) {
            if ($row->sendEmail) {
                $message2 = sprintf(JText::_('SEND_MSG_ADMIN'), $row->name, $sitename, $name, $email, $username);
                $message2 = html_entity_decode($message2, ENT_QUOTES);
                JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
            }
        }
    }
}
