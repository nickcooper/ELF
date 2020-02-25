<?php if (strtolower($license_status['status']) == 'incomplete') : ?>
    <div class="notice">This license is incomplete: It has not been submitted by the user nor approved.</div>

<?php else: ?>
    <div id="license_information" class="form_section setoff <?php echo $open_close_class;?>">
        <h3>License Information<?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

        <?php if (strtolower($license_status['status']) == 'incomplete') : ?>
            <div class="notice">This license is incomplete: It has not been submitted by the user nor approved. License information will be available after the license has been submitted.</div>

        <?php else: ?>
            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                    <tbody>
                        <tr>
                            <th scope="row" width="200">License #</th>
                            <td><?php echo $license['license_number']; ?></td>
                        </tr>

                        <?php if (GenLib::isData($license, 'License', array('legacy_number'))) : ?>
                        <tr>
                            <th scope="row">Legacy #</th>
                            <td><?php echo $license['legacy_number']; ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr>
                            <th width="120" scope="row">License Status</th>
                            <td><strong><?php echo $license_status['status']; ?></strong></td>
                        </tr>
                        <tr>
                            <th width="120" scope="row">Application Status</th>
                            <td><?php echo $applications[0]['ApplicationStatus']['label']; ?></td>
                        </tr>
                        <tr>
                            <th width="120" scope="row">Application State</th>
                            <td><?php echo ($applications[0]['open'] ? 'Open' : 'Closed'); ?></td>
                        </tr>
                        <tr>
                            <th width="120" scope="row">Issue Date</th>
                            <td><?php echo GenLib::dateFormat($license['issued_date']); ?></td>
                        </tr>
                        <tr>
                            <th width="120" scope="row">Processed Date</th>
                            <td><?php echo GenLib::dateFormat($applications[0]['processed_date']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Effective Date</th>
                            <td><?php echo GenLib::dateFormat($applications[0]['effective_date']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Paid/Submitted Date</th>
                            <td><?php echo GenLib::dateFormat($applications[0]['submit_paid_date']); ?></td>
                        </tr>

                        <?php foreach ($license_expirations as $key => $expiration): ?>
                            <tr>
                                <th scope="row"><?php echo Inflector::humanize($key); ?> Expires</th>
                                <td><?php echo GenLib::dateFormat($expiration);?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
                <p>This license will expire on <?php echo GenLib::dateFormat($license['expire_date']); ?><?php if (isset($license_expire_reason['descr'])): ?> because of <?php echo $license_expire_reason['descr']; ?><?php endif; ?>.</p>

                <?php if ($allow_docs_menu && $app_open): ?>
                    <?php echo $this->element('insta_doc_links', array('doc_links' => $doc_links), array('plugin' => 'OutputDocuments')); ?>
                <?php endif; ?>
            </div>

            <?php
                // application action buttons
                echo $this->element(
                    'Licenses.application_action_buttons',
                    array(
                        'license' => $license,
                        'current_application' => $current_application,
                        'open_application' => $open_application,
                        'license_types' => $license_types,
                        'license_status' => $license_status
                    )
                );
        endif;

        if ($license['manually_edited'] && $auth_user['Group']['admin']): ?>
            <br class="clearfixed" />
            <div class="notice">The dates for this record have been manually edited.</div>
        <?php endif; ?>

    </div>
<?php endif; ?>