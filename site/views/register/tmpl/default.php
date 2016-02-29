<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
jimport("pvcombo.PVCombo");
jimport("kint.kint");
if (count(JRequest::getVar('msg', null, 'post'))) {
    foreach (JRequest::getVar('msg', null, 'post') as $msg) {
        JError::raiseWarning(1, $msg);
    }
}
// lets go through the post array and extract any existing values for display
$fields = array('prefix', 'first_name', 'middle_name', 'last_name', 'suffix', 'division', 'address1', 'address2', 'city', 'region', 'postcode', 'phone', 'email');
foreach ($fields as $field) {
    $$field = JRequest::getVar($field, null, 'post');
}

$document->addCustomTag('<script src="/components/com_pvmachineinspectors/assets/js/machineinspectors.js" async defer></script>');
?>
<form action="<?=JRoute::_('index.php?option=com_pvmachineinspectors');?>" method="post" id="josForm" name="josForm" class="form-validate">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40">
		<label id="namemsg" for="first_name"><?=JText::_('Name');?>:</label>
	</td>
  	<td>
<?=JHTML::_('select.genericlist', PVCombo::gets('prefix'), 'prefix', 'class="inputbox required"', 'idx', 'value', $prefix, true)?>
  		<input type="text" name="first_name" id="first_name" size="18%" value="<?=$first_name?>" class="inputbox required" maxlength="50" placeholder="<?=JText::_('FNAME PLACEHOLDER');?>" />
  		<input type="text" name="middle_name" id="middle_name" size="1%" value="<?=$middle_name?>" class="inputbox optional" maxlength="25" />
  		<input type="text" name="last_name" id="last_name" size="18%" value="<?=$last_name?>" class="inputbox required" maxlength="50" placeholder="<?=JText::_('LNAME PLACEHOLDER');?>" />
<?=JHTML::_('select.genericlist', PVCombo::gets('suffix'), 'suffix', 'class="inputbox required"', 'idx', 'value', $suffix, true)?>
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="address1msg" for="address1"><?=JText::_('STREET ADDRESS');?>:</label>
	</td>
	<td>
		<input type="text" id="address1" name="address1" size="60%" value="<?=$address1?>" class="inputbox required" maxlength="60" placeholder="<?=JText::_('STREET PLACEHOLDER');?>" />
	</td>
</tr>
<tr>
	<td height="40">
		<label id="address2msg" for="address2"><?=JText::_('APT_UNIT_SUITE');?>:</label>
	</td>
	<td>
		<input type="text" id="address2" name="address2" size="60%" value="<?=$address2?>" class="inputbox optional" maxlength="60" />
	</td>
</tr>
<tr>
	<td height="40">
		<label id="citymsg" for="city"><?=JText::_('CITY');?>:</label>
	</td>
	<td>
		<input type="text" id="city" name="city" size="60%" value="<?=($city ? $city : 'Philadelphia')?>" class="inputbox required" maxlength="60" placeholder="<?=JText::_('CITY PLACEHOLDER');?>" />
	</td>
</tr>
<tr>
	<td height="40">
		<label id="regionmsg" for="region">
<?=JText::_('REGION');?>:
		</label>
	</td>
	<td><?=JHTML::_('select.genericlist', PVCombo::gets('state'), 'region', 'class="inputbox required"', 'idx', 'value', ($region ? $region : 'PA'), true)?></td>
</tr>
<tr>
	<td height="40">
		<label id="postcodemsg" for="postcode">
<?=JText::_('POSTCODE');?>:
		</label>
	</td>
	<td>
		<input type="text" id="postcode" name="postcode" size="60%" value="<?=$postcode?>" class="inputbox required" maxlength="60" placeholder="<?=JText::_('POSTCODE PLACEHOLDER');?>" />
	</td>
</tr>
<tr>
	<td height="40">
		<label id="phonemsg" for="phone">
<?=JText::_('PHONE');?>:
		</label>
	</td>
	<td>
		<input type="text" id="phone" name="phone" size="60%" value="<?=$phone?>" class="inputbox required" maxlength="100" placeholder="<?=JText::_('PHONE PLACEHOLDER');?>" />
	</td>
</tr>
<tr>
	<td height="40">
		<label id="emailmsg" for="email">
<?=JText::_('EMAIL');?>:
		</label>
	</td>
	<td>
		<input type="text" id="email" name="email" size="60%" value="<?=$email?>" class="inputbox" maxlength="100" />
	</td>
</tr>
<tr>
	<td height="40">&nbsp;</td>
	<td>
		<button class="button validate" type="submit"><?=JText::_('REGISTER');?></button>
		<input type="hidden" name="task" value="save" />
	</td>
</tr>
</table>

<?=JHTML::_('form.token');?>
</form>