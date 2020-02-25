<?php
/*

I DON'T BELIEVE THIS FILE IS BEING USED ANY LONGER. IT SHOULD PROBABLY BE DELETED.

-AM

*/
?>

		<fieldset>
            <legend><?php echo __('Person Responsible'); ?></legend>
            <?php echo $this->Form->input(
            	'Contact.first_name',
	            array(
	            	'label' => 'First Name',
	            	'class' => 'text span-7',
	            	'div' => array(
	            		'class' => 'form_item span-7'
	            	)
	            )
	        ); ?>
            <?php echo $this->Form->input(
            	'Contact.last_name', 
            	array(
            		'label' => 'Last Name',
            		'class' => 'text span-7',
            		'div' => array(
            			'class' => 'form_item span-11 last'
            		)
            	)
            ); ?>
            <?php echo $this->Form->phone(
            	'Contact.phone', 
            	array('label' => 'Phone Number')
            ); ?>
            <?php echo $this->Form->input(
            	'Contact.ext', 
            	array('label' => 'Phone Extension')
            ); ?>
			<?php echo $this->Form->input('Contact.email', array('label' => 'PR Email')); ?></div>

        </fieldset>
