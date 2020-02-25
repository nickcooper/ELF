<?php
// By default the name and type are both file
$type = empty($type) ? 'file' : $type;

// By default there will be no label
$label = empty($label) ? false : $label;

// By default use the default settings
$config_key = empty($config_key) ? 'Upload' : $config_key;

// By default use hasMany association
$association = empty($association) ? 'hasMany' : $association;

// By default parent is null
$parent = empty($parent) ? null : $parent;

// By default data is $this->data
$data = empty($data) ? $this->data : $data;



// parent field
if ($parent) :
    echo $this->Form->input(
        sprintf('%s.%s', $config_key, 'parent_id'),
        array(
            'type' => 'hidden',
            'value' => $parent,
        )
    );
endif;

echo $this->Form->input(
    sprintf('%s.file', $config_key),
    array('type' => $type, 'label' => $label)
);

// show the uploaded files
if (isset($data[$config_key]) && GenLib::isData($data, $config_key, array('file_size')))
{
?>
    <ul style="padding-left:30px;">
        <li>
            <?php echo $this->Html->link((!empty($data[$config_key]['label']) ? $data[$config_key]['label'] : $data[$config_key]['file_name']), $data[$config_key]['web_path']); ?>
        </li>
    </ul>
<?php
}
?>
