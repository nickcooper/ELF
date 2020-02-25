<?php

    if (!isset($note_map))
    {
        $note_map = 'Note.note';
    }

?>

<fieldset>
    <legend><?php echo __('Add New Note'); ?></legend>
    <?php
        echo $this->Form->input(
            $note_map,
            array(
                'label' => __(''),
                'type'  => 'textarea',
                'class' => 'span-x med elastic',
            )
        );
    ?>
</fieldset>
