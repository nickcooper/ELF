<?php $account = $this->request->data['Account']; ?>
<div id="body" class="span-24">
    
    <?php echo $this->element('section_nav', array('account' => $this->request->data)); ?>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo __('Edit Personal Information'); ?></h3>
            <?php echo $this->element('account_form', array('account' => $this->request->data)); ?>
        </div>
    </div>
    
</div>