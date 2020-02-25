<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">View</span> Third Party Test Results</h3>
			<p class="bottom"><?php echo $this->IiHtml->returnLink(); ?></p>
		</div>
		<div id="section_nav_holder">
			<ul id="section_nav">
				<li><a href="#"></a></li>
			</ul>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<div id="Course_Information" class="form_section">
			<h3>Third Party Test Results</h3>
			<div class="span-12">
				<table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
					<tr>
						<th width="120" scope="row">Testing Center:</th>
						<td>
						    <?php echo $test['ThirdPartyTest']['testing_center']; ?>
						</td>
					</tr>
					<tr>
                        <th width="120" scope="row">Test Date:</th>
                        <td><?php echo GenLib::dateFormat($test['ThirdPartyTest']['date']); ?></td>
					</tr>
                    <tr>
                        <th width="120" scope="row">Test Score:</th>
                        <td><?php echo $test['ThirdPartyTest']['score']; ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row">Passed?</th>
                        <td><?php echo ($test['ThirdPartyTest']['pass'] ? 'Yes' : 'No'); ?></td>
                    </tr>
					<tr>
						<th width="120" scope="row">Test Results:</th>
				            <td>
			                    <?php if ($this->action == 'view' && GenLib::isData($test, 'Upload.0', array('id'))) : ?>
			                        <div class="form_item">
			                            <div class="input_holder">
			                                <?php
			                                    echo $this->Html->link(
			                                        __('View Third Party Test Results'),
			                                        sprintf('/files/%s', $test['Upload'][0]['file_name']),
			                                        array(
			                                            'title'  => __('View Third Party Test Results'),
			                                            'target' => '_blank',
			                                            'class'  => 'iconify pdf',
			                                        )
			                                    );
			                                ?>
			                            </div>
			                        </div>
			                    <?php endif; ?>
							</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="actions">
			<?php
                echo $this->Html->link(
                    'Finished',
                    base64_decode($this->params['named']['return']),
                    array('class' => 'button cancel')
                );
            ?>
		</div>
	</div>
</div>
