<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <?php echo $this->element('section_heading'); ?>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Abatement Phases'); ?></h3>
            <h3><?php echo sprintf(__('Abatement %s'), $abatement['Abatement']['abatement_number']); ?></h3>

            <?php echo $this->Form->create(); ?>
            <fieldset>
                <legend><?php echo __('Edit Phase'); ?></legend>
                <div class="input_holder" class="">
                    <?php echo $this->Form->input('AbatementPhase.begin_date'); ?>
                </div>
                <div class="input_holder" class="">
                    <?php echo $this->Form->input('AbatementPhase.end_date'); ?>
                </div>
            </fieldset>
            <?php echo $this->Form->end(__('Submit')); ?>
        </div>
    </div>
</div>