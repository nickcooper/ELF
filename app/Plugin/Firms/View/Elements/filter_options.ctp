<div class="filter_holder">
    <div id="filter_options" class="hide">
        <?php echo $this->Form->create('Firms.Firm', array('action' => 'search')); ?>

        <!-- Keyword section -->
        <div class="block full">
            <h4><?php echo __('Keyword'); ?></h4>
            <p>
                <?php echo $this->Form->input('keyword', array('class' => 'span-8 text', 'placeholder' => __('Enter keyword...'), 'label' => false)); ?>
                <?php echo $this->Form->submit(__('Search'), array('class' => 'button submit small', 'div' => false, 'after' => '')); ?>
            </p>
        </div>

        <!-- License type section -->
        <div class="block">
            <h4><?php echo __('License Type'); ?></h4>
            <ul>
        <?php foreach ($licenseTypes as $licenseType): ?>
                <li><?php echo $this->Html->link($licenseType, '#'); ?></li>
        <?php endforeach; ?>
            </ul>
        </div>

        <!-- Date section -->
        <div class="block">
            <h4><?php echo __('Date'); ?></h4>
        </div>

        <!-- Status section -->
        <div class="block last">
            <h4><?php echo __('Status'); ?></h4>
            <ul>
        <?php foreach ($licenseStatuses as $licenseStatus): ?>
                <li><?php echo $this->Html->link($licenseStatus, '#'); ?></li>
        <?php endforeach; ?>
            </ul>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>