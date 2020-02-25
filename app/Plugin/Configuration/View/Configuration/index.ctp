<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3>
                    <span class="pre"><?php echo __('Configuration'); ?></span>
                    <em><?php echo __('Configuration'); ?></em>
                </h3>
            </div>

            <div id="section_nav_holder">
                <ul id="section_nav" class="tab_nav">
                    <li>
				    	<?php 
					        echo $this->Html->link(
					            'Core', 
					            array(
					                'plugin' => 'configuration', 
					                'controller' => 'configuration', 
					                'action' => 'core',
	                                'return' => base64_encode($this->here),
	                            )
	                        ); 
	                    ?>
	                </li>
                    <li>
				    	<?php 
					        echo $this->Html->link(
					            'Plugins', 
					            array(
					                'plugin' => 'configuration', 
					                'controller' => 'configuration', 
					                'action' => 'plugin',
	                                'return' => base64_encode($this->here),
	                            )
	                        ); 
	                    ?>
	                </li>
                    <li>
				    	<?php 
					        echo $this->Html->link(
					            'Programs', 
					            array(
					                'plugin' => 'configuration', 
					                'controller' => 'configuration', 
					                'action' => 'program',
	                                'return' => base64_encode($this->here),
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
            <h2><?php echo h('Configuration'); ?></h2>
			<hr/>
            <div id="license_panel" class="content_panel">
				<div class="form_section">
				    <h3>Core Configuration</h3>
	            	<div class="actions text_center">
					    <?php
					        echo $this->Html->link(
					            'Configure', 
					            array(
					                'plugin' => 'configuration',
					                'controller' => 'configuration',
					                'action' => 'core',
					            ),
					            array('class' => 'button')
					        );
					    ?>
					</div>
				</div>
				<div class="form_section">
				    <h3>Plugins Configuration</h3>
	            	<div class="actions text_center">
					    <?php
					        echo $this->Html->link(
					            'Configure', 
					            array(
					                'plugin' => 'configuration',
					                'controller' => 'configuration',
					                'action' => 'plugin',
					            ),
					            array('class' => 'button')
					        );
					    ?>
					</div>
				</div>
				<div class="form_section">
				    <h3>Program Configuration</h3>
	            	<div class="actions text_center">
					    <?php
					        echo $this->Html->link(
					            'Configure', 
					            array(
					                'plugin' => 'configuration',
					                'controller' => 'configuration',
					                'action' => 'program',
					            ),
					            array('class' => 'button')
					        );
					    ?>
					</div>
				</div>
           	</div>
        </div>
    </div>
</div>