<?php
    echo $this->Html->link(
        __('Add New Training Provider'),
        array(
            'plugin'     => 'searchable',
            'controller' => 'searchable',
            'action'     => 'add',
            'fp'         => $foreign_plugin,
            'fo'         => $foreign_obj,
            'return'     => base64_encode($this->here)
        ),
        array(
            'class' => 'button'
        )
    );
?>
