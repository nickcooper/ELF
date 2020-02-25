<?php
    // display admin action links
    foreach ($license_types as $id => $label) :

        echo '<div class="text" style="float:left;">';
        echo $this->Html->aclLink(
            $label,
            sprintf('/licenses/licenses/add/%s/searchable:%s/return:%s', $id, $data[0]['Entity']['id'], $return),
            array(
                'title' => $label,
                'class' => 'button small'
            )
        );
        echo '</div>';
        echo '<br style="clear:both" />';

    endforeach;