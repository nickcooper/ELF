<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">## NEED DATA ##</span> ## NEED DATA ##</h3>
            <p class="bottom"><?php echo $this->Html->returnLink(); ?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <?php echo $this->Form->create('Course');?>
        <h3>Edit Course</h3>
        <?php echo $this->Form->hidden('Course.id');?>
        <?php echo $this->element('form_course'); ?>
        <?php echo $this->Form->end('Save');?>
    </div>
</div>
