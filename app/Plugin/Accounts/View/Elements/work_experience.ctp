<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($experiences, '0', array('id'))): ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Employer</th>
                    <th scope="col">Position</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($experiences as $experience) : ?>
                <tr>
                    <td>
                        <?php 
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $experience['employer'], 
                                    array(
                                        'plugin' => 'accounts', 
                                        'controller' => 'work_experiences', 
                                        'action' => 'edit', 
                                        $experience['id'],
                                        'fp' => 'Accounts',
                                        'fo' => 'Account',
                                        'fk' => $account['id'],
                                        'return' => $return,
                                    ), 
                                    array('title' => 'View experience details')
                                ); 
                            }
                            else
                            {
                                echo $experience['employer'];
                            }
                        ?>
                    </td>
                    <td><?php echo $experience['position']; ?></td>
                    <td width="85">
                        <?php 
                            if ($app_open)
                            {   
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i> Remove', 
                                    array(
                                        'plugin' => 'accounts', 
                                        'controller' => 'work_experiences', 
                                        'action' => 'delete', 
                                        $experience['id'],
                                        'return' => $return,
                                    ), 
                                    array(
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ), 
                                    __('Are you sure you want to delete experience #%s?', $experience['id']), array('title' => 'Remove Experience')
                                );
                            }
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
                    'Add Work Experience', 
                    array(
                        'plugin' => 'accounts', 
                        'controller' => 'work_experiences', 
                        'action' => 'add',
                        'fp' => 'Accounts',
                        'fo' => 'Account',
                        'fk' => $account['id'],
                        'return' => $return,
                    ), 
                    array('class' => 'button small')
                ); 
            ?>
        </div>
    </div>

    <?php else: ?>

    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add Work Experience', 
                array(
                    'plugin' => 'accounts', 
                    'controller' => 'work_experiences', 
                    'action' => 'add',
                    'fp' => 'Accounts',
                    'fo' => 'Account',
                    'fk' => $account['id'],
                    'return' => $return,
                ), 
                array('class' => 'button small')
            ); 
        ?>
    </div>

    <?php endif; ?>
</div>
