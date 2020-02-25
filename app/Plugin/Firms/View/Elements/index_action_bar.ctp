    <h4>Keyword Search</h4>
    <?php echo $this->element('keyword_input', array(), array('plugin' => 'Searchable')); ?>
        <div id="filter_options" class="hide">
            <div class="filter_group border span-6">
            <h4>Types</h4>
            <?php
                echo $this->Form->input(
                    'firm_type_id',
                    array(
                        'type' => 'select',
                        'options' => $firmTypes,
                        'label' => 'Firm Type',
                        'div' => false,
                        'empty' => '-- Select Type --',
                        'value' => (isset($this->params->named['firm_type_id']) ? $this->params->named['firm_type_id'] : ''),
                    )
                );
            ?>

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
            </div>
            <div class="filter_group border span-6">
            <h4>Date Ranges</h4>
            <?php
                echo $this->Form->input(
                    'date_start',
                    array(
                        'type' => 'date',
                        'label' => 'Start Date',
                        'separator' => '-',
                        'dateFormat' => 'MDY',
                        'empty' => true,
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
                        'empty' => true,
                        'selected' => Configure::read('Searchable.License.searchable.view_vars.end_date')
                    )
                );
            ?>
            </div>

            <div class="filter_group border">
            <?php
                echo 'For best results, enter both a start and an end date.'
            ?>
            </div>
        <?php echo $this->element('filter_actions', array(), array('plugin' => 'Searchable')); ?>
    </div>
