<?php 
    // file name
    $filename = sprintf('%s-results', strtolower($title));

    // build the link
    $link = sprintf('%s/%s', preg_replace('/\/index\//', '/download/', $this->here), $filename);
    
    // display the link
    echo $this->HTML->link(
        'Download these results',
        array_merge(
            $this->params->named,
            array('action' => 'download', 'searchable' => $filename, 'ext' => 'csv')
        ),
        array(),
        'The download is currently limited to 1000 records.'
    );
?>
