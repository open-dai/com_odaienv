 <?php
/**
* @version		$Id$ $Revision$ $Date$ $Author$ $
* @package		Odaienv
* @subpackage 	Controllers
* @copyright	Copyright (C) 2014, Luca Gioppo. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/libraries/csClient/CloudStackClient.php';

/**
 * Odaienvvms Controller
 *
 * @package    Odaienv
 * @subpackage Controllers
 */
class OdaienvControllerCloudstack extends JControllerLegacy
{
	protected $vmList = array('puppet', 'wso2api', 'wso2greg', 'apache', 'nfs', 'jbossvdbmaster', 'wso2bam', 'wso2esb', 'wso2bps');

	public function vmlist($cachable = false, $urlparams = false)
	{
		// prepare the CloudStack client
		$params = JComponentHelper::getParams('com_odaienv');
		$domid = $params->get('domid');
		$endpoint = $params->get('endpoint');
		$api_key = $params->get('api_key');
		$secret_key = $params->get('secret_key');
		$cloudstack = new CloudStackClient($endpoint, $api_key, $secret_key);
		
		// Get the list of VM 
		$listVirtualMachines_params = array();
		$listVirtualMachines_params['isrecursive']='true';
		$listVirtualMachines_params['listall']='true';
		try {
			$csVmList = $cloudstack->listVirtualMachines($listVirtualMachines_params);
		} catch (CloudStackClientException $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		// get the list of templates
		$zones= $cloudstack->listZones(array(
			"available"=>"false")
			);

		$templates = $cloudstack->listTemplates(array(
			"templatefilter" => "featured",
			"zoneid"=>$zones[0]->id)
			);

		$diskOfferings = $cloudstack->listDiskOfferings(array());	
		
		$serviceOfferings = $cloudstack->listServiceOfferings(array());
		
		$networks = $cloudstack->listNetworks(array(
									"acltype" => "Account",
									"listall" => "true"));
		
		// get the list of compute offerings
		$view = $this->getView('cloudstack','html');
		$view->odaivms = $this->vmList;
		$view->vms = $csVmList;
		$view->templates = $templates;
		$view->serviceOfferngs = $serviceOfferings;
		$view->diskOfferings = $diskOfferings;
		$view->networks = $networks;
		$view->display();
		return $this;
	}
	public function vmdeploy($cachable = false, $urlparams = false)
	{
		// prepare the CloudStack client
		$params = JComponentHelper::getParams('com_odaienv');
		$domid = $params->get('domid');
		$endpoint = $params->get('endpoint');
		$api_key = $params->get('api_key');
		$secret_key = $params->get('secret_key');
		$cloudstack = new CloudStackClient($endpoint, $api_key, $secret_key);
	
		echo "deploy";
		$jinput = JFactory::getApplication()->input;
		$service = $jinput->get('service', 'default_value', 'filter');
		$template = $jinput->get('template', 'default_value', 'filter');
		$service = $jinput->get('service', 'default_value', 'filter');
		$network = $jinput->get('network', 'default_value', 'filter');
		$vm = $jinput->get('vm', 'default_value', 'filter');
		$post_array = $jinput->getArray($_POST);
		echo $service;
		echo $template;
		echo print_r($post_array);
		echo date_default_timezone_get();
		echo gethostname(); 
		echo php_uname('n');

		
		$userdata = "role=".$vm."\nenv=".date_default_timezone_get()."\npuppet_master=".gethostname()."\ntimezone=".date_default_timezone_get()."\n";
		$userdata_encoded = base64_encode($userdata);

		$vars = array(
			"serviceofferingid" => $service,
			"templateid" => $template,
			"zoneid" => $params->get('zoneid'),
			"displayname" => $vm,
			"domainid" => $params->get('domid'),
			"name" => $vm,
			"networkids" => $network,
			"userdata" => $userdata_encoded
			);
			
//		$created = $cloudstack->deployVirtualMachine($vars);
		$view = $this->getView('cloudstack','html');
		$view->display();
		return $this;
	}
		
}// class
?> 