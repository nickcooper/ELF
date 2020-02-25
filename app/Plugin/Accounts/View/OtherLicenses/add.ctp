<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo 'Account'; ?></span>
                <?php echo $account['label']; ?>
            </h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
        </div>
    </div>
</div>

<div id="section" class="span-19 last">
    <div class="pad">
        <h3><?php echo __('Add Other License'); ?></h3>
            
        <?php echo $this->element('other_license_form'); ?>
    </div>
</div>
