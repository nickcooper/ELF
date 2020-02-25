<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre">RECIPROCAL</span> Course Information</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
            <div id="section_nav_holder">    
                <ul id="section_nav">
                    <li class="selected">
                        <?php
                            echo $this->Html->link(
                                'Edit Course Hours', 
                                array(
                                    'plugin' => 'licenses', 
                                    'controller' => 'reciprocals', 
                                    'action' => 'edit', 
                                    'fp' => $foreign_plugin,
                                    'fo' => $foreign_obj,
                                    'fk' => $foreign_key,
                                    'return' => base64_encode($this->here),
                                ), 
                                array()
                            ); 
                        ?>
                    </li>
                    <li>
                        <?php 
                            echo $this->Html->link(
                                'Add Student Photo',
                                array(
                                    'plugin' => 'uploads',
                                    'controller' => 'uploads',
                                    'action' => 'add',
                                    'fp' => 'Accounts',
                                    'fo' => 'Account',
                                    'fk' => $account_id,
                                    'type' => 'AccountPhoto',
                                    'return' => base64_encode($this->here)
                                )
                            );
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="section" class="span-19 last">
        <?php echo $this->Form->create('Education', array('type' => 'file')); ?>
            <div class="pad">
                <h3>Edit Course Hours</h3>
                
                <fieldset>
                    <legend>Provider Information</legend>
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.provider', array('label' => 'Training Provider')); ?>
                    </div>
                    <?php echo $this->Form->input('Address.id', array('type' => 'hidden', 'value' => $this->data['Address']['id'])); ?>
                    <?php echo $this->element('address_form_short', array(), array('plugin' => 'AddressBook')); ?>
                </fieldset>
                    
                <fieldset>
                    <legend>Course Information</legend>
                    
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.course_title', array('label' => 'Course Title')); ?>
                    </div>
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.hours', array('label' => 'Course Hours')); ?>
                    </div>
                    <div class="form_item">
                        <?php 
                            echo $this->Form->input(
                                'Reciprocal.pass', 
                                array(
                                    'label' => 'Course Pass/Fail', 
                                    'type' => 'select', 
                                    'options' => array('0' => 'Fail', '1' => 'Pass'), 
                                    'empty' => '-- Select --'
                                )
                            );
                        ?>
                    </div>
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.start_date', array('label' => 'Course Start Date', 'type' => 'date')); ?>
                    </div>
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.completed_date', array('label' => 'Course Completed Date', 'type' => 'date')); ?>
                    </div>
                    <div class="form_item">
                        <?php echo $this->Form->input('Reciprocal.expire_date', array('label' => 'Course Expiration Date', 'type' => 'date')); ?>
                    </div>
                    
                    <div class="form_item">
                        <label for="transcript">Upload Course Transcript</label>
                        <?php echo $this->element('upload', array('config_key' => 'Transcript'), array('plugin' => 'Uploads')); ?>
                    </div>
                </fieldset>
            </div>
        <?php echo $this->Form->end('Save'); ?>
    </div>
</div>