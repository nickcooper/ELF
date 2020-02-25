<p>
    <ul class="button_select_list">
        <li>
            <a href="#">Generate Documents</a>
            <ul>
                <?php
                    foreach ($doc_links as $label => $type)
                    {
                        foreach ($type as $ext => $link)
                        {
                            echo '<li>';
                            echo $this->Html->link(
                                Inflector::humanize(sprintf('%s (%s)', $label, $ext)),
                                $link,
                                array()
                            );
                            echo '</li>';
                        }
                    }
                ?>
            </ul>
        </li>
    </ul>
</p>