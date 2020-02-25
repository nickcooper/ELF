<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php
    // check for data
    if (GenLib::isData($license, sprintf('%s.Contact', $foreign_obj), array('id'))) :
    ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="120" scope="row">Name</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contact['label']); ?></td>
                </tr>
                <tr>
                    <th width="120" scope="row">Title</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contact['title']); ?></td>
                </tr>
                <tr>
                    <th width="120" scope="row">Business Phone</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contact['phone']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            if ($app_open)
            {
                echo $this->Html->link(
                    sprintf('Edit %s', $label), 
                    array(
                        'plugin' => $underscored_foreign_plugin,
                        'controller' => $foreign_controller,
                        'action' => 'edit',
                        $foreign_key,
                        'return' => $return,
                    ),
                    array('class' => 'button small')
                ); 
            }
        ?>
        </div>
    </div>

    <?php else : ?>
        
    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add General Info', 
                array(
                    'plugin' => $underscored_foreign_plugin,
                    'controller' => $foreign_controller,
                    'action' => 'add',
                    $license['id'],
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>