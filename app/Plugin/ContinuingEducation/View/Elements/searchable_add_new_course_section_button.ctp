<?php
    echo $this->Html->link(
        __('Add New Course Section'),
        array(
            'plugin'     => 'continuing_education',
            'controller' => 'course_sections',
            'action'     => 'add',
            'return'     => base64_encode($this->here)
        ),
        array(
            'class' => 'button'
        )
    );
?>
