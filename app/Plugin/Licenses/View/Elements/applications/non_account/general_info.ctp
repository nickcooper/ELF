<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php if (GenLib::isData($license, $license['License']['foreign_obj'])) : ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="140" scope="row">Organization Name</th>
                    <td><?php echo $license[$foreign_obj]['label']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Abbreviation</th>
                    <td><?php echo $this->textProcessing->checkForBlank($license[$foreign_obj]['abbr']); ?></a></td>
                </tr>
                <?php if (GenLib::isData($license, $foreign_obj, array('website'))) : ?>
                <tr>
                    <th scope="row">Website</th>
                    <td><?php echo $this->Html->link($license[$foreign_obj]['website']); ?></a></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th scope="row">How will hands-on skills be assessed?</th>
                    <td><?php echo ($license[$foreign_obj]['training_plan'] == '') ?
                        '<span class="blank">none</span>' :
                        $this->Html->link(
                            '<i class="icon-eye-open"></i> View',
                            '#training_plan_answer',
                            array(
                                'class' => 'inline_action modal blue',
                                'title' => 'Hands-on skills assessment',
                                'escape' => false
                            )
                        ); ?></td>
                </tr>
                <tr>
                    <th scope="row">Describe the Available Hand-on Equipment</th>
                    <td><?php echo ($license[$foreign_obj]['equipment'] == '') ?
                        '<span class="blank">none</span>' :
                        $this->Html->link(
                            '<i class="icon-eye-open"></i> View',
                            '#equipment_answer',
                            array(
                                'class' => 'inline_action modal blue',
                                'title' => 'Available hands-on equipment',
                                'escape' => false
                            )
                        ); ?></td>
                </tr>
                <tr>
                    <th width="120">Do Not Send Mail</th>
                    <td><?php echo ($license[$foreign_obj]['no_mail'] ? '&#10004;' : ''); ?></td>
                </tr>
                <tr>
                    <th width="120">No Public Contact</th>
                    <td><?php echo ($license[$foreign_obj]['no_public_contact'] ? '&#10004;' : ''); ?></td>
                </tr>
            </tbody>
        </table>
        <div class="answer_holder hide">
            <div id="training_plan_answer">
                <h4>How will hands-on skills be assessed?</h4>
                <?php echo $this->textProcessing->pbr($license[$foreign_obj]['training_plan']); ?>
            </div>
            <div id="equipment_answer">
                <h4>Describe the Available Hand-on Equipment</h4>
                <?php echo $this->textProcessing->pbr($license[$foreign_obj]['equipment']); ?>
            </div>
        </div>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                'Edit Information',
                array(
                    'plugin' => $underscored_foreign_plugin,
                    'controller' => $foreign_controller,
                    'action' => 'edit',
                    $foreign_key,
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
                'Add Information',
                array(
                    'plugin' => $underscored_foreign_plugin,
                    'controller' => $foreign_controller,
                    'action' => 'add',
                    $license['License']['id'],
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>