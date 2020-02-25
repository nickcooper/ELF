<div class="span-5 last">
    <div class="actions">
        <?php
            // closed applications only
            if (!GenLib::isData($open_application, null, array('id'))):
        ?>

            <?php

                if ($can_renew):
                    // renew application button
                    $help_link = $this->Html->link('help', '/help_items/renew_license.html', array('class' => 'help_tag'));

                    echo $this->Html->aclLink(
                        '<i class="icon-repeat"></i> ' . 'Renew License' . $help_link,
                        array(
                            'plugin' => 'licenses',
                            'controller' => 'licenses',
                            'action' => 'renew',
                            $license['id']
                        ),
                        array(
                            'class' => 'button',
                            'escape' => false,
                            'label_show' => false
                        )
                    );

                endif;

                if (!$can_renew && $license_status['status'] !== 'Suspended'):
                    // reopen application button
                    echo $this->Html->aclLink(
                    'Reopen Application',
                        array(
                            'plugin' => 'licenses',
                            'controller' => 'applications',
                            'action' => 'reopen',
                            $current_application['id'],
                            'return' => base64_encode($this->here),
                        ),
                        array(
                            'class' => 'button',
                            'after' => '',
                            'stripped' => true,
                            'label_show' => false
                        )
                    );
                endif;

                // change license type
                if ($license_types && $license_status['status'] != 'Suspended'):
                ?>
                    <ul class="button_select_list">
                        <li>
                            <a href="#">Change License Type</a>
                            <ul style="display: none; ">
                                <?php
                                    foreach ($license_types as $id => $label) :
                                        $link = $this->Html->aclLink(
                                            $label,
                                            array(
                                                'plugin' => 'licenses',
                                                'controller' => 'licenses',
                                                'action' => 'convert',
                                                $id,
                                                $license['id'],
                                                'return' => base64_encode($this->here)
                                            ),
                                            array('label_show' => false)
                                        );

                                        echo sprintf('<li>%s</li>', $link);
                                    endforeach;
                                ?>
                            </ul>
                        </li>
                    </ul>
                <?php
                    echo $this->Html->link('help', '/help_items/convert_license.html', array('class' => 'help_tag'));

                endif;
            endif;

            // suspend license button
            echo "<br />";
            if ($license_status['status'] !== 'Suspended'):

                echo $this->Html->link(
                    'Suspend License',
                    array(
                        'plugin' => 'licenses',
                        'controller' => 'licenses',
                        'action' => 'suspend',
                        $license['id'],
                        'return' => base64_encode($this->here)
                    ),
                    array(
                        'class' => 'button',
                        'escape' => false,
                        'label_show' => false
                    )
                );
            endif;

            // reactivate license button
            if ($license_status['status'] == 'Suspended'):

                echo $this->Html->link(
                    'Activate License',
                    array(
                        'plugin' => 'licenses',
                        'controller' => 'licenses',
                        'action' => 'activate',
                        $license['id']
                    ),
                    array(
                        'class' => 'button',
                        'escape' => false,
                        'label_show' => false
                    )
                );
            endif;
        ?>
    </div>
</div>