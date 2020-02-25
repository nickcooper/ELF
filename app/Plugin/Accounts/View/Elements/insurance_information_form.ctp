<?php echo $this->Form->create('InsuranceInformation', array('type' => 'file'));?>
    <fieldset>
        <legend><?php echo __('Insurance Information'); ?></legend>
    <?php
        echo $this->Form->input('InsuranceInformation.label', array('label' => 'Insurance Company Name'));
     
        echo $this->Form->input('InsuranceInformation.insurance_amount', array('type' => 'float', 'label' => 'Insurance Policy Amount'));
        echo $this->Form->input('InsuranceInformation.expire_date', array('type' => 'date', 'label' => 'Insurance Policy Expiration Date'));
    ?>

    <?php if($this->params['action'] == 'add') : ?>
        <div class="form_item">
            <label for="upload">Upload Insurance Document</label>
            <?php echo $this->element('upload', array('identifier' => 'Upload'), array('plugin' => 'Uploads')); ?>
        </div>
    <?php  endif; ?>

        <?php  if (GenLib::isData($insurances) && $insurances != null): ?>
        <div class="form_item">
            <label for="uploaded_documents">Previously Uploaded Insurance Documents</label>
        </div>
            <?php foreach($insurances as $upload): ?>

                <?php
                     echo $this->Html->link(
                        $upload['Upload']['label'],
                        DS.$upload['Upload']['file_path'].DS.$upload['Upload']['file_name'],
                        array(
                            'title' => __('View Document'),
                            'target' => '_blank',
                            'class' => 'inline_action',
                            'escape' => false
                        )
                    );
                ?><br />
            <?php endforeach; ?>
        <?php  endif; ?>
    </fieldset>

    <?php echo $this->Form->submit('Save', array('class' => 'button submit')); ?>

<?php echo $this->Form->end();?>