<div id="body" class="span-24">
    
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo $humanized_foreign_obj; ?> </span>Insurance Information</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
            
            <div id="section_nav_holder">    
                <ul id="section_nav">
                    <li class="selected">
                        <?php
                            echo $this->Html->link(
                                'Add New Insurance Information',
                                $this->here
                            );
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3>Add Insurance Information</h3>
            <?php echo $this->element('insurance_information_form'); ?>
        </div>
    </div>
</div>