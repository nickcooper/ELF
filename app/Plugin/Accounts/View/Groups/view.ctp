<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">Program Group</span> <?php echo $group['Group']['label'] ?></h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to Program Group Listing', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'index')); ?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
        <div id="Group_Information" class="form_section">
            <h3>Group Information</h3>
            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                    <tr>
                        <th width="120" scope="row">Name</th>
                        <td><?php echo $group['Group']['label']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row">Group Description</th>
                        <td><?php echo $group['Group']['descr']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row">Program</th>
                        <td><?php echo ($group['Program']['label'] != '') ? $group['Program']['label']: '<span class="hilight">none</span>'; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row">Enabled</th>
                        <td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $group); ?></td>
                    </tr>
                </table>
            </div>
            <div class="span-5 last">
                <div class="actions"><?php echo $this->Html->link('Edit Group Information', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'edit', $group['Group']['id']), array('class' => 'button small')); ?></div>
            </div>
        </div>
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'accounts', 'controller' => 'groups', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
    </div>
</div>
