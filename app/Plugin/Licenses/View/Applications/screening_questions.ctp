<div id="body" class="span-24">
    <!-- InstanceBeginEditable name="Main Content" -->
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3>
                    <span class="pre"><?php echo $application['License']['LicenseType']['label']; ?> #</span>
                    <?php echo $application['License']['license_number']; ?>
                </h3>
                <p class="bottom"><?php echo $this->Html->returnLink('/licenses/licenses/index', 'Licenses'); ?></p>
            </div>
        </div>
    </div>
    <div id="section" class="span-19 last">
        <div class="pad">
            <?php echo $this->Form->create('Question'); ?>
                <fieldset>
                    <legend>Screening Questions</legend>
                    <?php foreach ($application['License']['LicenseType']['ScreeningQuestion'] as $question) : ?>
                    <a name="<?php echo $question['id']; ?>"></a>
                    <div class="form_item">
                        <label for="question_1"><?php echo $question['question']; ?></label>
                        <div class="input_holder">
                            <?php
                                // define the value
                                $value = 0;
                                if (GenLib::isData($question, 'ScreeningAnswer.0', array('answer')))
                                {
                                    $value = $question['ScreeningAnswer'][0]['answer'];
                                }

                                echo $this->Form->select(
                                    sprintf('Application.ScreeningQuestion.%s.answer', $question['id']),
                                    array(
                                        1 => 'Yes',
                                        0 => 'No'
                                    ),
                                    array(
                                        'empty' => false,
                                        'value' => $value,
                                        'rel' => $question['correct_answer'],
                                        'class' => 'toggleMore'
                                    )
                                );

                                // decide if the comment field should be visible
                                $hideClass = ($value == $question['correct_answer']) ? ' hide' : '';

                                // display comment field
                                echo $this->Form->input(
                                    sprintf('Application.ScreeningQuestion.%s.comment', $question['id']),
                                    array(
                                        'type' => 'textarea',
                                        'label' => 'Please Explain',
                                        'value' => (GenLib::isData($question, 'ScreeningAnswer.0', array('comment')) ? $question['ScreeningAnswer'][0]['comment'] : ''),
                                        'class' => 'short',
                                        'div'   => array(
                                            'class' => 'form_item more' . $hideClass
                                        )
                                    )
                                );
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php echo $this->Form->end(__('Submit', true)); ?>
        </div>
    </div>
</div>
