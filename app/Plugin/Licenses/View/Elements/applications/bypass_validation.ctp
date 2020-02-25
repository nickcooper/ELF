<p><?php echo Configure::read('Configuration.bypass_validation_description'); ?></p>
<?php
    // bypass button
    echo $this->Html->link(
        ($bypass_validation ? 'Disable' : 'Enable') . ' Bypass',
        array(
            'plugin' => 'licenses',
            'controller' => 'applications',
            'action' => 'bypass',
            $bypass_validation_id,
            'return' => base64_encode($this->here)
        ),
        array(
            'class' => 'button',
            'escape' => false
        )
    );