<?php
/**
 * $Id: admin/tables/inspector_applicant.php $
 * $LastChangedBy: Matt Murphy $
 * Campaign Finance Reports - Philadelphiavotes.com
 * a component for Joomla! 1.5 CMS (http://www.joomla.org)
 * Author Website: http://www.philadelphiavotes.com
 * @copyright Copyright (C) 2015 City of Philadelphia
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package Philadelphia.Votes
 */

defined('_JEXEC') or die('Restricted access');

/**
 * @package Philadelphia.Votes
 */

class TableApplicant extends JTable
{
    public $id;
    public $division_id;
    public $prefix;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $address1;
    public $address2;
    public $address3;
    public $city;
    public $region;
    public $postcode;
    public $email;
    public $phone;
    public $published;
    public $checked_out;
    public $checked_out_time;
    public $created;
    public $updated;

    public function __construct(&$_db)
    {
        parent::__construct('#__pv_inspector_applicants', 'id', $_db);
    }
}
