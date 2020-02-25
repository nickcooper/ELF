<?php echo $this->Form->create('Page');?>
    <fieldset>
        <legend>Page Information</legend>
    <?php
        if ($this->action == 'edit')
        {
            echo $this->Form->input('Page.id');
        }
        
        // program data
        echo $this->Form->input('Page.program_id', array('type' => 'radio', 'options' => $programs));
        
        echo $this->Form->input('Page.title');
        
        if ($this->action == 'add')
        {
            echo $this->Form->input('Page.slug', array('class' => 'text slugify', 'rel' => 'input#PageTitle'));
        }
        
        echo $this->Form->textarea('Page.content');
        
        echo $this->Html->enableButton($this->params, $this->data);
    ?>
    </fieldset>
    <div class="actions">
        <?php echo $this->Form->submit('Save', array('class' => 'button submit')); ?>
        <?php echo ($this->action == 'edit') ? $this->Html->link(__('Delete', true), array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'delete', $this->Form->value('Page.id')), array('class' => 'button severe'), sprintf(__('Are you sure you want to delete page # %s?', true), $this->Form->value('Page.id'))) : ''; ?>
    </div>
<?php echo $this->Form->end();?>
