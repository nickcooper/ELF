    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre">Instructor</span> 
                    <?php
                    if (!empty($instructor['Account']['label']))
                    {
                        echo $instructor['Account']['label'];
                    }
                    ?>
                </h3>
                <p class="bottom"><?php echo $this->Html->link('< Back to Listing', array('action' => 'index'));?></p>
            </div>
        </div>
    </div>
    <div id="section" class="span-19 last">
        <div class="pad">
            <?php echo $this->element('instructor_edit_action_bar'); ?>
            <?php echo $this->element('Instructors/information') ?>
            <!-- Start Of License Info --> 
            <?php echo $this->element('license_info',
                array(
                    'label' => 'Instructor Licenses',
                    'required' => null,
                    'account' => $instructor['Account'],
                    'licenses' => $instructor['Account']['License'],
                ),
                array(
                    'plugin' => 'Accounts'
                )
            ); ?>
            <!-- close #account_licenses -->
            <!-- Start Of WorkExperience -->
            <?php echo $this->element('work_experience',
                array(
                    'label' => 'Instructor Work Experience',
                    'required' => null,
                    'account' => $instructor['Account'],
                    'experiences' => $instructor['Account']['WorkExperience']
                ),
                array(
                    'plugin' => 'Accounts'
                )
            ); ?>
            <!-- Close Of WorkExperience -->
            <!-- Start Of Education -->
            <?php echo $this->element('education',
                array(
                    'label' => 'Instructor Education',
                    'required' => null,
                    'account' => $instructor['Account'],
                    'education' => $instructor['Account']['EducationDegree']
                ),
                array(
                    'plugin' => 'Accounts'
                )
            ); ?>
            <!-- Close Of WorkEducation -->
            <?php echo $this->element('instructor_edit_action_bar'); ?>
        </div> 
    </div>
