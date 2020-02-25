        <fieldset>
            <legend><?php echo __('Organization Information'); ?></legend>
            <?php echo $this->Form->input('Firm.label', array('class' => 'text large span-12')); ?>
            <?php echo $this->Form->input('Firm.alias', array('label' => 'Doing Business As')); ?>
            <?php echo $this->Form->input('firm_type_id'); ?>
            <?php
                // do not send mail to this firm
                $mail_help_link = $this->Html->link('help', '/help_items/do_not_send_mail.html', array('class' => 'help_tag'));

                echo $this->Form->input(
                    'Firm.no_mail',
                    array(
                        'label' => 'Do Not Send Mail' . $mail_help_link,
                        'type' => 'checkbox',
                        'value' => 0
                    )
                );

                // do not allow public to contact this firm
                $help_link = $this->Html->link('help', '/help_items/no_public_contact.html', array('class' => 'help_tag'));

                echo $this->Form->input(
                    'Firm.no_public_contact',
                    array(
                        'label' => 'Do Not Allow Public Contact ' . $help_link,
                        'type' => 'checkbox',
                        'value' => 1
                    )
                );
            ?>
        </fieldset>

        <fieldset>
            <legend><?php echo __('Person Responsible'); ?></legend>
            <?php if(!empty($this->data['Contact']['id'])): ?>
                <?php echo $this->Form->hidden('Contact.id'); ?>
            <?php endif ?>
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
			<?php echo $this->Form->input('Contact.email', array('label' => 'PR Email')); ?>

        </fieldset>
