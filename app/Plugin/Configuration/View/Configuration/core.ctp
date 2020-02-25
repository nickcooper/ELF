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
                </ul>
            </div>
        </div>
    </div>

	<div id="section" class="span-19 last">
		<div class="pad">
		    <?php echo $this->Form->create('Configuration');?>
		        
		        <h2>Allowed Login Groups</h2>
		        <table>
                    <?php 
                        foreach ($groups as $group) : 
                            
                            // set the checked and disabled values
                            $checked = '';
                            $disabled = '';
                            
                            if ( in_array($group['Group']['id'], explode(',', Configure::read('Configuration.allowed_login_groups')))) :
                                $checked = 'checked';
                            endif;
                            
                            if ( $group['Group']['label'] == 'Super Admin') :
                                $checked = 'checked';
                                $disabled = 'disabled';
                            endif;
                    ?>
                       <tr>
                            <td style="width:200px;"><?php echo Inflector::humanize($group['Group']['label']); ?></td>
                            <td>
                                <?php
                                    echo $this->Form->checkbox(
                                        'Group.'.$group['Group']['id'], 
                                        array(
                                            'value' => $group['Group']['id'],
                                            'class' => 'clear',
                                            'label' => false,
                                            'checked' => $checked,
                                            'disabled' => $disabled
                                        )
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br />
                <br />
		    
		    <?php foreach ($program_options as $progam_id => $options) : ?>
    			<h2><?php echo (!$progam_id ? 'Default' : $programs[$progam_id]); ?> Configuration</h2>
    			<table>
    		        <?php foreach ($options as $option) : ?>
    		            <?php
    		              // exclude some options from displaying in the form
    		              if (in_array($option['Configuration']['name'], $exclude)) :
                            continue;
                          endif;
    		            ?>
    		           <tr>
    		                <td style="width:200px;"><?php echo Inflector::humanize($option['Configuration']['name']); ?></td>
    		                <td>
                                <?php
                                    if (is_array($option['Configuration']['options'])) :
                                        
                                        echo $this->Form->input(
                                            $option['Configuration']['id'], 
                                            array(
                                                'options' => $option['Configuration']['options'],
                                                'selected' => $option['Configuration']['value'],
                                                'class' => 'clear',
                                                'label' => false
                                            )
                                        );
                                        
                                    else :
                                        
                                        echo $this->Form->input(
                                            $option['Configuration']['id'], 
                                            array(
                                                'default' => $option['Configuration']['value'],
                                                'class' => 'text clear',
                                                'label' => false
                                            )
                                        );
                                        
                                    endif;
                                ?>
                            </td>
                        </tr>
    		        <?php endforeach; ?>
    		    </table>
                <br />
                <br />
    		<? endforeach; ?>
    		
			<?php echo $this->Form->end('Save');?>

		</div>
	</div>
</div>
