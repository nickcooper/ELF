<?php if (isset($actions) && count($actions)) : ?>
    <div class="actions">
        <?php
            // loop the action options
            foreach ($actions as $options) :
                foreach ($options['url'][1] as $param_key => $map) :
                    $options['url'][1][$param_key] = Hash::get($data, $map);
                endforeach;

                $link_type = 'link';
                if ($options['acl']) :
                    $link_type = 'aclLink';
                endif;

                $return = '';
                if ($options['return']) :
                    $return = '/return:'.base64_encode($this->here);
                endif;

                echo $this->Html->{$link_type}(
                    $options['label'],
                    vsprintf($options['url'][0], $options['url'][1]).$return,
                    $options['attr']
                );
            endforeach;
       ?>
    </div>
<?php endif; ?>