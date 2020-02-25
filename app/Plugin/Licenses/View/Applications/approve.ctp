<?php
    // open the form
    echo $this->Form->create('Application');

    // include the materials recieved data
    echo $this->element('Licenses.applications/received_materials');

    // include the interim date
    echo $this->element('Licenses.applications/interim_date');

    // include notes
    echo $this->Form->input(
        'License.Note.0.note',
        array(
            'label' => __('Notes'),
            'type'  => 'textarea',
            'class' => 'span-x med elastic',
        )
    );

    // close the form
    echo $this->Form->end('Approve');
?>