<?php
    // TEMPORARY UNTIL SEARCHABLE QUICK FILTERS ARE ADDED TO DATABASE AND SEARCHABLE CONTROLLER
    $quick_filters = array(
        'filter label' => '' /* need to insert filter params here*/,
    );
?>
<ul>
    <?php
        $i = 0;
        foreach($quick_filters as $label => $filter)
        {
            if ($i > 0)
            {
                echo '<hr style="margin:5px 0px 5px;" />';
            }
            
            echo $this->Html->link(
                $label,
                array (
                    'plugin' => 'searchable', 
                    'controller' => 'searchable', 
                    'action' => 'index', 
                    'fp' => 'Licenses', 
                    'fo' => 'License',
                    '?=filters', //filter
                    //'return' => $this->params['named']['return']
                ),
                array(
                    'before' => '<li>',
                    'after' => '</li>',
                    'escape' => false,
                )
            );
            
            $i++;
        }
    ?>
</ul>