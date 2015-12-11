<?php
/**
 * $Id: site/controller.php $
 * $LastChangedDate: 2015-07-31 $
 * $LastChangedBy: Matt Murphy $
 * Election Officials - Philadelphiavotes.com
 * a component for Joomla! 1.5 CMS (http://www.joomla.org)
 * Author Website: http://www.philadelphiavotes.com
 * @copyright Copyright (C) 2015 City of Philadelphia
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package Philadelphia.Votes
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Applicant Controller.
 *
 * @since 1.5
 */
class PvmachineinspectorsController extends JController {
    public $message = '';
    /**
     * Display signup form.
     *
     * @since   1.5
     */
    public function display() {
        JRequest::setVar('view', 'register');
        JRequest::setVar('message', $this->message);
        parent::display();
    }

    /**
     * Display signup acknowledgement.
     *
     * @since   1.5
     */
    public function thanks() {
        JRequest::setVar('view', 'thanks');

        parent::display();
    }

    /**
     * Save registration and notify users and admins if required.
     */
    public function register_save() {
        $db = &JFactory::getDBO();
        d('processing the save', $_POST);

        // call to validate save, and ditch out to form on failure
        if (!$this->validate_save()) {
            d('invalidated');
            // load the form and a message
            $this->message = 'Form invalidated, sucka!';
            // load the form again
            return $this->display();
        }
        d('validated');
        if (!$this->save()) {
            $this->message = 'Could not save. -- replace with a JError call';
            return $this->display();
        }
        d('saved');
        // hey, we have good data!  let's set a message for the redirect
        $this->message = "Thank you for registering to be a Machine Inspector.";

        dd('stopping before we redirect');
        $this->setRedirect('index.php', $this->message);
    }

    /**
     * Validation tests for length only on fname, lname, address1, city, province, postcode, email
     *
     */
    public function validate_save() {
        return (JRequest::getVar('fname', null, 'post', 'word') &&
            JRequest::getVar('lname', null, 'post', 'string') &&
            JRequest::getVar('address1', null, 'post', 'string') &&
            JRequest::getVar('city', null, 'post', 'string') &&
            JRequest::getVar('region', null, 'post', 'string') &&
            JRequest::getVar('postcode', null, 'post', 'string') &&
            JRequest::getVar('email', null, 'post', 'string')
        );
    }

    /**
     * Save the form data in the various proper locations
     */
    public function save() {
        $created = date('Y-m-d h:i:s');

        jimport("combo.Combo");
        $region = $suffix = $prefix = $marital = $gender = '';
        // lets get values to replace references
        if (JRequest::getVar('prefix', null, 'post', 'string')) {
            $prefix = Combo::getPrefix(JRequest::getVar('prefix', null, 'post', 'string')) ? Combo::getPrefix(JRequest::getVar('prefix', null, 'post', 'string')) : '';
            $gender = Combo::getGender($prefix);
            $marital = Combo::getMarital($prefix);
        }
        if (JRequest::getVar('suffix', null, 'post', 'string')) {
            $suffix = Combo::getSuffix(JRequest::getVar('suffix', null, 'post', 'string')) ? Combo::getSuffix(JRequest::getVar('suffix', null, 'post', 'string')) : '';
        }
        if (JRequest::getVar('region', null, 'post', 'string')) {
            $region = Combo::getUSState(JRequest::getVar('region', null, 'post', 'string')) ? Combo::getUSState(JRequest::getVar('region', null, 'post', 'string')) : '';
        }

        // load our models
        $ia = $this->getModel('applicant');
        $a = $this->getModel('address');
        $l = $this->getModel('link');

        // create applicant record and get person id (applicant = person + inspector_applicant)
        $pids = $ia->create(
            array(
                'prefix' => $prefix,
                'first_name' => JRequest::getVar('fname', null, 'post', 'string'),
                'middle_name' => JRequest::getVar('mname', null, 'post', 'string'),
                'last_name' => JRequest::getVar('lname', null, 'post', 'string'),
                'suffix' => $suffix,
                'gender' => $gender,
                'marital_status' => $marital,
                'created' => $created,
            )
        );

        // a returned $pid means we wrote a person
        if ($pids['person']) {
            // save person's address and get a division_id
            $did = $a->create(
                array(
                    'person_id' => $pid,
                    'address1' => JRequest::getVar('address1', null, 'post', 'string'),
                    'address2' => JRequest::getVar('address2', null, 'post', 'string'),
                    'city' => JRequest::getVar('city', null, 'post', 'string'),
                    'region' => $region,
                    'postcode' => JRequest::getVar('postcode', null, 'post', 'string'),
                    'created' => $created,
                )
            );

            // a returned $did means we wrote a division
            if ($did) {
                d('updating IA with division');
                $ia->update(array('InspectorApplicant' => array('id' => $pids['applicant'], 'division_id' => $did)));
            }

            // link email to person
            $l->create(
                array(
                    'person_id' => $pid['person'],
                    'type' => 'email',
                    'value' => JRequest::getVar('email', null, 'post', 'string'),
                    'created' => $created,
                )
            );
        } else {
            return false;
        }

        return true;
    }
}
