<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre"><?php echo __('Program'); ?></span> <?php echo $program['Program']['label']; ?></h3>
            <p class="bottom">
                <?php echo $this->Html->link(__('< Back to Program Listing'), array('controller' => 'programs', 'action' => 'index')); ?>
            </p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <div class="actions">
            <?php echo $this->Html->link(__('Finished'), array('controller' => 'programs', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
        <div id="Program_Information" class="form_section">
            <h3><?php echo __('Program Information'); ?></h3>
            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                    <tr>
                        <th width="120" scope="row"><?php echo __('Name'); ?></th>
                        <td><?php echo h($program['Program']['label']); ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Slug'); ?></th>
                        <td><?php echo h($program['Program']['slug']); ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Description'); ?></th>
                        <td><?php echo $this->TextProcessing->checkForBlank($program['Program']['descr']); ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Merchant Code'); ?></th>
                        <td><?php echo $program['Program']['merchant_code']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Service Code'); ?></th>
                        <td><?php echo $program['Program']['service_code']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Enabled'); ?></th>
                        <td><?php echo $program['Program']['enabled']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Created'); ?></th>
                        <td><?php echo h($this->TextProcessing->formatDate($program['Program']['created'])); ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Modified'); ?></th>
                        <td><?php echo h($this->TextProcessing->formatDate($program['Program']['modified'])); ?></td>
                    </tr>
                </table>
            </div>
            <div class="span-5 last">
                <div class="actions">
                    <?php
                        echo $this->Html->link(
                            __('Edit Program Information'),
                            array(
                                'controller' => 'programs',
                                'action'     => 'edit',
                                $program['Program']['id']
                            ),
                            array('class' => 'button small')
                        );
                    ?>
                </div>
            </div>
        </div>
        <div id="Program_Groups" class="form_section">
            <h3><?php echo __('Program Groups'); ?></h3>
            <div class="span-12">
                <table cellspacing="0" cellpadding="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo __('Group'); ?></th>
                            <th scope="col"><?php echo __('Enabled'); ?></th>
                            <th scope="col"><?php echo __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($program['Group'] as $group): ?>
                        <tr>
                            <td><?php echo h($group['label']); ?></td>
                            <td><?php echo h($group['enabled']); ?></td>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        __('View'),
                                        array('controller' => 'groups', 'action' => 'view', $group['id'])
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
                            __('Add Program Group'),
                            array('controller' => 'groups', 'action' => 'add'),
                            array('class' => 'button small')
                        );
                    ?>
                </div>
            </div>
        </div>
        <div id="License_Types" class="form_section">
            <h3><?php echo __('License Types'); ?></h3>
            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="light_data">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo __('License Type'); ?></th>
                            <th scope="col"><?php echo __('Enabled'); ?></th>
                            <th scope="col"><?php echo __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (GenLib::isData($license_types, 'LicenseType.0', array('id'))): ?>
                        <?php foreach ($license_types as $license_type): ?>
                        <tr>
                            <td><?php echo h($license_type['label']); ?></td>
                            <td><?php echo $license_type['enabled']; ?></td>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        __('View'),
                                        array(
                                            'plugin'     => 'licenses',
                                            'controller' => 'license_types',
                                            'action'     => 'view',
                                            $license_type['id']
                                        )
                                    );
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="span-5 last">
                <div class="actions">
                    <?php
                        echo $this->Html->link(
                            __('Add License Type'),
                            array(
                                'plugin'     => 'licenses',
                                'controller' => 'license_types',
                                'action'     => 'add'
                            ),
                            array('class' => 'button small')
                        );
                    ?>
                </div>
            </div>
        </div>
        <div class="actions">
            <?php echo $this->Html->link(__('Finished'), array('controller' => 'programs', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
    </div>
</div>