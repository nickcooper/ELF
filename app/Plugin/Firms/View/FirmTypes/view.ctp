<div class="firmTypes view">
<h2><?php  echo __('Firm Type');?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($firmType['FirmType']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($firmType['FirmType']['name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created'); ?></dt>
        <dd>
            <?php echo h($firmType['FirmType']['created']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Modified'); ?></dt>
        <dd>
            <?php echo h($firmType['FirmType']['modified']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Firm Type'), array('action' => 'edit', $firmType['FirmType']['id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Firm Type'), array('action' => 'delete', $firmType['FirmType']['id']), null, __('Are you sure you want to delete # %s?', $firmType['FirmType']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Firm Types'), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Firm Type'), array('action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Firms'), array('controller' => 'firms', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Firm'), array('controller' => 'firms', 'action' => 'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php echo __('Related Firms');?></h3>
    <?php if (!empty($firmType['Firm'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php echo __('Id'); ?></th>
        <th><?php echo __('Legacy Id'); ?></th>
        <th><?php echo __('Name'); ?></th>
        <th><?php echo __('Firm Type Id'); ?></th>
        <th><?php echo __('Slug'); ?></th>
        <th><?php echo __('Created'); ?></th>
        <th><?php echo __('Modified'); ?></th>
        <th class="actions"><?php echo __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($firmType['Firm'] as $firm): ?>
        <tr>
            <td><?php echo $firm['id'];?></td>
            <td><?php echo $firm['legacy_id'];?></td>
            <td><?php echo $firm['name'];?></td>
            <td><?php echo $firm['firm_type_id'];?></td>
            <td><?php echo $firm['slug'];?></td>
            <td><?php echo $firm['created'];?></td>
            <td><?php echo $firm['modified'];?></td>
            <td class="actions">
                <?php echo $this->Html->link(__('View'), array('controller' => 'firms', 'action' => 'view', $firm['id'])); ?>
                <?php echo $this->Html->link(__('Edit'), array('controller' => 'firms', 'action' => 'edit', $firm['id'])); ?>
                <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'firms', 'action' => 'delete', $firm['id']), null, __('Are you sure you want to delete # %s?', $firm['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Firm'), array('controller' => 'firms', 'action' => 'add'));?> </li>
        </ul>
    </div>
</div>
