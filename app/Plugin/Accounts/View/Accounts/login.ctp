<?php
    echo $this->Session->flash('auth');
    
    echo $this->Form->create('Account');
    echo $this->Form->inputs(
        array(
            'legend' => __('Login', true),
            'Account.username',
            'Account.password'
        )
    );
        
    echo $this->Form->end('Login');
?>
