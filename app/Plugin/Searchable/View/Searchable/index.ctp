
<?php
    if (isset($left_element) && $left_element):
        echo $this->element($left_element, array(), array('plugin' => $foreign_plugin));
    else:
        echo $this->element('section_nav');
    endif;
?>

<div id="section" class="span-19 last">
	<div class="pad">
		<h2><?php echo h($title); ?></h2>
		<hr/>

		<?php if (isset($add_button_element)): ?>
            <?php echo $this->element($add_button_element, array(), array('plugin' => $foreign_plugin)); ?>
        <?php endif; ?>

        <?php if (isset($filter_element)): ?>

        <div id="filter_holder">
            <?php echo $this->Form->create('Filter'); ?>

                <?php echo $this->element($filter_element, array(), array('plugin' => $foreign_plugin)); ?>

            <?php echo $this->Form->end(); ?>

        </div>
        <?php endif; ?>

        <?php if (count($results) == 0): ?>
            <div class="notice"><?php echo __('No records found. Sorry.'); ?></div>

        <?php else: ?>

		<table class="data" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
				    <?php foreach($fields as $field => $config): ?>

                        <th><?php echo $this->Paginator->sort($field, $config['label']); ?></th>

                    <?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($results as $result): ?>

				<tr>
                    <?php

                        $i = 0;
                        foreach($fields as $field => $config):

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
                            if (isset($config['link']))
                            {
                                // get the data for the url params
                                $url_params = array();
                                foreach ($config['link'][1] as $data_map)
                                {
                                    $url_params[] = Hash::get($result, $data_map);
                                }

                                // build the link
                                $val = $this->Html->link(
                                    $val,
                                    vsprintf($config['link'][0], $url_params).DS.'return:'.base64_encode($this->here)
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

        <?php endif; ?>

		<?php echo $this->element('pagination_links'); ?>

        <div style="clear: both;">
            <?php echo $this->element('download_link', array('plugin' => 'searchable', 'title' => $title)); ?>
        </div>
	</div>
</div>
