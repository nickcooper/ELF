<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre">License</span> Contractor Info</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
        </div>
    </div>

    <div id="section" class="span-19 last">
        <div class="pad">
            <h3>Contractor Information</h3>

            <?php echo $this->Form->create('Contractor'); ?>
            <?php
                if (GenLib::isData($this->data, 'Contractor', array('id'))) :
                    echo $this->Form->input('Contractor.id', array('type' => 'hidden'));
                endif;
            ?>
            <fieldset>
                <legend>Contractor Information</legend>
                <?php echo $this->Form->input('Contractor.crn', array('label' => 'Contractor Registration Number')); ?>
                <?php echo $this->Form->input("Contractor.crn_expire_date", array('label' => 'CRN Expire Date', 'type' => 'date')); ?>
                <?php echo $this->Form->input('Contractor.fin', array('label' => 'Federal ID Number')); ?>
            </fieldset>
            <?php echo $this->Form->end('Save'); ?>
        </div>
    </div>
</div>