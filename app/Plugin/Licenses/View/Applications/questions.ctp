<div id="body" class="span-24">
    <!-- InstanceBeginEditable name="Main Content" -->
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3>
                    <span class="pre"><?php echo $application['License']['LicenseType']['label']; ?> #</span> 
                    <?php echo $application['License']['license_number']; ?>
                </h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>
    <div id="section" class="span-19 last">
        <div class="pad">
            <?php echo $this->Form->create('License'); ?>
                <fieldset>
                    <legend>General Questions</legend>
                    <?php
                        foreach($application['License']['LicenseType']['Question'] as $question):
                    ?>
                    <a name="<?php echo $question['id']; ?>"></a>
                    <div class="form_item">
                        <label for="question_1"><?php echo $question['question']; ?></label>
                        <div class="input_holder">
                            <?php
                                // define the value
                                $value = null;
                                if (GenLib::isData($question, 'QuestionAnswer.0', array('answer')))
                                {
                                    $value = $question['QuestionAnswer'][0]['answer'];
                                }
                                
                                echo $this->Form->textarea(
                                    sprintf('Application.Question.%s', $question['id']), 
                                    array('class' => 'span-x med', 'value' => $value)
                                ); 
                            ?>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    ?>
                </fieldset>
            <?php echo $this->Form->end(__('Submit', true)); ?>
        </div>
    </div>
</div>
