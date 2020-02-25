<div class="actions">
    <?php
        echo $this->Html->link(__('Finished'), array('action' => 'index'), array('class' => 'button'));

        echo $this->Form->postLink(
            __('Delete Instructor'),
            array(
                'action' => 'delete',
                $instructor['Instructor']['id']
            ),
            array(
                'class' => 'button delete rightify',
            ),
            sprintf(
                __('Are you sure you want to delete Instructor "%d"? This will only remove them as an instructor. Their account will be left in-tact.'),
                $instructor['Account']['label']
            )
        );

        if ($instructor['Instructor']['pending'])
        {
            echo $this->Html->link(
                __('Approve'),
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'instructors',
                    'action'     => 'approve',
                    $instructor['Instructor']['id']
                ),
                array('class' => 'button')
            );
        }
    ?>
</div>
