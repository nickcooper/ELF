<?php
$group = $this->request->data['Group'];
?>
<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">Program Group</span> <?php echo $group['label'];?></h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to Group Overview', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'view', $group['id']));?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>Edit Program Group</h3>
        <?php echo $this->element('group_form'); ?>
    </div>
</div>