<div class="actions">
    <?php
        // finished button
        echo $this->Html->link(
            __('Finished'),
            array(
                'plugin'     => 'abatements',
                'controller' => 'abatements',
                'action'     => 'index',
            ),
            array('class' => 'button')
        );

        if ($this->data['Abatement']['date_submitted'] === null && $isIncomplete)
        {
            // Submit button
            echo $this->Html->link(
                __('Submit Notice'),
                array(
                    'plugin'     => 'abatements',
                    'controller' => 'abatements',
                    'action'     => 'submit',
                    $this->data['Abatement']['id'],
                    'return'     => base64_encode($this->here)
                ),
                array('class' => 'button')
            );
        }

        if ($this->data['Abatement']['date_submitted'] !== null && $isActive)
        {
            // Submit revised notice button
            echo $this->Html->link(
                __('Submit Revised Notice'),
                array(
                    'plugin'     => 'abatements',
                    'controller' => 'abatements',
                    'action'     => 'submit',
                    $this->data['Abatement']['id'],
                    'return'     => base64_encode($this->here)
                ),
                array('class' => 'button')
            );
        }
    ?>
</div>