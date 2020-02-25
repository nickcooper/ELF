<?php
    // cancel url
    $cancel_url = base64_decode($this->params['named']['return']);

    // build the cancel link
    $cancel_link = $this->Html->link(
        __('Cancel'),
        $cancel_url,
        array('class' => 'button cancel', 'title' => __('Get me outta here.'))
    );

    // build the add new link
    $add_new_link = '';
    if ($add_new):
        $add_new_link = $this->Html->link(
            sprintf(__('Add New %s'), $humanized_foreign_obj),
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'add',
                'fp'         => $foreign_plugin,
                'fo'         => $foreign_obj,
                'return'     => $this->params['named']['return'],
            ),
            array(
                'class' => 'button',
                'title' => __("Didn't find what you were looking for? Add a new one."),
            )
        );
    endif;
?>
<div id="section" class="full last">
    <div class="pad">
        <h2><?php echo __(Inflector::singularize($title).' Locator'); ?></h2>

        <table>
            <tr>
                <td style="width:500px;background-color:#FFF;border-bottom:none;">
                    <?php
                        // get the keywords values from the url
                        $keywords = '';
                        if (isset($this->params['named']['keywords']))
                        {
                            $keywords = $this->params['named']['keywords'];
                        }

                        echo $this->Form->create('Filter');
                        echo $this->Form->input('keywords', array('label' => false, 'style' => 'width:465px;', 'value' => $keywords));
                        echo $this->Form->submit('Search', array('after' => $cancel_link));
                        echo $this->Form->end();
                    ?>
                </td>
                <td style="vertical-align:top;padding-left:20px;background-color:#FFF;border-bottom:none;">
                    <h3><?php echo __('Better <em>search</em> than sorry!'); ?></h3>
                    <p>
                        <?php
                            if ($description) :
                                 echo sprintf(__("%s"), $description);
                            else :
                                echo sprintf(__("Search for the %s you wish to select. If you don't find it you can add a new one."), $humanized_foreign_obj);
                            endif
                        ?>
                    </p>
                </td>
            </tr>
        </table>

        <?php if (is_array($results) && count($results) == 0): ?>
            <table>
                <tbody>
                    <tr>
                        <td colspan="99" style="text-align:center;background-color:#FFF;border-bottom:none;">
                            <h3><?php echo __("Uh oh! We didn't find anything."); ?></h3>

                            <?php if ($add_new): ?>
                                <p>
                                    <?php echo sprintf(__('Use the <strong>Add New %s</strong> button below, or try another keyword search.'), $humanized_foreign_obj); ?>
                                </p>
                            <?php else : ?>
                                <p>
                                    <strong><?php echo __("We couldn't find any records matching your keywords. Try another keyword search."); ?></strong>
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php if (isset($add_button_element)): ?>
            <div class="actions">
                <?php echo $this->element($add_button_element, array(), array('plugin' => $foreign_plugin)); ?>
            </div>
            <?php endif; ?>
        <?php elseif (is_array($results) && count($results) > 0): ?>
            <?php echo $this->Form->create('Searchable', array('url' => base64_decode($this->params['named']['return']))); ?>

                <table class="data" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <?php foreach($fields as $field => $options): ?>

                                <th><?php echo $this->Paginator->sort($field, $options['label']); ?></th>

                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        foreach ($results as $result):
                            $i++;

                            $checked = '';
                            if ($i == 1)
                            {
                                $checked = "checked";
                            }
                    ?>
                        <tr>
                            <td style="text-align:center;">
                                <input type="radio" name="Searchable" value="<?php echo $result[$foreign_obj]['id']; ?>" <?php echo $checked; ?> />
                            </td>
                            <?php

                                $i = 0;
                                foreach($fields as $field => $options):

                                    $val = Hash::get($result, $field);

                                    // replace any null/empty values
                                    if (! preg_match('/[a-z0-9]+/i', $val)):
                                        $val = 'n/a';
                                    endif;

                                    // if it's a date shorten it to Y-m-d
                                    if (strtotime($val))
                                    {
                                        $val = GenLib::dateFormat($val);
                                    }

                                    // truncate the value if needed (removed)
                                    $non_truncated_val = $val;
                                    $val = $this->Text->truncate($val, 30, array('end' => '...'));

                                    // add the link if 'link' set in config
                                    if (isset($options['link']))
                                    {
                                        // get the data for the url params
                                        $url_params = array();
                                        foreach ($options['link'][1] as $data_map)
                                        {
                                            $url_params[] = Hash::get($result, $data_map);
                                        }

                                        // build the link
                                        $val = $this->Html->link(
                                            $val,
                                            vsprintf($options['link'][0], $url_params).DS.'return:'.base64_encode($this->here)
                                        );
                                    }

                                    // increment the field count
                                    $i++;

                                    // echo the value
                                    echo sprintf('<td><span title="%s">%s</span></td>', $non_truncated_val, $val);

                                endforeach;
                            ?>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>

                <?php echo $this->element('pagination_links'); ?>

                <div class="actions" style="clear:both;">
                    <?php
                        // combine the add_new and cancel links
                        $after_links = sprintf('%s %s', $add_new_link, $cancel_link);

                        // buttons
                        if(! $results):
                            echo $after_links;
                        else:
                            echo $this->Form->submit(sprintf(__('Choose Selected %s'), $humanized_foreign_obj), array('after' => $after_links));
                        endif;
                    ?>
                </div>
            <?php echo $this->Form->end(); ?>
        <?php endif; ?>

    </div>
</div>
