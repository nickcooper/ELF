<div id="Abatement" class="form_section">
    <h3>
        <?php echo h($label); ?>
        <?php if (isset($required) && $required): ?>
            <span class="req"><?php echo __('Required Section'); ?></span>
        <?php endif; ?>
    </h3>
<?php if (GenLib::isData($phases, '0', array('id'))) : ?>
    <div class="span-12">
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th><?php echo __('Phase #'); ?></th>
                    <th><?php echo __('Begin Date'); ?></th>
                    <th><?php echo __('End Date'); ?></th>
                <?php if (! $isComplete && ! $isCancelled): ?>
                    <th><?php echo __('Actions'); ?></th>
                <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($phases as $idx => $phase): ?>
                <tr>
                    <td width="60"><?php echo $idx+1; ?></td>
                    <td><?php echo h($this->TextProcessing->formatDate($phase['begin_date'])); ?></td>
                    <td><?php echo h($this->TextProcessing->formatDate($phase['end_date'])); ?></td>
                <?php if (! $isComplete && ! $isCancelled): ?>
                    <td width="85">
                    <?php
                        echo $this->Html->link(
                            __('Edit'),
                            array(
                                'controller' => 'phases',
                                'action'     => 'edit',
                                $abatement['id'],
                                $phase['id'],
                                'return'     => base64_encode($this->here),
                            ),
                            array(
                                'class' => 'iconify comment',
                                'title' => __('Edit Phase'),
                            )
                        );
                        echo '&nbsp;';
                        echo $this->Html->link(
                            __('Remove'),
                            array(
                                'controller' => 'phases',
                                'action'     => 'delete',
                                $abatement['id'],
                                $phase['id'],
                                'return'     => base64_encode($this->here),
                            ),
                            array(
                                'class' => 'iconify warning',
                                'title' => __('Remove Phase'),
                            ),
                            __('Are you sure you want to delete this phase?')
                        );
                    ?>
                    </td>
                <?php endif; ?>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
    <?php if (! $isComplete && ! $isCancelled): ?>
        <div class="actions">
        <?php
            echo $this->Html->link(
                __('Add Phase'),
                array(
                    'controller' => 'phases',
                    'action'     => 'add',
                    $abatement['id'],
                    'return'     => base64_encode($this->here),
                ),
                array('class' => 'button small')
            );
        ?>

        </div>
    <?php else: ?>
            <div class="notice"><?php echo __('Cannot add phases'); ?></div>
    <?php endif; ?>

    </div>
<?php else: ?>

    <div class="actions text_center">
        <?php
            if (! $isComplete && ! $isCancelled)
            {
                echo $this->Html->link(
                    __('Add Phase'),
                    array(
                        'controller' => 'phases',
                        'action'     => 'add',
                        $abatement['id'],
                        'return'     => base64_encode($this->here),
                    ),
                    array('class' => 'button small')
                );
            }
        ?>
    </div>

<?php endif; ?>
</div>
