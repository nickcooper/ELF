<div id="pre" class="span-5">
    <div class="stationary">
    	<div id="section_head" class="black-box">
            <h3><?php echo $this->textProcessing->checkForBlank($account['label']); ?></h3>
            <dl>
            <dt>Email</dt>
            <dd><?php echo $this->textProcessing->checkForBlank($account['email']); ?></dd>
            <dt>SSN</dt>
            <dd><?php 
                    $ssn = ($account['ssn_last_four'] == '') ? '' : sprintf('***-**-%s', $account['ssn_last_four']);
                    echo $this->textProcessing->checkForBlank($ssn); 
                ?></dd>
            <dt>Date of Birth</dt>
            <dd><?php echo $this->textProcessing->checkForBlank(GenLib::dateFormat($account['dob'])); ?></dd>
            </dl>
            <hr/>
            <p class="bottom"><?php 
                echo $this->Html->link(
                    'Edit Account Email',
                    array(
                        'plugin' => 'accounts',
                        'controller' => 'accounts',
                        'action' => 'editEmail',
                        $account['id'],
                        'return' => base64_encode($this->here),
                    )
                ); 
            ?></p>
        </div>
    </div>
</div>