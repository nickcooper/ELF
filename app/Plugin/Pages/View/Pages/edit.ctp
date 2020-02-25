<?php
$page = $this->request->data['Page'];
?>
<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">Page</span> <?php echo $page['title']; ?></h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to Page View', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'view', $page['id']));?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3>Edit Page</h3>
        <?php echo $this->element('page_form'); ?>
    </div>
</div>