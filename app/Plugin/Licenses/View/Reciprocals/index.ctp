<div id="body" class="span-24">
    <div id="pre" class="span-5">
        <div class="stationary">
            <div id="section_head" class="black-box">
                <h3><span class="pre">RECIPROCAL</span> Course Information</h3>
                <p class="bottom">
                    <?php echo $this->IiHtml->returnLink(); ?>
                </p>
            </div>
            <div id="section_nav_holder">    
                <ul id="section_nav">
                    <li class="">
                        <?php
                            echo $this->Html->link(
                                'Add Course Hours', 
                                array(
                                    'plugin' => 'licenses', 
                                    'controller' => 'reciprocals', 
                                    'action' => 'add', 
                                    'fp' => $foreign_plugin,
                                    'fo' => $foreign_obj,
                                    'fk' => $foreign_key,
                                    'return' => base64_encode($this->here),
                                ), 
                                array()
                            ); 
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3>Reciprocal Course Information</h3>
            <div style="padding:0px 20px 0px;">
                <p>Explain what reciprocal courses are and how they apply to State of Iowa licenses.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut sagittis lacus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam porttitor velit in erat gravida pellentesque. Pellentesque laoreet consequat dolor, ut commodo mi facilisis sed. Integer diam ligula, mollis posuere hendrerit nec, ullamcorper sit amet dui. Donec lobortis aliquam velit eu volutpat. Vestibulum nibh erat, facilisis nec pretium nec, pharetra vitae enim.</p>
            </div>
            <fieldset>
                <legend>Reciprocal Courses</legend>
                <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <th>Course</th>
                            <th>Hours</th>
                            <th>Date</th>
                            <th>Transcript</th>
                            <th>Passed</th>
                            <th>Actions</th>
                        </tr>
                        
                        <?php foreach($reciprocals as $reciprocal) : ?>
                        
                        <tr>
                            <td>
                                <?php 
                                    echo $this->Html->link(
                                        $reciprocal['Reciprocal']['course_title'],
                                        array(
                                            'plugin' => 'licenses',
                                            'controller' => 'reciprocals',
                                            'action' => 'edit',
                                            $reciprocal['Reciprocal']['id'],
                                            'fp' => $foreign_plugin,
                                            'fo' => $foreign_obj,
                                            'fk' => $foreign_key,
                                            'return' => base64_encode($this->here),
                                        )
                                    ); 
                                ?>
                            </td>
                            <td><?php echo $reciprocal['Reciprocal']['hours']; ?></td>
                            <td><?php echo $reciprocal['Reciprocal']['completed_date']; ?></td>
                            <td>
                                <?php 
                                    if (GenLib::isData($reciprocal, 'Upload', array('id'))) :
                                        echo $this->Html->link(
                                            'Transcript',
                                            sprintf('/files/%s', $reciprocal['Upload']['file_name'])
                                        );
                                    else:
                                        echo 'n/a';
                                    endif; 
                                ?>
                            </td>
                            <td><?php echo ($reciprocal['Reciprocal']['pass'] ? 'Yes' : 'No'); ?></td>
                            <td width="85">
                                <?php 
                                    echo $this->Html->link(
                                        'Remove', 
                                        array(
                                            'plugin' => 'licenses', 
                                            'controller' => 'reciprocals', 
                                            'action' => 'delete', 
                                            $reciprocal['Reciprocal']['id'],
                                            'return' => base64_encode($this->here)
                                        ), 
                                        array(
                                            'title' => 'Remove certificate',
                                            'class' => 'iconify warning '), 
                                            __('Are you sure you want to remove %s?', 'course_name_here')
                                    );
                                ?>
                            </td>
                        </tr>
                        
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            </fieldset>
            <h4>Valid Reciprocal Hours: <?php echo $ttl_hours; ?></h4>
            <div class="actions">
                <?php 
                    echo $this->Html->link(
                        'Add Course Hours', 
                        array(
                            'plugin' => 'licenses', 
                            'controller' => 'reciprocals', 
                            'action' => 'add', 
                            'fp' => $foreign_plugin,
                            'fo' => $foreign_obj,
                            'fk' => $foreign_key,
                            'return' => base64_encode($this->here),
                        ), 
                        array('class' => 'button', 'target' => '_blank')
                    ); 
                    
                    echo $this->Html->link(
                        'Cancel',
                        base64_decode($this->params['named']['return']),
                        array('class' => 'button cancel')
                    ); 
                ?>
            </div>
        </div>
    </div>
</div>