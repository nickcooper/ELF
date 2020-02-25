<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
            <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php
    # check if data has been saved to decide what to show
    if (GenLib::isData($documents, '0', array('id'))):
    ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Document</th>
                    <th scope="col">Description</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($documents as $document) : ?>
                <tr>
                    <td>
                        <?php
                            echo $this->Html->link(
                                $document['file_name'],
                                $document['web_path'],
                                array('title' => 'View document')
                            );
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $document['label']
                        ?>
                    </td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i>&nbsp;Remove',
                                    array(
                                        'plugin' => 'uploads',
                                        'controller' => 'uploads',
                                        'action' => 'delete',
                                        $document['id'],
                                        'return' => $return,
                                    ),
                                    array(
                                        'class' => 'inline_action warning',
                                        'title' => 'Remove document',
                                        'escape' => false
                                    ),
                                    __('Are you sure you want to delete document #%s?', $document['id']), array('title' => 'Remove document')
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
                    'Add Document',
                    array(
                        'plugin' => 'uploads',
                        'controller' => 'uploads',
                        'action' => 'add',
                        'fp' => 'Accounts',
                        'fo' => 'Account',
                        'fk' => $account['id'],
                        'type' => 'Document',
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
                'Add Document',
                array(
                    'plugin' => 'uploads',
                    'controller' => 'uploads',
                    'action' => 'add',
                    'fp' => 'Accounts',
                    'fo' => 'Account',
                    'fk' => $account['id'],
                    'type' => 'Document',
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>
