<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><?php echo __('New Account'); ?></h3>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('New Account Information'); ?></h3>
        <fieldset>
            <legend><?php echo sprintf(__('%s Account'), Inflector::humanize($this->action)); ?></legend>
            <p class="attn" style="color:black">
                <?php echo __('Welcome! We couldn\'t find a account that matches your AA information.'); ?>
                <?php echo __('If this is your first login, we invite you to register for an account.'); ?>
                <?php echo __('Please provide some additional information to complete this registration process.'); ?>
            </p>

            <span><strong><?php echo __('Account Information'); ?>:</strong></span>
            <table border="0" class="light_data">
                <tbody>
                    <tr>
                        <td><?php echo __('Username'); ?>:</td>
                        <td><?php echo h($user['user_id']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('First Name'); ?>:</td>
                        <td><?php echo h($user['first_name']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Last Name'); ?>:</td>
                        <td><?php echo h($user['last_name']); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Email Address'); ?>:</td>
                        <td><?php echo h($user['email']); ?></td>
                    </tr>
                </tbody>
            </table>

        <?php
            echo $this->Form->create();

            echo $this->Form->input(
                'Account.ssn',
                array(
                    'label' => __('Social Security Number'),
                    'class' => 'text ssn',
                    'value' => '',
                    'autocomplete' => 'off'
                )
            );

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

            echo $this->Form->submit(
                __('Register My Account'),
                array(
                    'class' => 'button submit',
                    'after' => sprintf(
                        '&nbsp;%s',
                        $this->Html->link(__('Cancel'), array('action' => 'logout'), array('class' => 'button cancel'))
                    ),
                )
            );
            echo $this->Form->end();
        ?>

        </fieldset>
    </div>
</div>
