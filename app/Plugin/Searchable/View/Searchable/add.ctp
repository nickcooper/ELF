<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><?php echo $title; ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
        <h2><?php echo h($title); ?></h2>

            <?php echo $this->Form->create($foreign_obj, array('type' => 'file')); ?>

                <?php echo $this->element($add_form_element, array(), array('plugin' => $foreign_plugin)); ?>

            <?php echo $this->Form->end(__('Save')); ?>
        </div>
    </div>
</div>