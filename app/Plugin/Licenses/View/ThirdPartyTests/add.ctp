<div id="body" class="span-24">

    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('Application'); ?></span> <?php echo __('Third Party Tests'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Add Third Party Test'); ?></h3>
            <?php echo $this->Form->create('ThirdPartyTest', array('type' => 'file')); ?>

                <fieldset>
                    <legend><?php echo __('Third Party Test Details'); ?></legend>
                    <?php
                        echo $this->Form->input(
                            'ThirdPartyTest.testing_center',
                            array(
                                'label' => 'Testing Center Name',
                                'class' => 'text span-6',
                                'div'   => array('class' => 'form_item span-7'),
                            )
                        );

                        echo $this->Form->input(
                            'ThirdPartyTest.date',
                            array(
                                'label' => 'Test Date',
                                'type'    => 'date',
                                'minYear' => date('Y') - 40,
                                'maxYear' => date('Y'),
                                'div'     => array('class' => 'form_item span-6'),
                            )
                        );

                        echo $this->Form->input(
                            'ThirdPartyTest.score',
                            array(
                                'class' => 'text span-2',
                                'div'   => array('class' => 'form_item span-3'),
                            )
                        );

                        echo $this->Form->input(
                            'ThirdPartyTest.pass',
                            array(
                                'type'    => 'select',
                                'options' => array('1' => __('Pass'), '0' => __('Fail')),
                                'empty'   => __('-- Select --'),
                                'label'   => __('Pass/Fail'),
                                'div'     => array('class' => 'form_item span-8 last'),
                            )
                        );
                    ?>
                </fieldset>

                <fieldset>
                    <legend><?php echo __('Supporting Documents'); ?></legend>

                    <?php echo $this->element('Uploads.upload', array('config_key' => 'Upload')); ?>
                </fieldset>

            <?php echo $this->Form->end(__('Save')); ?>
        </div>
    </div>
</div>