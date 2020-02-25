<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">Course Section</span> <?php echo $this->data['CourseSection']['label']; ?></h3>
			<p class="bottom">
                <?php echo $this->Html->link('< Back to Listing', array(
                    'plugin' => 'continuing_education',
                    'controller' => 'course_sections',
                    'action' => 'view',
                    $this->data['CourseSection']['id'],
                ));?>
            </p>
		</div>
		<div id="section_nav_holder">    
            <ul id="section_nav">
                <li>
                    <?php 
                        echo $this->Html->link(
                            'Add Student Photo',
                            array(
                                'plugin' => 'uploads',
                                'controller' => 'uploads',
                                'action' => 'add',
                                'fp' => 'Accounts',
                                'fo' => 'Account',
                                'fk' => $this->data['Account']['id'],
                                'type' => 'AccountPhoto',
                                'return' => base64_encode($this->here)
                            )
                        );
                    ?>
                </li>
            </ul>
        </div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<?php echo $this->Form->create('CourseRoster', array('enctype' => 'multipart/form-data'));?>
        <?php
        if (!empty($this->data['CourseRoster']['id']))
        {
            echo $this->Form->hidden('CourseRoster.id');
        }
        ?>
		<h3>Edit Student</h3>
		<fieldset>
			<legend>Student Information</legend>
            <span class="attn iconify user"><?php echo $this->data['Account']['label'];?></span>
		</fieldset>

        <fieldset>
            <legend>Course Performance</legend>
            <table class="input_table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Attempt</th>
                        <th>Test Date</th>
                        <th>Test Result</th>
                        <th>Test Grade</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($editable): ?>
                    <?php for($i=0; $i < $test_attempts; $i++): ?>
                        <tr id='attempt_<?php echo $i +1?>'>
                            <td><?php echo $i+1?></td>
                            <td>
                                <?php
                                if (!empty($this->data['ExamScore'][$i]['id']))
                                {
                                    echo $this->Form->hidden("ExamScore.{$i}.id");
                                }
                                if ($i == 0)
                                {
                                    echo $this->Form->input("ExamScore.{$i}.exam_date", array('label' => false, 'empty' => true, 'default' => $this->data['CourseSection']['end_date']));
                                }
                                else
                                {
                                    echo $this->Form->input("ExamScore.{$i}.exam_date", array('label' => false, 'empty' => true));
                                } 

                                ?>
                            </td>
                            <td><?php echo $this->Form->input("ExamScore.{$i}.pass", array('options' => array('' => '-- Select --', 1 => 'Pass', 0 => 'Fail'), 'label' => false)) ?></td>
                            <td><?php echo $this->Form->input("ExamScore.{$i}.score", array('label' => false, 'class' => 'text span-2')) ?>
                        </tr> 
                    <?php endfor ?>
                <?php else: ?>
                    <?php foreach ($this->data['ExamScore'] as $i => $exam_score): ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $exam_score['exam_date']; ?></td>
                            <td><?php echo $exam_score['pass'] ? 'Passed' : 'Failed'; ?></td>
                            <td><?php echo $exam_score['score']; ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </fieldset>
            
		
        <div class="actions">
            <?php echo $this->Html->link(__('Remove Student from Roster'), array('action' => 'delete', $this->data['CourseRoster']['id']), array('class' => 'button delete rightify'), __('Are you sure you want to remove student "%s" from the course roster?', $this->data['Account']['label'])); ?>
            <?php echo $this->Form->submit('Save', array('stripped' => true, 'after' => '', 'name' => 'save')); ?>
            <?php if ($editable): ?>
                <?php echo $this->Form->submit('Save & Complete', array('stripped' => true, 'after' => '', 'name' => 'complete')); ?>
                <?php //echo $this->Html->link(__('Complete'), array('action' => 'complete', $this->data['CourseRoster']['id']), array('class' => 'button'), __('Are you sure you want to complete the course for "%s"? All further editing will be disabled, and cannot be reopened.', $this->data['Account']['label'])); ?>
                <?php echo $this->Html->link(__('Cancel'), (isset($this->params['named']['return']))  ? base64_decode($this->params['named']['return']) : Controller::referer(), array('class' => 'button cancel')); ?>
            <?php else: ?>
                <?php echo $this->Html->link(__('Download Completion Certificate'), $completion_cert_link, array( 'class' => 'button',)); ?>
            <?php endif ?>
        </div>
        
		<?php echo $this->Form->end();?>
	</div>
</div>
