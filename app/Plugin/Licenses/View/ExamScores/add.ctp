<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo __('ACCOUNT'); ?></span> <?php echo __('Exam Score'); ?></h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Exam Score Information'); ?></h3>
                <?php echo $this->Form->create('ExamScore'); ?>
            <?php 
                if (GenLib::isData($this->data, 'ExamScore', array('id'))) :
                    echo $this->Form->input('ExamScore.id', array('type' => 'hidden'));
                endif; 
            ?>
            <fieldset>
                <legend>Exam Score Information</legend>

                <?php echo $this->Form->input("ExamScore.exam_date", array('label' => 'Exam Date', 'type' => 'date')); ?>
                <?php echo $this->Form->input('ExamScore.score', array('label' => 'Exam Score', 'class' => 'text span-2')); ?>

                <?php echo $this->Form->input('ExamScore.sponsored', 
                        array(
                            'label' => 'State Sponsored?',
                            'type' => 'checkbox',
                            'after' => 'Is this exam state sponsored?'
                        )
                    );
                ?>
            </fieldset>
        <?php echo $this->Form->end('Save'); ?>
        </div>
    </div>
</div>