<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <?php echo $this->element('section_heading'); ?>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Abatement Information'); ?></h3>

            <?php echo $this->Form->create(); ?>

            <div class="span-12">
                <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo __('Abatement #'); ?></th>
                            <td><?php echo h($abatement['Abatement']['abatement_number']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('License #'); ?></th>
                            <td><?php echo h($abatement['License']['license_number']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Contractor'); ?></th>
                            <td><?php echo h($abatement['License']['label']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Abatement Status'); ?></th>
                            <td><?php echo h($abatement['AbatementStatus']['label']); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Received Date'); ?></th>
                            <td><?php echo $this->Form->input('Abatement.date_received', array('label' => false)); ?></td>
                        </tr>
                    </tbody>
               </table>
            </div>

            <?php echo $this->Form->end(__('Submit')); ?>

        </div>
    </div>
</div>