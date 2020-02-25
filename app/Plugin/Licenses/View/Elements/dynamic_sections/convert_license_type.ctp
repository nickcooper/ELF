<?php
    // display admin action links
    foreach ($convert_license_types as $id => $label) :

        // Get the link
        $action_link = $this->Html->aclLink(
            $label,
            sprintf('/licenses/licenses/convert/%s/%s/return:%s', $id, $license['License']['id'], $return),
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
?>
