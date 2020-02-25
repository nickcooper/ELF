<?php
    $section_nav = array(
        'Add New Address' => array(
            'plugin' => 'address_book',
            'controller' => 'addresses',
            'action' => 'add',
            'fp' => $foreign_plugin,
            'fo' => $foreign_obj,
            'fk' => $foreign_key, 
        ),
        'Address Book' => array(
            'plugin' => 'address_book',
            'controller' => 'addresses',
            'action' => 'address_book',
            'fp' => $foreign_plugin,
            'fo' => $foreign_obj,
            'fk' => $foreign_key, 
        ),
    )
?>
<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo $humanized_foreign_obj; ?></span> Address Book
            </h3>
            
            <p class="bottom"><?php echo $this->IiHtml->returnLink(); ?></p>
        </div>
        <!--
        <div id="section_nav_holder">    
            <ul id="section_nav">
                <?php
                    // loop the section links
                    foreach($section_nav as $label => $route) : 
                        // add the return to the route
                        $route['return'] = $this->params['named']['return'];
                        
                        // set the selected class
                        $selected = '';
                        if($this->action == $route['action']):
                            $selected = 'selected';
                        endif;                        
                ?>
                <li class="<?php echo $selected; ?>">
                    <?php
                        echo $this->Html->link($label, $route); 
                    ?>
                </li>
                <?php endforeach; ?>
                
            </ul>
        </div>
    -->
    </div>
</div>