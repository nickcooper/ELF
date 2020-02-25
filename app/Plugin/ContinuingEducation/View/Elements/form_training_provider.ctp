<fieldset>
    <legend><?php echo __('About Organization'); ?></legend>
    <?php echo $this->Form->input('TrainingProvider.label', array('class'=>'text large span-12')); ?>
    <?php echo $this->Form->input('TrainingProvider.abbr', array('label' => __('Abbreviation'))); ?>
    <?php echo $this->Form->input('TrainingProvider.website', array('label' => __('Website'))); ?>
    <?php
       // do not send mail to this firm
        $mail_help_link = $this->Html->link('help', '/help_items/do_not_send_mail.html', array('class' => 'help_tag'));

        echo $this->Form->input(
            'TrainingProvider.no_mail',
            array(
                'label' => 'Do Not Send Mail' . $mail_help_link,
                'type' => 'checkbox',
                'value' => 0
            )
        );

        // do not allow public to contact this training provider
        $help_link = $this->Html->link('help', '/help_items/no_public_contact.html', array('class' => 'help_tag'));

        echo $this->Form->input(
            'TrainingProvider.no_public_contact',
            array(
                'label' => 'Do Not Allow Public Contact ' . $help_link,
                'type' => 'checkbox',
                'value' => 1
            )
        );
    ?>
</fieldset>
<fieldset>
    <legend><?php echo __('Hands-on Assessment & Equipment'); ?></legend>
        <?php
          echo $this->Form->input(
            'TrainingProvider.training_plan',
            array(
                'label' => __('How will hands-on skills be assessed?'),
                'type'  => 'textarea',
                'class' => 'span-x med elastic',
            )
        );
        ?>
        <hr />
        <?php
            echo $this->Form->input(
                'TrainingProvider.equipment',
                array(
                    'label' => __('Describe the Available Hand-on Equipment'),
                    'class' => 'span-x med elastic',
                )
            );
         ?>
</fieldset>
