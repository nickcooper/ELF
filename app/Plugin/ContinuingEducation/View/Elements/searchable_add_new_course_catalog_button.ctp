<?php
    echo $this->Html->link(
        __('Add New Course Catalog'),
        array(
            'plugin'     => 'continuing_education',
            'controller' => 'course_catalogs',
            'action'     => 'add',
            'return'     => base64_encode($this->here)
        ),
        array( 
            'class' => 'button'
        )
    );
?>
