<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo $header; ?></span>
                <?php echo $sub_header; ?>
            </h3>
            <p class="attn">
                <?php
                    echo $this->Html->aclLink(
                        $this->textProcessing->checkForBlank($label),
                        $fo_link
                    );
                ?>
            </p>
            <hr/>
            <p class="bottom"><?php echo $this->Html->aclLink('Search for Applications', '/licenses/applications/index'); ?></p>
            </br>
        </div>

        <div id="section_nav_holder">
            <ul id="section_nav">
                <li class="selected"><a href="#license_panel">License Information</a>
                <li><?php echo $this->Html->link('Notes <span class="count">('.$note_count.')</span>',
                array(
                    'plugin' => 'notes',
                    'controller' => 'notes',
                    'action' => 'index',
                    'fp' => 'Licenses',
                    'fo' => 'License',
                    'fk' => $license_id,
                    'return' => base64_encode($this->here)
                ),
                array('escape' => false)); ?></li>
            </ul>

        </div>
    </div>
</div>