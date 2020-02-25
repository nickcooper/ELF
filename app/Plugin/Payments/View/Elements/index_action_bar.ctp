<h4>Keyword Search</h4>
    <?php echo $this->element('keyword_input', array(), array('plugin' => 'Searchable')); ?>
    <div id="filter_options" class="hide">
        <div class="filter_group border span-8">
            <h4>Payment Type</h4>
            <?php
                echo $this->Form->input(
                    'payment_type_id',
                    array(
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'label' => false,
                    'options' => $payment_types,
                    'value' => (isset($this->params->named['payment_type_id']) ? explode(',', $this->params->named['payment_type_id']) : ''),
                ));
            ?>
        </div>
        <div class="filter_group span-9">
            <h4>Date Ranges</h4>
            <?php
                echo $this->Form->input(
                    'date_field',
                    array(
                        'type' => 'select',
                        'options' => $date_fields,
                        'label' => false,
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
                        'separator' => '',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.Payment.searchable.view_vars.start_date')
                    )
                );
            ?>
            <?php
                echo $this->Form->input(
                    'date_end',
                    array(
                        'type' => 'date',
                        'label' => 'End Date',
                        'separator' => '',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.Payment.searchable.view_vars.end_date')
                    )
                );

                echo 'For best results, enter both a start and an end date.'
            ?>
        </div>
        <?php echo $this->element('filter_actions', array(), array('plugin' => 'Searchable')); ?>
    </div>