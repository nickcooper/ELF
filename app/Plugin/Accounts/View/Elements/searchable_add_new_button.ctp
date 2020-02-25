<?php
    echo $this->html->link(
        'Add New Account',
        array(
            'plugin' => 'searchable',
            'controller' => 'searchable',
            'action' => 'add',
            'fp' => 'Accounts',
            'fo' => 'Account',
            'return' => base64_encode($this->here)
        ),
        array('class' => 'button')
    );
?>
