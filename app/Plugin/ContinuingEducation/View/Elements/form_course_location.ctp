		<fieldset>
			<legend><?php echo __('Location Title'); ?></legend>
			<?php echo $this->Form->input('Address.label', array('label' => __('Name of Location'), 'class' => 'text large span-12')); ?>
		</fieldset>

        <fieldset id="address_set">
            <legend><?php echo __('Address'); ?></legend>
            <?php echo $this->element('AddressBook.address_form_short'); ?>
        </fieldset>
		<fieldset>
			<legend><?php echo __('Location Contact Person'); ?></legend>
			<?php echo $this->Form->input('CourseLocation.contact_person'); ?>
			<?php echo $this->Form->phone('CourseLocation.contact_phone', array('label' => __('Phone Number'))); ?>
		</fieldset>