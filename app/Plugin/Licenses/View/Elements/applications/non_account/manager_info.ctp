<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo  $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($managers, '0.Account', array('id'))) : ?>
    
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Manager Account</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($managers as $manager) : ?>
                <tr>
                    <td>
                        <?php
                            echo $this->Html->aclLink(
                                $manager['Account']['label'],
                                array(
                                    'plugin' => 'accounts',
                                    'controller' => 'accounts',
                                    'action' => 'view',
                                    $manager['Account']['id'],
                                    'return' => $return,
                                )
                            );
                        ?>
                    </td>
                    <td width="85">
                        <?php
                            if (!$manager['primary_flag'] && $app_open):
                                echo $this->Form->postLink(
                                    '<i class="icon-remove-sign"></i>&nbsp;Remove', 
                                    array(
                                        'plugin' => 'accounts', 
                                        'controller' => 'managers', 
                                        'action' => 'delete', 
                                        $manager['id'],
                                        'return' => $return,
                                    ), 
                                    array(
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ), 
                                    __('Are you sure you want to remove # %s?', $manager['id']), array('title' => 'Remove instructor')
                                ); 
                            endif;
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php
                echo $this->Html->link(
                    'Edit Managers', 
                    array(
                        'plugin' => 'accounts',
                        'controller' => 'managers',
                        'action' => 'edit',
                        'fp' => $foreign_plugin,
                        'fo' => $foreign_obj,
                        'fk' => $foreign_key,
                        'return' => $return,
                    ),
                    array('class' => 'button small')
                ); 
            ?>
        </div>
    </div>

    <?php else : ?>
        
    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add Manager',
                array(
                    'plugin' => 'accounts',
                    'controller' => 'managers',
                    'action' => 'add',
                    'fp' => $foreign_plugin,
                    'fo' => $foreign_obj,
                    'fk' => $foreign_key,
                    'return' => $return,
                ),
                array('class' => 'button small')
            ); 
        ?>
    </div>
    <?php endif; ?>
</div>
