<div class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php
    # check if data has been saved to decide what to show
    if (GenLib::isData($license, 'Account.SupportDocument')):
    ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Document</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($license['Account']['SupportDocument'] as $document) : ?>
                <tr>
                    <td>
                        <?php 
                            echo $this->Html->link(
                                $document['label'], 
                                array(
                                    'plugin' => '', 
                                    'controller' => '', 
                                    'action' => '', 
                                    $document['id'],
                                    'return' => base64_encode($this->here)
                                ), 
                                array('title' => 'View document')
                            ); 
                        ?>
                    </td>
                    <td width="85">
                        <?php 
                            echo $this->Html->link(
                                'Remove', 
                                array(
                                    'plugin' => 'uploads', 
                                    'controller' => 'upload', 
                                    'action' => 'delete', 
                                    $document['id'],
                                    'return' => base64_encode($this->here)
                                ), 
                                array('class' => 'iconify warning'), __('Are you sure you want to delete document #%s?', $document['id']), array('title' => 'Remove document')
                            ); 
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
                        'controller' => 'upload', 
                        'action' => 'add',
                        'fp' => 'Accounts',
                        'fo' => 'Account',
                        'fk' => $license['Account']['id'],
                        'id' => 'support_docs',
                        'return' => base64_encode($this->here)
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
                    'controller' => 'upload', 
                    'action' => 'add',
                    'fp' => 'Accounts',
                    'fo' => 'Account',
                    'fk' => $license['Account']['id'],
                    'id' => 'support_docs',
                    'return' => base64_encode($this->here)
                ), 
                array('class' => 'button small')
            ); 
        ?>
    </div>

    <?php endif; ?>
</div>