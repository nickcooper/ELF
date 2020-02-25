<?php echo $this->Form->create('Account', array('type' => 'file'));?>
  <fieldset>
        <legend><?php echo sprintf(__('%s Account'), Inflector::humanize($this->action)); ?></legend>
    <?php
        // account data
        if ($this->action == 'edit')
        {
            echo $this->Form->input('Account.id', array('type' => 'hidden'));
        }
        echo $this->Form->input('Account.username');
        echo $this->Form->input('Account.email');

        // personal data
        echo $this->Form->input('Account.first_name');
        echo $this->Form->input('Account.middle_initial');
        echo $this->Form->input('Account.last_name');

        if(Configure::read('dob_min_year'))
        { $min_year = Configure::read('dob_min_year'); } else { $min_year = '1950'; }

        if(Configure::read('dob_max_year'))
        { $max_year = Configure::read('dob_max_year'); } else { $max_year = '2025'; }

        echo $this->Form->input(
            'Account.dob',
            array(
                'label' => __('Date of Birth'),
                'class' => 'text date',
                'minYear' => $min_year,
                'maxYear' => $max_year,
                'empty' => true,
            )
        );

        echo $this->Form->input('Account.ssn', array('label' => __('Social Security Number'), 'class' => 'text ssn', 'value' => '', 'autocomplete' => 'off'));

        echo $this->Form->input('Account.enabled', array('type' => 'hidden', 'value' => true));

        // group data
        echo $this->Form->input('Account.group_id', array('type' => 'select', 'options' => $groups, 'default' => 1));

        // account photo
    ?>
        <div class="form_item">
            <label for="AccountGroupId">Account Photo</label>
            <?php echo $this->element('Uploads.has_one_upload', array('config_key' => 'AccountPhoto')); ?>
        </div>
    <?php
        // do not send mail option
        $help_link = $this->Html->link('help', '/help_items/do_not_send_mail.html', array('class' => 'help_tag'));

        echo $this->Form->input(
            'Account.no_mail',
            array(
                'label' => 'Do Not Send Mail '. $help_link,
                'type' => 'checkbox',
                'value' => 1
            )
        );

        // do not allow public to contact this person
        $help_link = $this->Html->link('help', '/help_items/no_public_contact.html', array('class' => 'help_tag'));

        echo $this->Form->input(
            'Account.no_public_contact',
            array(
                'label' => 'Do Not Allow Public Contact ' . $help_link,
                'type' => 'checkbox',
                'value' => 1
            )
        );

        // account data
        if ($this->action == 'edit')
        {
            echo $this->Form->submit(__('Save'), array('class' => 'button submit'));
        }
    ?>
    </fieldset>
