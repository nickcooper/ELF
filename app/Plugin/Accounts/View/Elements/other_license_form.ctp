<?php echo $this->Form->create('OtherLicense'); ?>

    <fieldset>
        <legend>License Information</legend>
        <?php
            echo $this->Form->input('OtherLicense.id', array('type' => 'hidden'));
            echo $this->Form->input('OtherLicense.label', array('label' => 'License Name', 'type' => 'text'));
            echo $this->Form->input('OtherLicense.license_number', array('label' => 'License Number', 'type' => 'text'));
            echo $this->Form->input('OtherLicense.jurisdiction', array('label' => 'Licensing Jurisdiction', 'type' => 'text'));
            echo $this->Form->input(
                'OtherLicense.issue_date',
                array(
                    'label' => __('License Issue Date'),
                    'class' => 'text date',
                    'minYear' => date('Y') - 10,
                    'maxYear' => date('Y'),
                    'empty' => true,
                )
            );
            echo $this->Form->input(
                'OtherLicense.expire_date',
                array(
                    'label' => __('License Expiration Date'),
                    'class' => 'text date',
                    'minYear' => date('Y') - 10,
                    'maxYear' => date('Y') + 10,
                    'empty' => true,
                )
            );
            
            echo $this->Form->input('OtherLicense.obtained_by_exam', array('label' => 'Obtained By Examination?', 'type' => 'select', 'options' => array(1 => 'Yes', 0 => 'No'), 'default' => 0));
        ?>
    </fieldset>
    
<?php echo $this->Form->end('Save'); ?>
