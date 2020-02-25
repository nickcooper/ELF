<fieldset>
<?php
    // personal data
    echo $this->Form->input('Account.first_name');
    echo $this->Form->input('Account.middle_initial');
    echo $this->Form->input('Account.last_name');
    echo $this->Form->input('Account.dob', array('label' => 'Date of Birth', 'class' => 'text date', 'minYear' => date('Y') - 95, 'maxYear' => date('Y') - 18));
    echo $this->Form->input('Account.ssn', array('label' => 'Social Security Number', 'autocomplete' => 'off'));
?>
</fieldset>