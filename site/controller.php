<?php
/**
* @version		$Id:controller.php  1 2014-07-07 14:44:16Z LG $
* @package		Odaienv
* @subpackage 	Controllers
* @copyright	Copyright (C) 2014, Luca Gioppo. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Odaienv Controller
 *
 * @package    
 * @subpackage Controllers
 */
class OdaienvController extends JControllerLegacy
{


	public function display($cachable = false, $urlparams = false)
	{
		$cachable	= true;	
					if(version_compare(JVERSION,'3','<')) {
				$vName = JRequest::getVar('view', 'vmss');			
				JRequest::setVar('view', $vName);
			} else {
				$vName = $this->input->get('view', 'vmss');
				$this->input->set('view', $vName);
			}	
			$safeurlparams = array(
			'id'				=> 'INT',
			
		);
		 
		return parent::display($cachable, $safeurlparams);
	}	
	

}// class
?>