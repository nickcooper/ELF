<ul class="button_select_list">
    <li>
        <a href="#"><?php echo __('Add New Instructor'); ?></a>
        <ul style="display: none; ">
        <?php foreach ($programs as $id => $label): ?>
            <li>
            <?php
                echo $this->Html->link(
                    $label,
                    array(
                        'plugin'     => 'continuing_education',
                        'controller' => 'instructors',
                        'action'     => 'add',
                        $id,
                        'return'     => base64_encode($this->here),
                    )
                );
            ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </li>
</ul>
