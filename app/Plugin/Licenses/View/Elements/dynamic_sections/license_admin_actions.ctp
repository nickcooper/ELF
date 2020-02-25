<?php
    // display admin action links
    foreach ($admin_actions as $key => $link) :

        // Get the link
        $action_link = $this->Html->aclLink(
            $link['label'],
            sprintf('%s/return:%s', $link['url'], $return),
            $link['attr']
        );

        // Output the link if it's not empty
        if (!empty($action_link)) {
            echo '<li>' . $action_link . '</li>';
        }

    endforeach;
?>
