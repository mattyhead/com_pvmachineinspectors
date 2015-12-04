<?php
/**
 * $Id: site/models/address.php $
 * $LastChangedBy: Matt Murphy $
 * Election Officials - Philadelphiavotes.com
 * a component for Joomla! 1.5 CMS (http://www.joomla.org)
 * Author Website: http://www.philadelphiavotes.com.
 *
 * @copyright Copyright (C) 2015 City of Philadelphia
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * User Component Remind Model.
 *
 * @since        1.5
 */
class PvmachineinspectorsModelAddress extends JModel
{
    /**
     * Registry namespace prefix.
     *
     * @var string
     */
    public $_namespace = 'com_pvmachineinspectors.address.';

    /**
     * Create a new applicant.
     *
     * @param  array
     *
     * @return bool
     */
    public function create($data = array())
    {
        d($data, $this);

        return true;
    }

    /**
     * Read an address from address id.
     *
     * @param  int
     *
     * @return bool
     */
    public function read($id = null)
    {
        // todo
    }

    /**
     * Update an applicant.
     *
     * @param  array
     *
     * @return bool
     */
    public function update($data = array())
    {
        // todo
    }

    /**
     * Delete an applicant.
     *
     * @param  int
     *
     * @return boolp
     */
    public function delete($id = null)
    {
        // todo
    }
}
