<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3><span class="pre">Page</span> <?php echo $page['Page']['title']; ?></h3>
            <p class="bottom"><?php echo $this->Html->link('< Back to Listing', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'index')); ?></p>
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
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
        <div id="page_information" class="form_section">
            <div class="span-12">
                <h3><?php echo $page['Page']['title']; ?> <span class="small" title="URL Slug"><?php echo $page['Page']['slug']; ?></span></h3>
            </div>
            <div class="span-5 last">
                <div class="actions">
                    <?php echo $this->Html->link('Edit Page', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'edit', $page['Page']['id']), array('class' => 'button small')); ?>
                </div>
            </div>
            <div class="box clear">
                <?php echo $page['Page']['content']; ?>
            </div>

            <table class="light_data" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th scope="col">Keywords</th>
                        <th scope="col">Created</th>
                        <th scope="col">Enabled</th>
                        <th scope="col">Modified</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo ($page['Page']['meta_keywords']!='') ? $page['Page']['meta_keywords'] : '<span class="blank">none</span>'; ?></td>
                        <td><?php echo $page['Page']['created']; ?></td>
                        <td><?php echo $this->Html->enableButton($this->params, $page); ?></td>
                        <td><?php echo $page['Page']['modified']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="actions">
            <?php echo $this->Html->link('Finished', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'index'), array('class' => 'button')); ?>
        </div>
    </div>
</div>