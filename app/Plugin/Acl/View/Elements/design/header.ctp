<?php
echo $this->Html->css('/acl/css/acl.css');
?>
<!--<div id="plugin_acl" class="">-->
<div id="section">
	<div class="pad">
	
	<?php
	echo $this->Session->flash('plugin_acl');
	?>
	
	<h2><?php echo __d('acl', 'ACL plugin'); ?></h2>
	<hr />

	
	<?php

	if(!isset($no_acl_links))
	{
	    $selected = isset($selected) ? $selected : $this->params['controller'];
    
        $links = array();
        $links[] = $this->Html->link(__d('acl', 'Permissions'), '/acl/aros/index', array('class' => ($selected == 'aros' )? 'selected' : null));
        $links[] = $this->Html->link(__d('acl', 'Actions'), '/acl/acos/index', array('class' => ($selected == 'acos' )? 'selected' : null));
        
        echo $this->Html->nestedList($links, array('class' => 'acl_links'));
	}
	?>