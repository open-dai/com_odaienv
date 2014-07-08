<?php
/**
* @version		$Id$ $Revision$ $Date$ $Author$ $
* @package		Odaienv
* @subpackage 	Views
* @copyright	Copyright (C) 2014, Luca Gioppo. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class OdaienvViewVm  extends JViewLegacy 
{

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$app = JFactory::getApplication('site');
		
		// Initialise variables.		
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}
		
		//Get Params and Merge
		$this->params	= $this->state->get('params');
		$active	= $app->getMenu()->getActive();
		$temp	= clone ($this->params);
		$temp->merge($this->item->params);
		$this->item->params = $temp;
		
		parent::display($tpl);
	}
}
?> 