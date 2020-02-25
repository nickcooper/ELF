<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">Course Roster</span>Edit Expire Date</h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
        <h3>Edit Course Roster Expire Date</h3>

        <table>
            <tr>
                <td>Student Name</td>
                <td><?php echo $roster['Account']['label']; ?></td>
            </tr>
            <tr>
                <td>Student Number</td>
                <td><?php echo $roster['CourseRoster']['student_number']; ?></td>
            </tr>
            <tr>
                <td>Course Section</td>
                <td><?php echo $roster['CourseSection']['label']; ?></td>
            </tr>
        </table>

        <h4>Disclaimer</h4>

        <p>By changing the current expire date to a different date you could be altering other dates that rely on this expire date. After you have changed this date all licenses that are affected by this expire date will be flagged as manually edited.</p>

		<?php echo $this->Form->create('CourseRoster'); ?>

        <?php echo $this->Form->input("CourseRoster.expire_date", array('label' => 'Expire Date', 'type' => 'date', 'empty' => true)); ?>

        <div class="actions">
            <?php echo $this->Form->submit('Save', array('stripped' => true, 'after' => '', 'name' => 'save')); ?>
        </div>
        
		<?php echo $this->Form->end();?>
	</div>
</div>
