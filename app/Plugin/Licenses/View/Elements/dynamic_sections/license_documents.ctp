<?php
    // are there any docs to display?
    foreach ($doc_links as $key => $type) :

        foreach ($type as $ext => $link) :

            $label = Inflector::humanize(sprintf('%s (%s)', $key, $ext));

            // Get the link
            $action_link = $this->Html->aclLink(
                $label,
                $link,
                array(
                    'title' => $label,
                    'class' => 'button small'
                )
            );

            // Output the link if it's not empty
            if (!empty($action_link)) {
                echo '<li>' . $action_link . '</li>';
            }

        endforeach;

    endforeach;
?>
