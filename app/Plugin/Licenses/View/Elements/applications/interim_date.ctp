<div>
    <h3>Interim Date</h3>

    <p>
        If an interim date is provided, this will be the expiration date given to the license you are approving.  The date entered should be prior to the application expiration date.  You will have the ability to remove the interim date in the future.
    </p>

    <div>
        <?php
            echo $this->Form->input(
                'Application.interim_expire_date',
                array(
                    'label' => false,
                    'class' => 'text date',
                    'minYear' => date('Y'),
                    'maxYear' => $interim_max_year,
                    'selected' => $interim_date,
                    'empty' => true
                )
            );
        ?>
    </div>

</div>