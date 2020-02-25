<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre">ACCOUNT</span> 
                <?php echo $account['Account']['label']; ?>
            </h3>
            <p class="bottom"><?php echo $this->IiHtml->returnLink('/accounts/accounts/index'); ?></p>
        </div>

        <div id="section_nav_holder">    
            <ul id="section_nav">
                <li class="selected"><a href="#account_information">Account Information</a></li>
                <li><?php echo $this->Html->link('Notes <span class="count">('.$note_count.')</span>', 
                array(
                    'plugin' => 'notes', 
                    'controller' => 'notes', 
                    'action' => 'index', 
                    'fp' => 'Accounts', 
                    'fo' => 'Account', 
                    'fk' => $account['Account']['id'],
                    'return' => base64_encode($this->here)
                ),
                array('escape' => false)); ?></li>
            </ul>
        </div>
    </div>
</div>
