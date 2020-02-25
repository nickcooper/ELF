        <fieldset>
            <legend><?php echo __('Course Type'); ?></legend>
            <p class="attn"><?php echo h($this->data['CourseCatalog']['label']); ?></p>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Course Approval Date'); ?></legend>
            <div class="block_help">
                <?php
                    echo __(
                        'Course sections are not allowed to be created less than 30 days after the course is ' .
                        'initially approved for a training provider'
                    );
                ?>
            </div>
            <?php echo $this->Form->input('Course.approved_date'); ?>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Course Material'); ?></legend>
            <div class="block_help">
                <?php echo __('All non-IPDH course materials must be received and approved before the Training Provider license is issued.'); ?>&nbsp;
                <a href="#" class="help_link"><?php echo __('Submitting Documents to IDPH'); ?></a>
            </div>
            <?php
                echo $this->Form->radio(
                    'provider_materials',
                    array(
                        1 => __('IDPH-Provided Course Material'),
                        0 => __('Other Course Material'),
                    ),
                    array('legend' => __('Which course materials will be taught?'))
                );
            ?>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Course Tests'); ?></legend>
            <div class="block_help">
                <?php echo __('All non-IPDH course tests must be received and approved before the Training Provider license is issued.'); ?>&nbsp;
                <a href="#" class="help_link"><?php echo __('Submitting Documents to IDPH'); ?></a>
            </div>
            <?php
                echo $this->Form->radio(
                    'provider_tests',
                    array(
                        1 => __('IDPH-Provided Course Tests'),
                        0 => __('Other Course Tests'),
                    ),
                    array('legend' => __('Which course tests will be used?'))
                );
            ?>
        </fieldset>