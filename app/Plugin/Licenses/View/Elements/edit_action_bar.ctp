<div class="actions">
    <?php
        $lic_num = null;
        if (isset($this->params['named']['li']))
        {
            $lic_num = $this->params['named']['li'];
        }

        // finished button
        echo $this->Html->link(
            'Return Home',
            array(
                'plugin' => 'accounts',
                'controller' => 'accounts',
                'action' => 'home',
            ),
            array(
                'class' => 'button',
            )
        );

        // submit/continue button
        if ($show_continue_button)
        {
            echo $this->Html->link(
                'Continue &raquo;',
                array(
                    'plugin' => 'licenses',
                    'controller' => 'applications',
                    'action' => 'submit',
                    $application_view_data['Application']['id']
                ),
                array(
                    'class' => 'button',
                    'escape' => false
                )
            );
        }

        // approve button
        if ($show_approve_button)
        {
            echo $this->Html->link(
                'Approve &raquo;',
                array(
                    'plugin' => 'licenses',
                    'controller' => 'applications',
                    'action' => 'approve',
                    $application_view_data['Application']['id']
                ),
                array(
                    'class' => 'button modal',
                    'escape' => false
                )
            );
        }
    ?>
</div>