        <?php echo $this->Form->create('Education', array('type' => 'file')); ?>
            <?php 
                if (GenLib::isData($this->data, 'EducationDegree', array('id'))) :
                    echo $this->Form->input('EducationDegree.id', array('type' => 'hidden'));
                endif; 
            ?>
            <fieldset>
                <legend>Education Degree/Diploma/Certification</legend>
                <div class="form_item">
                    <label for="highest_earned">Degree/Diploma/Certification Type</label>
                    <div class="input_holder">
                        <?php
                            echo $this->Form->input(
                                'EducationDegree.degree_id',
                                array(
                                    'type' => 'select',
                                    'options' => $degrees,
                                    'empty' => '-- Select --',
                                    'label' => false,
                                    'id' => 'degree_select'
                                )
                            );
                        ?>
                    </div>
                </div>
                <?php
                    echo $this->Form->input(
                        'EducationDegree.school_name', 
                        array(
                            'label' => 'Name of School or Trade Association', 
                            'class' => 'text span-10'
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input(
                        'EducationDegree.start_date', 
                        array(
                            'label' => 'Start Date'
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input(
                        'EducationDegree.end_date', 
                        array(
                            'label' => 'End Date'
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input(
                        'EducationDegree.certified_date', 
                        array(
                            'label' => 'Certification Date'
                        )
                    );
                ?>
                
                <?php if (GenLib::isData($this->data, 'Upload', array('id'))) : ?>
                    <div class="form_item">
                        <label for="highest_earned_upload">Current Transcript</label>
                        <div class="input_holder">
                            <?php
                                echo $this->Html->link(
                                    'View Current Transcript',
                                    sprintf('/files/%s', $this->data['Upload']['file_name']),
                                    array(
                                        'title' => 'Upload',
                                        'target' => '_blank',
                                        'class' => 'iconify pdf',
                                    )
                                );
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form_item">
                    <label for="highest_earned_upload">Upload/Replace Transcript</label>
                    <?php echo $this->element('upload', array('config_key' => 'Upload', 'parent' => $parent, 'label' => 'Education Document'), array('plugin' => 'Uploads')); ?>
                </div>
                <div class="form_item">
                <?php
                    echo $this->Form->input(
                            'EducationDegree.highest_earned', 
                            array(
                                'label' => 'Highest Earned',
                                'type' => 'checkbox',
                                'after' => 'Make this the Highest Earned Degree/Diploma/Certification'
                            )
                        );
                ?>
                </div>
                
            </fieldset>

            <fieldset id="address_set">
                <legend>Address Information</legend>
                <?php 
                    echo $this->Form->input('Address.id', array('type' => 'hidden'));
                    echo $this->element('AddressBook.address_form_short'); 
                ?>
            </fieldset>
        <?php echo $this->Form->end('Save'); ?>