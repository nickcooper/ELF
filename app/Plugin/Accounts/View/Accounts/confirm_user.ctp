<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre"><?php echo __('New Account'); ?></h3>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>Confirm Personal Information</h3>
        <fieldset>
            <legend>Confirm User Account</legend>
            
            <p class="attn">Please verify the information below to complete the confirmation process.</p>
            
            <h4>Name: <?php echo $account['Account']['label']; ?></h4>

            <div class="form_section">
                <h3>Licenses Held</h3>
                <?php if (GenLib::isData($account, 'License.0', array('id'))): ?>
                <table border="0" cellpadding="0" cellspacing="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col">License Number</th>
                            <th scope="col">New License Number</th>
                            <th scope="col">License Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Expire Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account['License'] as $license) : ?>
                        <tr>
                            <td><?php echo $license['legacy_number']; ?></td>
                            <td><?php echo $license['license_number']; ?></td>
                            <td><?php echo $license['LicenseType']['abbr']; ?></td>
                            <td><?php echo $license['LicenseStatus']['status']; ?></td>
                            <td><?php echo GenLib::dateFormat($license['expire_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="notice text_center">No licenses held.</div>
                <?php endif; ?>
            </div>

            <div class="form_section">
                <h3>Managed Firms</h3>

                <?php if (GenLib::isData($account, 'ManagedFirm.0', array('id'))): ?>

                <table border="0" cellpadding="0" cellspacing="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account['ManagedFirm'] as $firm) : ?>
                        <tr>
                            <td><?php echo $firm['label']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="notice text_center">No firms managed.</div>
                <?php endif; ?>
            </div>

            <div class="form_section">
                <h3>Managed Training Providers</h3>

                <?php if (GenLib::isData($account, 'ManagedTrainingProvider.0', array('id'))): ?>

                <table border="0" cellpadding="0" cellspacing="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account['ManagedTrainingProvider'] as $training_provider) : ?>
                        <tr>
                            <td><?php echo $training_provider['label']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="notice text_center">No training providers managed.</div>
                <?php endif; ?>
            </div>


            <div class="form_section">
                <h3>Courses Taken</h3>

                <?php if (GenLib::isData($account, 'CourseRoster.0', array('id'))): ?>
                <table border="0" cellpadding="0" cellspacing="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col">Course Name</th>
                            <th scope="col">Course Section #</th>
                            <th scope="col">Code Hours</th>
                            <th scope="col">Non-code Hours</th>
                            <th scope="col">Training Provider</th>
                            <th scope="col">Date</th>
                            <th scope="col">Refresher Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account['CourseRoster'] as $roster) : ?>
                            <tr>
                                <td><?php echo $roster['CourseSection']['label']; ?></td>
                                <td><?php echo $roster['CourseSection']['course_section_number']; ?></td>
                                <td><?php echo $roster['CourseSection']['CourseCatalog']['code_hours']; ?></td>
                                <td><?php echo $roster['CourseSection']['CourseCatalog']['non_code_hours']; ?></td>
                                <td><?php echo $roster['CourseSection']['TrainingProvider']['label']; ?></td>
                                <td><?php echo GenLib::dateFormat($roster['CourseSection']['end_date']); ?></td>
                                <td width="85"><?php echo $roster['expire_date'] ? GenLib::dateFormat($roster['expire_date']) : ''; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else : ?>
                    <div class="notice text_center">No courses have been taken.</div>
                <?php endif; ?>
            </div>

            <br />

            <p>The email entered for your A&A account will override any email address previously provided to DPS.  You will be able to change your email from the My Account page.</p>

            <?php echo $this->Form->create(); ?>

            <h4>Acknowledgement Agreement</h4>

            <?php
                echo $this->Form->checkbox('perjury_acknowledged');
                echo __('I confirm that the provided information above is mine or I am responsible for the information to the best of my knowledge.');
            ?>

            <br /><br />

            <?php
                echo $this->Form->submit(
                    __('Confirm and Log in'),
                    array(
                        'class' => 'button submit',
                        'after' => sprintf(
                            '&nbsp;%s',
                            $this->Html->link(
                                __('Decline and Logout'),
                                array('action' => 'decline_confirm_user', AuthComponent::user('id')),
                                array('class' => 'button cancel')
                            )
                        ),
                    )
                );
            ?>

            <?php echo $this->Form->end(); ?>

        </fieldset>
    </div>
</div>