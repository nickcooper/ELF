<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre"><?php echo __('Application Cache'); ?></span></h3>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('Application Cache'); ?></h3>
<?php if (GenLib::isData($cacheFiles, '0', array('filename'))): ?>
        <div class="actions">
            <?php
                echo $this->Html->link(
                    __('Clear Cache'),
                    array('action' => 'cache', 'clear'),
                    array('class' => 'button')
                );
            ?>
        </div>
        <div class="content_panel">
            <table class="light_data" border="0">
                <thead>
                    <tr>
                        <th scope="col"><?php echo __('Filename'); ?></th>
                        <th scope="col"><?php echo __('Last Modified'); ?></th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach ($cacheFiles as $cacheFile): ?>
                    <tr>
                        <td><?php echo h($cacheFile['relpath']); ?></td>
                        <td><?php echo h($this->TextProcessing->formatDate($cacheFile['modified'], true)); ?></td>
                    </tr>
            <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php endif; ?>
    </div>
</div>