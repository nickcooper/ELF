<div id="body" class="span-24">

    <?php echo $this->element('section_heading'); ?>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Choose Associated Firm'); ?></h3>

            <?php
                echo $this->Form->create('Abatement');

                echo $this->Form->input(
                    'Abatement.firm_id',
                    array(
                        'label'   => __('Associate Firms'),
                        'options' => $firm_list,
                        'empty'   => __('- Choose -'),
                        'div'     => array(
                            'class' => 'form_item span-3',
                            'id'    => 'state_holder',
                        ),
                    )
                );

                echo $this->Form->end(__('Save'));
            ?>
        </div>
    </div>
</div>