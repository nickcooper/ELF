<table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><?php echo __('Abatement #'); ?></td>
        <td><?php echo h($abatement['Abatement']['abatement_number']); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Status'); ?></td>
        <td><?php echo h($abatement['AbatementStatus']['label']); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Requested Date'); ?></td>
        <td><?php echo h($this->TextProcessing->formatDate($abatement['Abatement']['created'])); ?></td>
    </tr>
</table>

<p>
    <?php
        echo $this->Html->link(__('Edit Status'),
            array('action' => 'change_status', $abatement['Abatement']['id']),
            array('class' => 'button')
        );
    ?>

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
</p>