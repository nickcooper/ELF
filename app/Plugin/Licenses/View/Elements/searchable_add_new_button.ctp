<ul class="actions_list">
    <li>
        <div class="parent_holder">
            <?php
                echo $this->Html->link(
                    '<i class="icon-plus"></i><i class="icon-minus"></i>Apply for New License',
                    '#',
                    array(
                        'class' => 'toggle',
                        'rel'   => '#searchable_add',
                        'escape'=> false,
                    )
                );
            ?>
        </div>
        <ul id="searchable_add" class="hide">
            <div class="text" style="float:left;">
            <?php
                foreach ($license_types as $id => $label) :
                    echo $this->Html->link(
                        $label,
                        array(
                            'plugin' => 'licenses',
                            'controller' => 'licenses',
                            'action' => 'add',
                            $id,
                        ),
                        array(
                            'title' => $label,
                            'class' => 'button small'
                        )
                    );
                endforeach;
            ?>
            </div>
            <br style="clear:both" />
        </ul>
    </li>
</ul>