<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo $humanized_foreign_obj . ' #'; ?></span>
                <?php echo $license['License']['license_number']; ?>
            </h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
        </div>
    </div>
</div>

<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo sprintf(__('Edit %s Managers'), $humanized_foreign_obj); ?></h3>
        <div class="actions">
            <?php
                echo $this->Html->link(
                    __('Add New Manager'),
                    array(
                        'controller' => 'managers',
                        'action'     => 'add',
                        'fp'         => $foreign_plugin,
                        'fo'         => $foreign_obj,
                        'fk'         => $foreign_key,
                        'return'     => base64_encode($this->here),
                    ),
                    array(
                        'class' => 'button'
                    )
                );
            ?>
        </div>
        <?php echo $this->Form->create('Manager', array()); ?>

        <fieldset>
            <legend>Managers</legend>
            <table class="light_data" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th><?php echo __('Primary'); ?></th>
                        <th><?php echo __('Name'); ?></th>
                        <th><?php echo __('Address'); ?></th>
                        <th><?php echo __('Phone'); ?></th>
                        <th><?php echo __('Actions'); ?></th>
                    </tr>

                    <?php foreach($managers as $manager): ?>

                    <tr>
                        <td>
                            <?php
                                echo $this->Form->radio(
                                    'primary_flag',
                                    array($manager['Manager']['id'] => ''),
                                    array(
                                        'hiddenField' => false,
                                        'checked'     => $manager['Manager']['primary_flag'] ? 'checked' : '',
                                    )
                                );
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $this->Html->link(
                                    $manager['Account']['label'],
                                    array(
                                        'plugin'     => 'accounts',
                                        'controller' => 'accounts',
                                        'action'     => 'view',
                                        $manager['Account']['id'],
                                        'return'     => base64_encode($this->here)
                                    )
                                );
                            ?>
                        </td>
                        <td>
                            <?php echo $this->element(
                                'templates/standard_address',
                                array('address' => $manager['Account']['PrimaryAddress']),
                                array('plugin' => 'AddressBook')
                            );?>
                        </td>
                        <td>
                            <?php echo $this->element(
                                'templates/phone_and_fax',
                                array('address' => $manager['Account']['PrimaryAddress']),
                                array('plugin' => 'AddressBook')
                            ); ?>
                        </td>
                        <td width="85">
                        <?php if (!$manager['Manager']['primary_flag']): ?>
                            <?php
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i>' . __('Remove'),
                                    array(
                                        'controller' => 'managers',
                                        'action'     => 'delete',
                                        $manager['Manager']['id'],
                                        'return'     => base64_encode($this->here)
                                    ),
                                    array(
                                        'class' => 'inline_action warning',
                                        'title' => __('Remove manager'),
                                        'escape' => false
                                    ),
                                    sprintf(__('Are you sure you want to remove manager %s?'), $manager['Account']['label'])
                                );
                            ?>
                        <?php endif; ?>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </fieldset>

        <?php echo $this->Form->end(__('Update Primary')); ?>
    </div>
</div>
