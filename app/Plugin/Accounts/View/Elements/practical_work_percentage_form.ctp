<?php echo $this->Form->create('PracticalWorkPercentage'); ?>

    <fieldset>
        <legend><?php echo h($label); ?></legend>
    <?php
        echo $this->Form->input('PracticalWorkPercentage.id', array('type' => 'hidden'));
        echo $this->Form->input(
            'PracticalWorkPercentage.practical_work_percentage_type_id',
            array(
                'label'    => _('Type of Experience'),
                'options'  => $practical_work_percentage_types,
            )
        );
        echo $this->Form->input(
            'PracticalWorkPercentage.percentage',
            array(
                'after' => '%',
                'min'   => 0,
                'max'   => 100,
            )
        );
        echo $this->Form->input(
            'PracticalWorkPercentage.descr',
            array(
                'type'  => 'textarea',
                'label' => __('Other'),
                'class' => 'span-x med',
            )
        );
    ?>
    </fieldset>

<?php echo $this->Form->end('Save'); ?>
