<h4><?php echo __('Keyword Search'); ?></h4>
<?php echo $this->element('Searchable.keyword_input'); ?>
<div id="filter_options" class="hide">
    <div class="filter_group">
        <h4><?php echo __('License Type'); ?></h4>
        <?php
            echo $this->Form->input(
                'Account.License.license_type_id',
                array(
                    'type'     => 'select',
                    'multiple' => 'checkbox',
                    'label'    => false,
                    'options'  => $license_types,
                    'value'    => isset($this->params->named['license_type_id']) ? $this->params->named['license_type_id'] : '',
                )
            );
        ?>
    </div>
    <?php echo $this->element('Searchabble.filter_actions'); ?>
</div>
