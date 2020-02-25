<?php echo $this->element('section_heading', array(), array('plugin' => 'reports')); ?>

<div id="section" class="span-19 last">
    <div class="pad">
        <h2><?php echo $page_name; ?></h2>
        <hr/>
        <?php echo $this->Form->create('Report', array('url' => '/reports/reports/search/'.$action));?>
            <fieldset>
                <legend>Filter Information</legend>
                <?php echo $this->Form->input('start', array('type' => 'datetime', 'div' => array('class' => 'form_item'), 'selected' => isset($filter['start'])?$filter['start']:date('Y-m-d H:i:s') )); ?>
                <?php echo $this->Form->input('end', array('type' => 'datetime', 'div' => array('class' => 'form_item'), 'selected' => isset($filter['end'])?$filter['end']:date('Y-m-d H:i:s') )); ?>
                <?php echo $this->Html->link('Today', array('start' => $date_shortcuts['today']['start'], 'end' => $date_shortcuts['today']['end'])); ?>
                <?php echo $this->Html->link('Yesterday', array('start' => $date_shortcuts['yesterday']['start'], 'end' => $date_shortcuts['yesterday']['end'])); ?>
                <?php echo $this->Html->link('Current Month', array('start' => $date_shortcuts['month']['start'], 'end' => $date_shortcuts['month']['end'])); ?>
                <?php echo $this->Html->link('Last Month', array('start' => $date_shortcuts['last_month']['start'], 'end' => $date_shortcuts['last_month']['end'])); ?>
            </fieldset>

            <div class="actions">
                <?php echo $this->Form->submit('Update Report', array('name' => 'submit_screen', 'class' => 'button'));?>
            </div>

            <?php if(count($rows) > 0): ?>
                <table class="data" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <?php foreach($headers as $header): ?>
                                <th><?php echo $header;?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $row): ?>
                            <tr>
                                <?php foreach($row as $cell): ?>
                                    <td>
                                        <?php if(is_array($cell) && isset($cell['label']) && isset($cell['url'])): ?>
                                            <?php echo $this->Html->link($cell['label'], $cell['url']); ?>
                                        <?php else: ?>
                                            <?php echo $cell; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php echo $this->Form->end();?>
    </div>
</div>
