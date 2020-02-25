<div class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (empty($application['perjury_name'])) : ?>
        <div class="span-12">
            <fieldset>
                <div class="form_item">
                    <?php echo $this->Form->input('Application.perjury_name', array('label' => 'Name')); ?>
                    <?php echo $this->Form->input('Application.perjury_date', array('type' => 'date', 'label' => 'Date')); ?>
                </div>
            </fieldset>
    <?php else: ?>
        <div class="span-12">
            <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
                <tbody>
                    <tr>
                        <th scope="row">Name</th>
                        <td><?php echo $application['perjury_name']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row">Date</th>
                        <td><?php echo GenLib::dateFormat($application['perjury_date']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>