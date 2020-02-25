<?php echo $this->element('section_nav', array('account' => $this->request->data)); ?>
<div id="section" class="span-19 last">
	<div class="pad">

    <?php echo $this->Form->create('Account', array('type' => 'file'));?>
    
    <fieldset>
        <legend><?php echo sprintf(__('Edit Email')); ?></legend>
        <?php
            // account data
            if ($this->action == 'editEmail')
            {
                echo $this->Form->input('Account.id', array('type' => 'hidden'));
                echo $this->Form->input('Account.label', array('disabled' => true, 'label' => 'Name'));
                echo $this->Form->input('Account.username', array('disabled' => true));
                echo $this->Form->input('Account.email');
                echo $this->Form->submit(__('Save'), array('class' => 'button submit'));
            }
        ?>
    </fieldset>

    </div>
</div>
