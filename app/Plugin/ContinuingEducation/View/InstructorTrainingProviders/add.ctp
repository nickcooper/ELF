<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">##Action##</span> ##Context##</h3>
			<p class="bottom"><?php echo $this->Html->link('< Back to Listing', array('action' => 'index'));?></p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>Add Instructor</h3>
        <?php echo $this->Form->create('InstructorAssignment');?>
            <fieldset>
                <legend>Instructor Information</legend>
                <?php
                    echo $this->Form->input('InstructorAssignment.account_id', array('label' => 'Choose an Instructor', 'options' => $instructors));
                ?>
            </fieldset>
        <?php echo $this->Form->end('Save');?>
    </div>
</div>
