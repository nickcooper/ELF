<div id="pre" class="span-5">
    <div id="section_head" class="black-box">
        <h3><?php echo __('Abatement Notices'); ?></h3>
        <p class="bottom"><?php echo __('Manage Abatement Notices'); ?></p>
    </div>

    <div id="section_nav_holder">
        <ul id="section_nav">
            <li>
                <?php
                    echo $this->Html->link(
                        __('Abatements'),
                        array(
                            'plugin'     => 'searchable',
                            'controller' => 'searchable',
                            'action'     => 'index',
                            'fp'         => 'Abatements',
                            'fo'         => 'Abatement',
                        )
                    );
                ?>
            </li>
            <li>
                <?php
                    echo $this->Html->link(
                        __('Abatement Submissions'),
                        array(
                            'plugin'     => 'searchable',
                            'controller' => 'searchable',
                            'action'     => 'index',
                            'fp'         => 'Abatements',
                            'fo'         => 'AbatementSubmission',
                        )
                    );
                ?>
            </li>
        </ul>
    </div>
</div>