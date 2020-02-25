<h3>Suspend License</h3>

<p>Please explain why you wish to suspend this license. Click the <em>Confirm</em> button to complete the process.</p>

<?php
    echo $this->Form->create('Suspend');

    // include notes
    echo $this->Form->input(
        'Note.0.note',
        array(
            'label' => __('Notes'),
            'type'  => 'textarea',
            'class' => 'span-x med elastic',
        )
    );

    echo $this->Form->end('Suspend');
?>