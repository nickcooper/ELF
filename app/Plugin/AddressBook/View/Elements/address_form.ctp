			<fieldset>
                <legend>Address</legend>
                
                <?php
                    echo $this->Form->input('Address.label', array('label' => 'Address Type', 'options' => array('Business Address' => 'Business Address', 'Home Address' => 'Home Address', 'Mailing Address' => 'Mailing Address', 'Other' => 'Other')));
                    //echo $this->Form->input('Address.attention', array('label' => 'Attention Of'));
                    
                    // pull in the short form here
                    echo $this->Element('AddressBook.address_form_short');

                    echo $this->Form->input(
                        'Address.primary_flag', 
                        array(
                            'label' => sprintf('Primary %s Address', $humanized_foreign_obj),
                            'type' => 'checkbox',
                            'after' => sprintf('Make this the primary address for this %s', $humanized_foreign_obj)
                        )
                    );
                ?>
            </fieldset>
            <fieldset>
                <legend>Phone Number</legend>
                <?php
                    echo $this->Form->input('Address.phone1', array('label' => 'Phone Number', 'type' => 'phone', 'class' => 'text span-5 phone'));
                    echo $this->Form->input('Address.fax', array('label' => 'Fax', 'type' => 'phone', 'class' => 'text span-5 phone'));
                ?>
            </fieldset>