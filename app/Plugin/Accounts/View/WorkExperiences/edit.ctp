<div id="body" class="span-24">
    
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre"><?php echo $humanized_foreign_obj; ?> </span>Work Experience</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
            
            <div id="section_nav_holder">    
                <ul id="section_nav">
                    <li>
                        <?php
                            echo $this->Html->link(
                                'Add New Experience',
                                array(
                                    'plugin' => 'accounts',
                                    'controller' => 'work_experiences',
                                    'action' => 'edit',
                                    'fp' => $foreign_plugin,
                                    'fo' => $foreign_obj,
                                    'fk' => $foreign_key,
                                    'return' => $this->params['named']['return']  
                                )
                            );
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3>Add Work Experience</h3>
            <?php echo $this->element('work_experience_form'); ?>
        </div>
    </div>
</div>