<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">New</span> Program Group</h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to Program Group Listing', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'index'));?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>New Program Group</h3>
        <?php echo $this->element('group_form'); ?>
    </div>
</div>