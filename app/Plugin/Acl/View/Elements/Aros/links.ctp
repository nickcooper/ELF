<div id="aros_link" class="acl_links">
<?php
$selected = isset($selected) ? $selected : $this->params['action'];

$links = array();
$links[] = $this->Html->link(__d('acl', 'Build missing AROs'), '/acl/aros/check', array('class' => ($selected == 'check' )? 'selected' : null));
$links[] = $this->Html->link(__d('acl', Configure::read('acl.aro.user.model').' roles'), '/acl/aros/users', array('class' => ($selected == 'users' )? 'selected' : null));

if(Configure :: read('acl.gui.roles_permissions.ajax') === true)
{
    $links[] = $this->Html->link(__d('acl', Configure::read('acl.aro.role.model').' permissions'), '/acl/aros/ajax_role_permissions', array('class' => ($selected == 'role_permissions' || $selected == 'ajax_role_permissions' )? 'selected' : null));
}
else
{
    $links[] = $this->Html->link(__d('acl', Configure::read('acl.aro.role.model').' permissions'), '/acl/aros/role_permissions', array('class' => ($selected == 'role_permissions' || $selected == 'ajax_role_permissions' )? 'selected' : null));
}
$links[] = $this->Html->link(__d('acl', Configure::read('acl.aro.user.model').' permissions'), '/acl/aros/user_permissions', array('class' => ($selected == 'user_permissions' )? 'selected' : null));

echo $this->Html->nestedList($links, array('class' => 'acl_links'));
?>
</div>