<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre"><?php echo __('New'); ?></span> <?php echo __('Course Catalog Item'); ?></h3>
			<p class="bottom"><?php echo $this->Html->link(__('< Back to Listing'), array('action' => 'index'));?></p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<h3><?php echo __('New Course Catalog Item'); ?></h3>
<?php echo $this->Form->create('CourseCatalog');?>
		<fieldset>
			<legend><?php echo __('Course Information'); ?></legend>
		<?php
	        echo $this->Form->input('CourseCatalog.label', array('class' => 'text span-9 large'));

	        echo $this->Form->input(
	        	'CourseCatalog.abbr',
	        	array(
	        		'class' => 'text span-10',
	        		'label' => __('Abbreviation'),
	        		'help'  => __('6 characters maximum.'),
        		)
        	);

	        echo $this->Form->input('CourseCatalog.program_id');

	        echo $this->Form->input(
	        	'CourseCatalog.descr',
	        	array(
	        		'type'  => 'textarea',
	        		'label' => __('Description'),
	        		'class' => 'short span-11',
        		)
    		);

	       	echo $this->Form->input(
	       		'CourseCatalog.code_hours',
	       		array(
	       			'label' => __('Code Hours'),
	       			'class' => 'text span-2',
       			)
   			);

            echo $this->Form->input(
                'CourseCatalog.non_code_hours',
                array(
                    'label' => __('Non Code Hours'),
                    'class' => 'text span-2',
                )
            );

	        echo $this->Form->input(
	        	'CourseCatalog.test_attempts',
	        	array(
	        		'label'   => __('Allowed Number of Test Attempts'),
	        		'options' => array_merge(array('' => '-- Select --'), range(1, $max_test_attempts)),
        		)
    		);

            echo $this->Form->input(
            	'CourseCatalog.cycle',
            	array(
            		'label' => __('Months until expired'),
            		'class' => 'text span-2',
        		)
    		);
        ?>
		</fieldset>
<?php echo $this->Form->end(__('Save'));?>

	</div>
</div>
