<?php
// check for data
if (GenLib::isData($questions, '0', array('id'))) :
?>
<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo  $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <div class="span-12">
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Question</th>
                    <th class="text_right">Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($questions as $question):

                        // check for answer
                        if (GenLib::isData($question, 'QuestionAnswer', array('id'))):
                            $answer = $this->Html->link(
                                '<i class="icon-eye-open"></i>&nbsp;View',
                                '#question_answer_' . $question['QuestionAnswer']['question_id'],
                                array(
                                    'class' => 'modal inline_action blue',
                                    'title' => 'View question answer',
                                    'escape' => false
                                )
                            );
                            $action_text = "Edit Answers";
                        else :
                            $answer = '<span class="blank">none<span>';
                            $action_text = "Add Answers";
                        endif;
                        ?>

                         <tr>
                            <td><em><?php echo $question['question']; ?></em></td>
                            <td class="text_center"><?php echo $answer; ?></td>
                        </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        <div class="answers_holder hide">
            <?php
                foreach($questions as $question):

                // check for answer
                if (GenLib::isData($question, 'QuestionAnswer', array('id'))):
                ?>

            <div id="<?php echo 'question_answer_' . $question['QuestionAnswer']['question_id']; ?>" class="answer">
                <h4><?php echo $question['question']; ?></h4>
                <?php echo $this->textProcessing->pbr($question['QuestionAnswer']['answer']); ?>
            </div>

                <?
                endif;
                ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->appLink(
                $action_text,
                array(
                    'plugin' => 'licenses',
                    'controller' => 'applications',
                    'action' => 'questions',
                    $application['id'],
                    'return' => $return,
                ),
                array('class' => 'button small'),
                false,
                $app_open
            );
        ?>
        </div>
    </div>
</div>
<?php endif; ?>