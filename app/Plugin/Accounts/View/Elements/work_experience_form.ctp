<?php echo $this->Form->create('WorkExperience'); ?>

    <fieldset>
        <legend>Company Information</legend>
        <?php
            echo $this->Form->input('WorkExperience.id', array('type' => 'hidden'));
            echo $this->Form->input('WorkExperience.employer', array('label' => 'Company', 'type' => 'text'));
            echo $this->Form->input('WorkExperience.position', array('label' => 'Job Title', 'type' => 'text'));
        ?>
    </fieldset>
    
    <fieldset id="address_set">
        <legend>Address Information</legend>
        <?php 
            echo $this->Form->input('Address.id', array('type' => 'hidden'));
            echo $this->element('AddressBook.address_form_short'); 
        ?>
    </fieldset>
    
    <fieldset id="phone_set">
        <legend>Phone</legend>
        <?php
            echo $this->Form->input('Address.phone1', array('label' => 'Phone Number', 'type' => 'phone', 'class' => 'text span-5 phone'));
            echo $this->Form->input('Address.phone2', array('label' => 'Phone Number', 'type' => 'phone', 'class' => 'text span-5 phone'));
        ?>
    </fieldset>
    
    <fieldset>
        <legend>Employment Details</legend>
        <?php
            echo $this->Form->input('WorkExperience.supervisor_name', array('label' => 'Supervisor\'s Name'));
            echo $this->Form->input('WorkExperience.supervisor_phone', array('label' => 'Supervisor\'s Number', 'type' => 'phone', 'class' => 'text span-5 phone'));
            
            echo $this->Form->input('WorkExperience.start_date',
                array(
                    'label' => 'Beginning Date of Employment',
                    'minYear' => 1940,
                    'maxYear' => date('Y')
                )
            );
            echo $this->Form->input('WorkExperience.end_date', 
                array(
                    'label' => 'Ending Date of Employment', 
                    'minYear' => 1940,
                    'maxYear' => date('Y'),
                    'after' => $this->Form->input(
                        'WorkExperience.current', 
                        array(
                            'label' => false, 
                            'type' => 'checkbox', 
                            'value' => 0,
                            'after' => 'Currently Employed'
                        )
                    )
            ));
            
            echo $this->Form->input('WorkExperience.hrs_per_week', array('label' => 'Average Hours Worked Per Week', 'class' => 'text span-2'));
            
            echo $this->Form->input(
                'WorkExperienceType',
                array(
                    'options' => $work_experience_types,
                    'type' => 'select',
                    'label' => 'Type of Work Experience',
                    'multiple' => 'checkbox',
                )
            );
            
            echo $this->Form->input('WorkExperience.descr', array('type' => 'textarea', 'label' => 'Describe Relevant Job Activities', 'class' => 'span-x med'));
        ?>
    </fieldset>
    
<?php echo $this->Form->end('Save'); ?>
