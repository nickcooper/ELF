<?php echo $this->Form->create('Education', array('type' => 'file'));?>
    <fieldset>
        <legend>Education History</legend>
        <div class="form_item">
            <label for="highest_earned">Highest Completed Education</label>
            <div class="input_holder">
                <?php
                    echo $this->Form->input(
                        'EducationDegree.label',
                        array(
                            'type' => 'select',
                            'options' => array_merge($degrees, array('Other')),
                            'empty' => '-- Select --',
                            'label' => false,
                            'id' => 'degree_select'
                        )
                    );
                ?>
                <div id="other_degree_type" class="hide">
                    <?php echo $this->Form->input('other_degree', array('label' => 'Other degree')); ?>
                </div>
            </div>
        </div>
        <div class="form_item">
            <label for="highest_earned_upload">Upload Transcript</label>
            <?php echo $this->element('Uploads.has_one_upload', array('config_key' => 'EducationDegree')); ?>
        </div>
    </fieldset>

<?php echo $this->Form->end('Save');?>