<?php foreach ($notes as $note): ?>
    <tr>
        <td><?php echo $this->Html->link('View', array('plugin' => 'notes', 'controller' => 'notes', 'action' => 'view', $note['Note']['id']), array('title' => 'needs colorbox maybe?')); ?></td>
        <td><?php echo $note['Note']['note']; ?></td>
        <td><?php echo $note['Note']['created']; ?></td>
        <td><?php echo $note['Account']['label']; ?></td><br>
    </tr>
<?php endforeach; ?>
