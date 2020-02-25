<div class="pending queue">
    <h2><?php echo sprintf(__('Pending %s'), $model);?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('label');?></th>
            <th class="actions"><?php echo __('Actions');?></th>
    </tr>
    <?php
    foreach ($data as $item): ?>
    <tr>
        <td><?php echo h($item[$model]['id']); ?>&nbsp;</td>
        <td><?php echo h(isset($item[$model]['label'])? $item[$model]['label']: $item['Account']['username']); ?>&nbsp;</td>
        <td class="actions">
            <?php
                echo $this->Html->link(__('View'), array('action' => 'view', $item[$model]['id']));
                echo $this->Html->link(__('Edit'), array('action' => 'edit', $item[$model]['id']));
                echo $this->Form->postLink(
                    __('Approve'),
                    array(
                        'action' => 'approve',
                        $item[$model]['id']
                    ),
                    null,
                    sprintf(__('Are you sure you want to approve # %s?'), $item[$model]['id'])
                );
                echo $this->Form->postLink(
                    __('Deny'),
                    array(
                        'action' => 'deny',
                        $item[$model]['id']
                    ),
                    null,
                    sprintf(__('Are you sure you want to deny # %s?'), $item[$model]['id'])
                );
                echo $this->Form->postLink(
                    __('Delete'),
                    array(
                        'action' => 'delete',
                        $item[$model]['id']
                    ),
                    null,
                    sprintf(__('Are you sure you want to delete # %s?'), $item[$model]['id'])
                );
            ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php
        echo $this->Paginator->counter(
            array(
                'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
            )
        );
    ?>  </p>

    <div class="paging">
    <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
    </div>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Course'), array('action' => 'add')); ?></li>
         <li><?php echo $this->Html->link(__('New Course Section'), array('controller' => 'course_sections','action' => 'add')); ?> </li>
    </ul>
</div>
