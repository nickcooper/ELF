<div id="abatement_info" class="form_section setoff">
    <h3><?php echo __('Abatement Information');?> <span class="req"><?php echo __('Required Section'); ?></span></h3>

    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Abatement #'); ?></th>
                    <td><?php echo h($this->TextProcessing->checkForBlank($this->data['Abatement']['abatement_number'])); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('License #'); ?></th>
                    <td><?php echo h($this->TextProcessing->checkForBlank($this->data['License']['license_number'])); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Contractor'); ?></th>
                    <td><?php echo h($this->TextProcessing->checkForBlank($this->data['License']['label'])); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Abatement Status'); ?></th>
                    <td><?php echo h($this->data['AbatementStatus']['label']); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Date Received'); ?></th>
                    <td><?php echo $this->TextProcessing->checkForBlank($this->TextProcessing->formatDate($this->data['Abatement']['date_received'])); ?></td>
                </tr>
            </tbody>
        </table>

        <?php if (! $isIncomplete): ?>
            <ul class="button_select_list">
                <li>
                    <a href="#"><?php echo __('Generate Documents'); ?></a>
                    <ul>
                    <?php foreach ($doc_links as $title => $url): ?>
                        <li><?php echo $this->Html->link($title, $url['pdf']); ?></li>
                    <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>

    </div>
    <div class="span-5 last">
        <div class="actions">

        <?php if ($isIncomplete): ?>

            <?php
                echo $this->Html->link(
                    __('Edit Abatement Information'),
                    array(
                        'controller' => 'abatements',
                        'action'     => 'abatement_information',
                        $this->data['Abatement']['id'],
                        'return'     => base64_encode($this->here),
                    ),
                    array('class' => 'button small')
                );
            ?>

        <?php else: ?>

            <em><?php echo __('Abatement completed'); ?></em>

        <?php endif; ?>

        </div>
    </div>
</div>
