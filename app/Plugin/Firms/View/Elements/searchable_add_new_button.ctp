
<?php
    echo $this->Html->link(
        'Add New Firm',
        array(
            'plugin' => 'licenses',
            'controller' => 'licenses',
            'action' => 'add',
            2, // license type id for firms
            'return' => base64_encode($this->here)
        ),
        array('class' => 'button')
    );
?>