<?php echo $this->Form->create('Group');?>
    <fieldset>
        <legend><?php echo __('Program Group Information'); ?></legend>
    <?php
        echo $this->Form->input('Group.id');
        // only show on add
        if ($this->action == 'add') {
            echo $this->Form->input('Group.program_id', $programs);
        }
        echo $this->Form->input('Group.name');
        echo $this->Form->input('Group.descr', array('type' => 'textarea', 'label' => 'Description', 'class' => 'short'));
        echo $this->Form->input('Group.enabled');
    ?>
    </fieldset>

    <?php echo $this->Form->submit('Save', array('class' => 'button submit')); ?>

<?php echo $this->Form->end();?>