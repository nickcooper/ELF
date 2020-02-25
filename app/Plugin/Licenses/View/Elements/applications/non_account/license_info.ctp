<div id="License" class="form_section setoff">
    <h3><?php echo sprintf('%s License', $foreign_obj); ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

<?php if ($license['pending']) : ?>

    <div class="notice">This license is pending: You cannot make edits unless the application is approved or denied.</div>

<?php elseif (strtolower($license_status['status']) == 'incomplete'): ?>

    <div class="notice">This license is incomplete: It has not been submitted by the user nor approved.</div>

<?php else: ?>

    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
            <tbody>
                <tr>
                    <th scope="row">License #</th>
                    <td><?php echo $license['license_number']; ?></td>
                </tr>
                <tr>
                    <th width="120" scope="row">Status</th>
                    <td><strong><?php echo $license_status['status']; ?></strong></td>
                </tr>
                <tr>
                    <th width="120" scope="row">Issue Date</th>
                    <td><?php echo GenLib::dateFormat($license['issued_date']); ?></td>
                </tr>
                <tr>
                    <th scope="row">Expiration Date</th>
                    <td><?php echo GenLib::dateFormat($license['expire_date']); ?></td>
                </tr>
            </tbody>
        </table>
        <?php echo $this->element('insta_doc_links', array('doc_links' => $doc_links), array('plugin' => 'OutputDocuments')); ?>
    </div>
    <?php
        // renew license
        if ($can_renew):

            ?>
            <div class="span-5 last">
                <div class="actions">
        <?php
            echo $this->Html->link(
                '<i class="icon-repeat"></i> ' . 'Renew License',
                array(
                    'plugin' => 'licenses',
                    'controller' => 'licenses',
                    'action' => 'renew',
                    $license['id']
                ),
                array(
                    'class' => 'small button',
                    'escape' => false
                )
            );
        ?>
            </div>
                </div>
        <?php endif; ?>
    <?php
        // convert license
        if ($can_convert):

            ?>
            <div class="span-5 last">
                <div class="actions">
        <?php
            echo $this->element(
                'Licenses.convert_new_button',
                array(
                    'license_types' => $license_types,
                    'license_id' => $license['id']
                )
            );
        ?>
            </div>
                </div>
        <?php endif; ?>

        <?php if ($license['manually_edited'] && $auth_user['Group']['admin']): ?>
            <br class="clearfixed" />
            <div class="notice">The dates for this record have been manually edited.</div>
        <?php endif; ?>

<?php endif; ?>
</div>