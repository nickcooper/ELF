
    <h4>Keyword Search</h4>
    <?php echo $this->element('keyword_input', array(), array('plugin' => 'Searchable')); ?>
    <div id="filter_options" class="hide">
        <div class="filter_group border span-6">
            <h4>Application Type</h4>
            <?php
                echo $this->Form->input(
                    'application_type_id',
                    array(
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'label' => false,
                        'options' => $application_types,
                        'value' => (isset($this->params->named['application_type_id']) ? explode(',', $this->params->named['application_type_id']) : ''),
                    )
                );
            ?>
            <h4>License Type</h4>
            <?php
                echo $this->Form->input(
                    'license_type_id',
                    array(
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'label' => false,
                        'options' => $license_types,
                        'value' => (isset($this->params->named['license_type_id']) ? explode(',', $this->params->named['license_type_id']) : ''),
                    )
                );
            ?>
        </div>
        <div class="filter_group border span-5">
            <h4>Status</h4>
            <?php
                echo $this->Form->input(
                    'application_status_id',
                    array(
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'label' => false,
                    'options' => $application_statuses,
                    'value' => (isset($this->params->named['application_status_id']) ? explode(',', $this->params->named['application_status_id']) : ''),
                ));
            ?>
            <h4>Special Flags</h4>
            <?php
                echo $this->Form->input(
                    'flags',
                    array(
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'label' => false,
                    'options' => array(
                        'bypass_validation' => 'Validation Bypass',
                        'additional_review' => 'Additional Review'
                    ),
                    'value' => (isset($this->params->named['flags']) ? explode(',', $this->params->named['flags']) : ''),
                ));
            ?>
        </div>
        <div class="filter_group span-6 last">
            <h4>Date Ranges</h4>
            <?php
                echo $this->Form->input(
                    'date_field',
                    array(
                        'type' => 'select',
                        'options' => $date_fields,
                        'label' => 'Date Type',
                        'div' => false,
                        'empty' => '-- Select Type --',
                        'value' => (isset($this->params->named['date_field']) ? $this->params->named['date_field'] : ''),
                    )
                );
            ?>

            <?php
                echo $this->Form->input(
                    'date_start',
                    array(
                        'type' => 'date',
                        'label' => 'Start Date',
                        'separator' => '-',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.License.searchable.view_vars.start_date')
                    )
                );
            ?>

            <?php
                echo $this->Form->input(
                    'date_end',
                    array(
                        'type' => 'date',
                        'label' => 'End Date',
                        'separator' => '-',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.License.searchable.view_vars.end_date')
                    )
                );

                echo 'For best results, enter both a start and an end date.'
            ?>
        </div>
        <?php echo $this->element('filter_actions', array(), array('plugin' => 'Searchable')); ?>
    </div>
