<?php
$license_type = $this->request->data['LicenseType'];
?>
<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">License Type</span> <?php echo $license_type['label']; ?></h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to License Type Overview', array('plugin' => 'licenses', 'controller' => 'license_types', 'action' => 'view', $license_type['id'])); ?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>License Type Information</h3>
        <?php echo $this->Form->create('LicenseType');?>            

        <fieldset>
            <legend>Edit Information</legend>
            
            <?php
                echo $this->Form->input('LicenseType.id');
                echo $this->Form->input('LicenseType.type');
                echo $this->Form->input('LicenseType.descr', array('type' => 'textarea', 'label' => 'Description', 'class' => 'text short'));
            ?>
        </fieldset>
        
        <div class="actions">
            <?php echo $this->Form->submit('Save'); ?>
        </div>

        <?php echo $this->Form->end();?>
    </div>
</div>