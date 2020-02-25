<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><?php echo __('Programs'); ?></h3>
            <p class="bottom"><?php echo __('Edit, disable, or set up new programs.'); ?></p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h2><?php echo __('Programs'); ?></h2>
        <hr/>
        <div class="actions">
            <?php
                echo $this->Html->link(
                    __('Add New Program'),
                    array('controller' => 'programs', 'action' => 'add'),
                    array('class' => 'button')
                );
            ?>
        </div>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('Program.label', __('Name'));?></th>
                    <th><?php echo $this->Paginator->sort('Program.enabled', __('Enabled'));?></th>
                    <th><?php echo $this->Paginator->sort('Program.modified', __('Modified'));?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($programs as $program):
                ?>
                <tr>
                    <td>
                        <?php
                            echo $this->Html->link(
                                $program['Program']['label'],
                                array('controller' => 'programs', 'action' => 'view', $program['Program']['id'])
                            );
                        ?>
                    </td>
                    <td><?php echo $program['Program']['enabled']; ?></td>
                    <td><?php echo h($this->TextProcessing->formatDate($program['Program']['modified'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $this->element('pagination_links'); ?>
    </div>
</div>