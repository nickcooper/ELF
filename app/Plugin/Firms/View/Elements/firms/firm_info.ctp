<?php if (GenLib::isData($firm, null, array('id'))) : ?>
    
<div class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="160">Name</th>
                    <td><?php echo $firm['label']; ?></td>
                </tr>
                
                <?php if(GenLib::isData($firm, null, array('alias'))) : ?>
                <tr>
                    <th width="120">Doing Business As</th>
                    <td><?php echo $firm['alias']; ?></td>
                </tr>
                <?php endif; ?>
                
                <tr>
                    <th width="120">Person Responsible <span style="font-size:8.5pt;">(PR)</span></th>
                    <td>
                        <?php 
                            if(GenLib::isData($contact, null, array('label'))) : 
                                echo $contact['label'];
                            else :
                                echo $this->Html->link(
                                    'Add Information',
                                    array(
                                        'plugin' => 'firms',
                                        'controller' => 'firms',
                                        'action' => 'edit',
                                        $firm['id'],
                                        'return' => base64_encode($this->here)
                                    )
                                );
                            endif; 
                        ?>
                    </td>
                </tr>
                
                <?php if(GenLib::isData($contact, null, array('label'))) : ?>
                <tr>
                    <th width="120"><span style="font-size:8.5pt;">PR</span> Phone</th>
                    <td><?php echo $contact['phone']; ?></td>
                </tr>
                <tr>
                    <th width="120"><span style="font-size:8.5pt;">PR</span> Email</th>
                    <td><?php echo $contact['email']; ?></td>
                </tr>
                <tr>
                    <th width="120"><span style="font-size:8.5pt;">PR</span> A&amp;A Account</th>
                    <td><span class="blank">Not linked</span></td>
                </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                'Edit Information', 
                array(
                    'plugin' => 'firms',
                    'controller' => 'firms',
                    'action' => 'edit',
                    $firm['id'],
                    'return' => base64_encode($this->here)
                ),
                array('class' => 'button small')
            ); 
        ?>
        </div>
    </div>
</div>

<?php endif; ?>