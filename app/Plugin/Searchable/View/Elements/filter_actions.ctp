<?php
    
    // cancel link
    $cancel_link = $this->Html->link(
        __('Cancel'),
        array('#'),
        array(
            'class' => 'button small cancel toggle',
            'rel'   => '#filter_options',
        )
    );
    
    // reset link
    $reset_options = array(
        'fp' => $this->params->named['fp'],
        'fo' => $this->params->named['fo'],
    );
    
    if (isset($this->params->named['return']))
    {
        $reset_options['return'] = $this->params->named['return'];
    }
    
    $reset_link = $this->Html->link(
        __('Reset Filters'), 
        $reset_options, 
        array('class' => 'button small cancel')
    );
?>

<div class="actions textright clear">
<?php
    // apply filters button
    echo $this->IiForm->submit(
        __('Apply Filters'),
        array(
            'stripped' => true,
            'class'    => 'button small submit',
            'after'    => sprintf('%s %s', $cancel_link, $reset_link),
        )
    );
?>
</div>