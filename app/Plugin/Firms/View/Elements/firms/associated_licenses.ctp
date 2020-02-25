<div id="associated_licenses" class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php if (GenLib::isData($firm_licenses, '0', array('id'))) : ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th scope="row">License</th>
                    <th scope="row">Type</th>
                    <th scope="row" width="75">Added</th>
                </tr>
                <?php foreach($firm_licenses as $firm_license) : ?>
                    <tr>
                        <td>
                            <?php
                                echo $this->Html->link(
                                    $firm_license['License']['license_number'],
                                    array(
                                        'plugin' => 'licenses',
                                        'controller' => 'license',
                                        'action' => 'view',
                                        $firm_license['License']['id'],
                                        'return' => base64_encode($this->here)
                                    ),
                                    array('class' => 'iconify user')
                                );
                            ?>
                        </td>
                        <td><?php echo $firm_license['License']['LicenseType']['abbr']; ?></td>
                        <td><?php echo GenLib::dateFormat($firm_license['created']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php 
            echo $this->Html->link(
                'Add Associated License', 
                    array(
                        'plugin' => 'firms',
                        'controller' => 'firms',
                        'action' => 'view',
                        $firm['id'],
                        'return' => base64_encode($this->here)
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
                'Add Associated License', 
                    array(
                        'plugin' => 'firms',
                        'controller' => 'firms',
                        'action' => 'add_license_to_firm',
                        $firm['id'],
                        'return' => base64_encode($this->here)
                    ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>