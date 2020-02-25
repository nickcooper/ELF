<div id="body" class="span-24">
    
    <?php echo $this->element('section_nav'); ?>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo sprintf('Edit %s Address', $humanized_foreign_obj); ?></h3>
            
            <?php echo $this->Form->create('Address');?>         

            <?php echo $this->element('address_form'); ?>

            <?php echo $this->Form->submit('Save'); ?>
    
            <?php echo $this->Form->end();?>
        </div>
    </div>
</div>