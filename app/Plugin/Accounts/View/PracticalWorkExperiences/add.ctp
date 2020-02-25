<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('ACCOUNT'); ?></span> <?php echo __('Practical Work Experience'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Practical Work Experience Information'); ?></h3>
                <?php echo $this->Form->create('PracticalWorkExperience'); ?>
            <?php 
                if (GenLib::isData($this->data, 'PracticalWorkExperience', array('id'))) :
                    echo $this->Form->input('PracticalWorkExperience.id', array('type' => 'hidden'));
                endif; 
            ?>
            <fieldset>
                <legend>Practical Work Experience</legend>
                <div class="form_item">
                    <label for="practical_work_experience">Practical Work Experience Type</label>
                    <div class="input_holder">
                        <?php
                            echo $this->Form->input(
                                'PracticalWorkExperience.practical_work_experience_type_id',
                                array(
                                    'type' => 'select',
                                    'options' => $practical_work_experience_types,
                                    'empty' => '-- Select --',
                                    'label' => false,
                                    'id' => 'practical_work_experience_type_select'
                                )
                            );
                        ?>
                    </div>
                </div>

                <?php echo $this->Form->input('PracticalWorkExperience.description', array('label' => 'Description')); ?>
                <?php echo $this->Form->input('PracticalWorkExperience.months', array('label' => 'Months of experience')); ?>

            </fieldset>
        <?php echo $this->Form->end('Save'); ?>
        </div>
    </div>
</div>