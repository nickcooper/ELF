
<h3>Edit Application Expire Date</h3>

<p>By changing the current application expiration date to a different date, you could be altering other dates within the system that rely on this expiration date. For that reason, as part of changing and saving this data, this license will be flagged as having been manually edited.</p>

<?php
    echo $this->Form->create('Application');

    echo $this->Form->input(
        "Application.expire_date",
        array(
            'label' => 'New Application Expiration Date',
            'type' => 'date',
            'empty' => true,
            'default' => $application['Application']['expire_date']
        )
    );
?>

<?php
    // include notes
    echo $this->Form->input(
        'License.Note.0.note',
        array(
            'label' => __('Notes'),
            'type'  => 'textarea',
            'class' => 'span-x med elastic',
        )
    );
?>

<?php echo $this->Form->end('Save');?>