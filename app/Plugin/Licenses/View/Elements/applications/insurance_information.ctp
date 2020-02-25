<div class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($insurances, '0', array('id'))): ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Insurance Company Name</th>
                    <th scope="col">Policy Amount</th>
                    <th scope="col">Policy Expiration Date</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($insurances as $insurance) : ?>
                <tr>
                    <td><?php 
                            echo $this->Html->link(
                                $insurance['label'], 
                                array(
                                    'plugin' => 'licenses', 
                                    'controller' => 'insurance_informations', 
                                    'action' => 'edit',
                                    $insurance['id'], 
                                    'fp' => 'Licenses',
                                    'fo' => 'License',
                                    'fk' => $license['id'],
                                    'return' => $return,
                                ), 
                                array('title' => 'View insurance details')
                            ); 
                        ?>

                    </td>
                    <td><?php  echo $insurance['insurance_amount']; ?></td>
                    <td><?php  echo $insurance['expire_date']; ?></td>                    
                    <td width="85">
                        <?php 
                            echo $this->Html->link(
                                'Remove', 
                                array(
                                    'plugin' => 'accounts', 
                                    'controller' => 'insurance_informations', 
                                    'action' => 'delete', 
                                    $insurance['id'],
                                    'return' => $return,
                                ), 
                                array('class' => 'iconify warning'), __('Are you sure you want to delete insurance information "%s"?', $insurance['label']), array('title' => 'Remove Insurance Information')
                            ); 
                        ?>
                    </td>
                    <td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php 
                echo $this->Html->link(
                    'Add Insurance', 
                    array(
                        'plugin' => 'licenses', 
                        'controller' => 'insurance_informations', 
                        'action' => 'add',
                        'fp' => 'Licenses',
                        'fo' => 'License',
                        'fk' => $license['id'],
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
                'Add Insurance Information', 
                array(
                    'plugin' => 'licenses', 
                    'controller' => 'insurance_informations', 
                    'action' => 'add',
                    'fp' => 'Licenses',
                    'fo' => 'License',
                    'fk' => $license['id'],
                    'return' => $return,
                ), 
                array('class' => 'button small')
            ); 
        ?>
    </div>

    <?php endif; ?>
</div>
