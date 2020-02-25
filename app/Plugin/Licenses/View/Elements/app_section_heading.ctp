<div id="pre" class="span-5">
    <div id="section_head" class="black-box">
        <h3>Applications</h3>
        <p class="bottom">Manage License Applications</p>
    </div>
    <div id="section_nav_holder">
        <ul id="section_nav">
            <li><?php echo $this->Html->link('Applications', array('plugin' => 'licenses', 'controller' => 'applications', 'action' => 'index', '')); ?></li>
            <li>
                <?php echo $this->Html->link(
                     'Pending Queue <span class="count">('.$pending_count.')</span>',
                     array(
                         'plugin' => 'searchable',
                         'controller' => 'searchable',
                         'action' => 'index',
                         'fp' => 'licenses',
                         'fo' => 'application',
                         'application_status_id' => 3
                     ),
                     array('escape' => false)
                ); ?>
            </li>
        </ul>
    </div>
</div>