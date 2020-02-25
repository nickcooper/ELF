<?php 
    // loop the data and feed it to the element
    foreach ($data as $d)
    {
        $this->element($element, array('data' => $d), array('plugin' => 'output_documents'));
    }
    
    // output the document
    echo $this->Pdf->lib->Output($filename, 'D');
?>
