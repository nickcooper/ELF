<div id="body" class="span-24">

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo sprintf('Add New %s File', $humanized_foreign_obj); ?></h3>

            <?php echo $this->Form->create('Upload', array('type' => 'file'));?>
            <?php echo $this->Form->input('Uploads.label', array('label' => 'Description'));?>


            <?php echo $this->Form->input(sprintf('%s.id', $foreign_obj), array('type' => 'hidden', 'value' => $foreign_key)); ?>

            <?php
                echo $this->element(
                    'upload',
                    array(
                        'config_key' => $this->params['named']['type'],
                        'parent' => $foreign_key,
                        'association' => (isset($configuration['association']) ? $configuration['association'] : null),
                    ),
                    array('plugin' => 'Uploads')
                );
            ?>

            <?php echo $this->Form->end('Upload File');?>
        </div>
    </div>
</div>