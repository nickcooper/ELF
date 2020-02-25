<?php echo $this->element('section_heading'); ?>

<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('Abatement Property Information'); ?></h3>

        <?php echo $this->Form->create(); ?>

        <?php
            // record ids
            if (GenLib::isData($this->data, 'Abatement', array('id'))):
                echo $this->Form->input('Abatement.id', array('type' => 'hidden'));
            endif;

            if (GenLib::isData($this->data, 'PropertyOwner', array('id'))):
                echo $this->Form->input('PropertyOwner.id', array('type' => 'hidden'));
            endif;

            if (GenLib::isData($this->data, 'Address', array('id'))):
                echo $this->Form->input('Address.id', array('type' => 'hidden'));
            endif;
        ?>

        <fieldset>
            <legend><?php echo __('Property Owner'); ?></legend>
            <?php
                echo $this->Form->input('PropertyOwner.first_name', array('label' => __('Owner Name'), 'placeholder' => __('First Name')));
                echo $this->Form->input('PropertyOwner.last_name', array('label' => false, 'placeholder' => __('Last Name')));
                echo $this->Form->input('PropertyOwner.phone', array('type' => 'phone', 'label' => __('Owner Phone')));
            ?>
        </fieldset>

        <fieldset>
            <legend><?php echo __('Property Details'); ?></legend>
            <?php
                echo $this->Form->input(
                    'Abatement.dwelling_year_built',
                    array(
                        'label'   => __('Year Built'),
                        'class'   => 'text date',
                        'minYear' => date('Y') - 1,
                        'maxYear' => date('Y'),
                        'default' => $this->data['Abatement']['dwelling_year_built'] ? $this->data['Abatement']['dwelling_year_built'] : '',
                    )
                );

                // address info
                echo $this->element('AddressBook.address_form_short');
            ?>
        </fieldset>

        <fieldset>
            <legend><?php echo __('Work Description'); ?></legend>
            <?php echo $this->Form->textarea('Abatement.work_description'); ?>
        </fieldset>

        <?php echo $this->Form->end(__('Save')); ?>

    </div>
</div>
