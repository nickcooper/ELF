<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('Continuing Education'); ?></span> <?php echo __('Upload'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Upload Continuing Education Data'); ?></h3>
            <?php echo $this->Form->create(null, array('type' => 'file')); ?>
                <fieldset>
                    <legend>Upload Course File</legend>
                    <div class="form_item">
                        <?php echo $this->Form->input('upload', array('type' => 'file', 'label' => false)); ?>
                    </div>
                </fieldset>
            <?php echo $this->Form->end('Upload'); ?>
        </div>
    </div>
</div>