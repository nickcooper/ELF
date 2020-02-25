<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class AclController extends AclAppController {

	var $name = 'Acl';
	var $uses = null;
	
	function beforeFilter()
	{
	    parent :: beforeFilter();
	    
		$this->Auth->allow('*');
	}
	
	
	function index()
	{
	    $this->redirect('/acl/aros');
	}
	
	function acos_index()
	{
	    $this->redirect('/acl/acos');
	}
	
}
?>