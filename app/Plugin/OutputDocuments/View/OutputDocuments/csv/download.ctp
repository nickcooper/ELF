<?php 
    // loop the data and feed it to the element
    foreach ($data as $d)
    {
        echo $this->element($element, array('data' => $d), array('plugin' => 'output_documents'))."\r\n";
    }
?>