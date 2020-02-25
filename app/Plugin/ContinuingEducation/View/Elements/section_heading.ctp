<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre"><?php echo __('Continuing'); ?></span> <?php echo __('Education'); ?></h3>
			<p class="bottom"><?php echo __('Manage Courses, Rosters, & Instructors'); ?></p>
		</div>
		<div id="section_nav_holder">
			<ul id="section_nav">
				<li>
				    <?php
				        echo $this->Html->link(
				            __('Course Sections'),
				            array(
				                'plugin'     => 'continuing_education',
				                'controller' => 'course_sections',
				                'action'     => 'index',
                                'return'     => base64_encode($this->here),
                            )
                        );
                    ?>
                </li>
				<li>
				    <?php
				        echo $this->Html->link(
				            __('Training Instructors'),
				            array(
				                'plugin'     => 'continuing_education',
				                'controller' => 'instructors',
				                'action'     => 'index',
                                'return'     => base64_encode($this->here),
                            )
                        );
                    ?>
                </li>
			    <li>
			        <?php
                        $label = sprintf('%s <span class="count">(%d)</span>', __('Pending queue'), $pending_count);
			            echo $this->Html->link(
			                $label,
			                array(
			                    'plugin'     => 'continuing_education',
			                    'controller' => 'instructors',
			                    'action'     => 'queue',
                                'return'     => base64_encode($this->here),
                            ),
                            array('escape' => false)
                        );
                    ?>
                </li>
				<li>
				    <?php
				        echo $this->Html->link(
				            __('Course Catalog'),
				            array(
				                'plugin'     => 'continuing_education',
				                'controller' => 'course_catalogs',
				                'action'     => 'index',
                                'return'     => base64_encode($this->here),
                            )
                        );
                    ?>
                </li>
				<li>
				    <?php
				        echo $this->Html->link(
				            __('Course Locations'),
				            array(
				                'plugin'     => 'continuing_education',
				                'controller' => 'course_locations',
				                'action'     => 'index',
                                'return'     => base64_encode($this->here),
                            )
                        );
                    ?>
                </li>
			</ul>
		</div>
	</div>
</div>
