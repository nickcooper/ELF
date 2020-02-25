<?php

$prefix = isset($prefix) ? $prefix.'.' : '';

$model = isset($model) ? $model : 'Address';

$index_string = isset($address_index) ? $address_index.'.' : '';

// this JS is added to the head of the doc to check the state and to show counties if it's Iowa.
$stateToggle = "
	<script type=\"text/javascript\">
		$(function(){
			if ($('#state_holder select').val()!='IA') {
				$('#county_holder').hide();
			}
			$('#state_holder select').change(function(){
				console.log('blurring');
				if ($(this).val() == 'IA') {
					$('#county_holder').show();
				} else {
					$('#county_holder').hide();
				}
			});
		});
	</script>";
$this->addScript($stateToggle);

$statesArray = Cache::read('state_list');

$countiesArray = Cache::read('county_list');

if (Hash::check($this->data, sprintf('%s%s.%sid', $prefix, $model, $index_string))) :
    echo $this->Form->input(sprintf('%s%s.%sid', $prefix, $model, $index_string), array('type' => 'hidden'));
endif;

echo $this->Form->input(sprintf('%s%s.%saddr1', $prefix, $model, $index_string), array('label' => 'Address, Line 1', 'class' => 'text span-10'));
echo $this->Form->input(sprintf('%s%s.%saddr2', $prefix, $model, $index_string), array('label' => 'Address, Line 2', 'class' => 'text span-10'));
echo $this->Form->input(sprintf('%s%s.%scity', $prefix, $model, $index_string), array('label' => 'City', 'class' => 'text span-x', 'div' => array('class' => 'form_item span-7')));
echo $this->Form->input(sprintf('%s%s.%sstate', $prefix, $model, $index_string), array('label' => 'State', 'options' => $statesArray, 'default' => 'IA', 'class' => 'span-x', 'div' => array('class' => 'form_item span-3', 'id'=>'state_holder')));
echo $this->Form->input(sprintf('%s%s.%spostal', $prefix, $model, $index_string), array('label' => 'Zip Code', 'type' => 'text', 'class' => 'text span-4', 'div' => array('class' => 'form_item span-8 last')));
echo $this->Form->input(sprintf('%s%s.%scounty', $prefix, $model, $index_string), array('label' => 'County', 'options' => $countiesArray, 'div' => array('class' => 'form_item', 'id' => 'county_holder')));