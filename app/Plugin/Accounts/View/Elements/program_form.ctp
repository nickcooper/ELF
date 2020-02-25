<div class="program form">
    <?php echo $this->Form->create('Program');?>
    <fieldset>
        <legend><?php echo sprintf(__('%s Program'), Inflector::humanize($this->action)); ?></legend>
        <?php
            echo $this->Form->input('Program.id');
            echo $this->Form->input('Program.label', array('label' => __('Name')));

            // only show the slug field if adding a new record
            if ($this->action == 'add')
            {
                echo $this->Form->input('Program.slug');
            }

            echo $this->Form->input('Program.descr', array('label' => __('Description'), 'type' => 'textarea', 'class' => 'short'));
            echo $this->Form->input('enabled');
            
            
            echo $this->Form->input('merchant_code');
            echo $this->Form->input('service_code');

            // program groups
            echo $this->Form->input(
                'GroupProgram.id',
                array('label' => __('Program Groups'), 'type'=>'select', 'multiple'=>'checkbox', 'options' => $group_programs)
            );
        ?>
    </fieldset>

    <div class="actions">
        <?php
            echo $this->Form->submit(__('Save'), array('class' => 'button submit'));

            // only show if edit
            if ($this->action == 'edit')
            {
                echo $this->Form->submit(__('Manage Groups'), array('name' => 'manage_groups'));
            }

            echo $this->Form->end();
        ?>
    </div>
</div>
