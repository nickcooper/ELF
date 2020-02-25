<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre">License</span> Variant</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3>License Variant Information</h3>
            
            <?php echo $this->Form->create('Variant', array('type' => 'file')); ?>
            <?php 
                if (GenLib::isData($this->data, 'Variant', array('id'))) :
                    echo $this->Form->input('Variant.id', array('type' => 'hidden'));
                endif; 
            ?>
            <fieldset>
                <legend>Variant History</legend>
                <div class="form_item">
                    <label for="variant_type">Variant Type</label>
                    <div class="input_holder">
                        <?php
                            echo $this->Form->input(
                                'Variant.id',
                                array(
                                    'type' => 'select',
                                    'options' => $variants,
                                    'empty' => '-- Select --',
                                    'label' => false,
                                    'id' => 'degree_select',
                                )
                            );
                        ?>
                        <div id="other_variant" class="hide">
                            <?php echo $this->Form->input('Variant.other', array('label' => 'Other Variant')); ?>
                        </div>
                    </div>
                </div>
                
                <?php if (GenLib::isData($this->data, 'Upload', array('id'))) : ?>
                    <div class="form_item">
                        <label for="variant_type_upload">Current Certificate</label>
                        <div class="input_holder">
                            <?php
                                echo $this->Html->link(
                                    'View Current Certificate',
                                    sprintf('/files/%s', $this->data['Upload']['file_name']),
                                    array(
                                        'title' => 'View Third Party Test Results',
                                        'target' => '_blank',
                                        'class' => 'iconify pdf',
                                    )
                                );
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form_item">
                    <label for="highest_earned_upload">Upload/Replace Certificate</label>
                    <?php echo $this->Form->hidden('Upload.label', array('value' => 'Certificate'));?>
                    <?php echo $this->element('has_one_upload', array('config_key' => 'Upload', 'parent' => $parent), array('plugin' => 'Uploads')); ?>
                </div>
                
            </fieldset>
            <?php echo $this->Form->end('Save'); ?>
        </div>
    </div>
</div>