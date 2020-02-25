<?php echo $this->element('section_heading'); ?>
<div id="section" class="span-19 last">
    <div class="pad">
        <h2>Groups</h2>
        <hr />
        <div class="actions">
            <?php echo $this->Html->link('Add New Group', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'add'), array('class' => 'button')); ?>
        </div>
        <table cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th scope="col"><?php echo $this->Paginator->sort('Group.name', 'Name');?></th>
                    <th scope="col"><?php echo $this->Paginator->sort('Group.enabled', 'Active');?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group) : ?>
                <tr>
                    <td><?php echo $this->Html->link($group['Group']['label'], array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'view', $group['Group']['id'])); ?>&nbsp;</td>
                    <td><?php echo $group['Group']['enabled']; ?>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $this->element('pagination_links'); ?>
    </div>
</div>