<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('ACCOUNT'); ?></span> <?php echo __('Education'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Account Education Information'); ?></h3>
            <?php echo $this->element($form_path ? $form_path : 'education_long_form', array('plugin' => 'account')); ?>
        </div>
    </div>
</div>