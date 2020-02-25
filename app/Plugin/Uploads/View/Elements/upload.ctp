<?php
// By default the name and type are both file
$type = empty($type) ? 'file' : $type;

// By default there will be no label
$label = empty($label) ? false : $label;

// By default is 1
$count = empty($count) ? 1 : intval($count);

// By default use the default settings
$config_key = empty($config_key) ? 'Upload' : $config_key;

// By default use hasMany association
$association = empty($association) ? 'hasMany' : $association;

// By default parent is null
$parent = empty($parent) ? null : $parent;

// By default data is $this->data
$data = empty($data) ? $this->data : $data;


// We're not ever going to replace hasMany uploads so it's assumed you'll put a delete button into your view to get rid of ones you don't want
for ($i = 0; $i < $count; $i++)
{
    // parent field
    if ($parent) :
        echo $this->Form->input(
            sprintf('%s.%s.%s', $config_key, $i, 'parent_id'),
            array(
                'type' => 'hidden',
                'value' => $parent,
            )
        );
    endif;

    echo $this->Form->input(
        sprintf('%s.%s.file', $config_key, $i),
        array('type' => $type, 'label' => $label)
    );
}
?>
