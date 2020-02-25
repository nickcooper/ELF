<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>Page</h3>
            <p class="bottom">Manage static pages</p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
    <div class="pad">
        <h2>Pages</h2>
        <hr />
        <div class="actions">
            <?php echo $this->Html->link('Add New Page', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'add'), array('class' => 'button')); ?>
        </div>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?php echo $this->Paginator->sort('Page.title', 'Title');?></th>
                    <th scope="col"><?php echo $this->Paginator->sort('Page.slug', 'Slug');?></th>
                    <th scope="col">Enabled<?php // echo $this->Paginator->sort('Page.enabled', 'Enabled');?></th>
                    <th scope="col"><?php echo $this->Paginator->sort('Page.modified', 'Modified');?></th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($pages as $page) : ?>
                <tr>
                    <td><?php echo $this->Html->link($page['Page']['title'], array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'view', $page['Page']['id'])); ?>&nbsp;</td>
                    <td><?php echo $page['Page']['slug']; ?>&nbsp;</td>
                    <td>##Enabled##</td>
                    <td><?php echo $page['Page']['modified']; ?>&nbsp;</td>
                </tr>
            </tbody>
        <?php endforeach; ?>
        </table>
        <?php echo $this->element('pagination_links'); ?>
    </div>
</div>