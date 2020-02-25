<?php
    $fields = '';

    if ($searchable_keyword_fields):
        $fields = sprintf('<p><strong>%s:</strong> %s</p>', __('Keyword Search Fields'), join(', ', $searchable_keyword_fields));
    endif;

    $search_button = $this->Form->submit(
        __('Search'),
        array(
            'class'    => 'button submit inline small',
            'stripped' => true,
            'after'    => '',
        )
    );

    $advanced_link = $this->Html->link(
        __('advanced search'),
        '#filter_options',
        array(
            'class' => 'toggle inline',
            'rel'   => '#filter_options',
        )
    );

    $after_stuff = $search_button . $advanced_link . $fields;
?>

<div id="filter_search" class="filter_group">
    <?php
        echo $this->Form->input(
            __('keywords'),
            array(
                'type'        => 'text',
                'class'       => 'span-9 text',
                'placeholder' => __('Enter Keyword...'),
                'label'       => false,
                'value'       => isset($this->params->named['keywords']) ? $this->params->named['keywords'] : '',
                'after'       => sprintf('%s</div>', $after_stuff), // the closing div is needed to close the input_holder
            )
        );
    ?>
</div>