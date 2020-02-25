<?php echo $this->element('section_heading', array(), array('plugin' => 'OutputDocuments')); ?>

<div id="section" class="span-19 last">
    <div class="pad">
        <h2><?php echo $page_name; ?></h2>
        <hr/>
        <table class="data" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Queue Count</th>
                    <th>Last Batched</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $row): ?>
                <tr>
                    <td>
                        <?php echo $this->Html->link(
                            GenLib::reverseSlug($row['label']), 
                            array(
                                'plugin' => 'output_documents',
                                'controller' => 'output_documents',
                                'action' => 'queue',
                                GenLib::makeSlug($row['label'])
                            )
                        ); ?>
                    </td>
                    <td><?php echo $row['count']; ?></td>
                    <td><?php echo $row['last_batch_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
