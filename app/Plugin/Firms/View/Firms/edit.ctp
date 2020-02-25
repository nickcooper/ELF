<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo __('Firm #'); ?></span>
                <?php echo h($firm['License']['license_number']); ?>
            </h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
        </div>
    </div>
</div>

<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('Edit Firm Information'); ?></h3>

        <?php echo $this->Form->create('Firm'); ?>

        <?php echo $this->element('firm_form'); ?>

        <?php echo $this->Form->end(__('Save')); ?>

    </div>
</div>