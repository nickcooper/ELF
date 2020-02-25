<h4>Keyword Search</h4>
    <?php echo $this->element('keyword_input', array(), array('plugin' => 'Searchable')); ?>
    <div id="filter_options" class="hide">
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

                echo $this->Form->input(
                    'date_start',
                    array(
                        'type' => 'date',
                        'label' => 'Start Date',
                        'separator' => '',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.CourseSection.searchable.view_vars.start_date')
                    )
                );

                echo $this->Form->input(
                    'date_end',
                    array(
                        'type' => 'date',
                        'label' => 'End Date',
                        'separator' => '',
                        'dateFormat' => 'MDY',
                        'empty' => false,
                        'selected' => Configure::read('Searchable.CourseSection.searchable.view_vars.end_date')
                    )
                );

                echo 'For best results, enter both a start and an end date.'
            ?>
        </div>
        <?php echo $this->element('filter_actions', array(), array('plugin' => 'Searchable')); ?>
    </div>