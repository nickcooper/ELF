<div>
    <h3>Materials Received Date</h3>

    <p>
        This is the date for which all materials (digital, faxed, mailed or otherwise) were received for this application.
        This date may only be changed during the approval process. You will not be able to update this date once you approve this application.
        If you do not have all of the required documents do not approve this application.
    </p>

    <div>
        <?php
            echo $this->Form->input(
                'Application.materials_received',
                array(
                    'label' => false,
                    'class' => 'text date',
                    'minYear' => date('Y') - 1,
                    'maxYear' => date('Y'),
                    'selected' => $materials_received_date
                )
            );
        ?>
    </div>

</div>