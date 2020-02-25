<ul class="button_select_list">
    <li>
        <a href="#"><?php echo __('Add New Abatement'); ?></a>
        <ul style="display: none; ">
            <?php
                foreach ($dwelling_types as $id => $label):
                    $link = $this->Html->link(
                        $label,
                        array(
                            'plugin'     => 'abatements',
                            'controller' => 'abatements',
                            'action'     => 'add',
                            $id,
                            'fp'         => 'Licenses',
                            'fo'         => 'License',
                            'return'     => base64_encode($this->here),
                        ),
                        array()
                    );

                    echo sprintf('<li>%s</li>', $link);
                endforeach;
            ?>
        </ul>
    </li>
</ul>
