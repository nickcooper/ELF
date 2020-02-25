<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('Add Firm Information'); ?></h3>

        <?php echo $this->Form->create('Firm'); ?>

        <?php echo $this->element('firm_form'); ?>

        <?php echo $this->Form->end(__('Save')); ?>

    </div>
</div>