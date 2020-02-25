<div class="span-14 prepend-5 append-5 last">
    <div id="login_box" class="pad">
        <h2><?php echo __('Login'); ?></h2>
        <hr />
        <p class="attn">
            <?php
                echo sprintf(
                    'This site requires you have an account in Enterprise A&A to login. If you do not already have an Enterprise A&A Services account you may register %s.',
                    $this->Html->link('here', $reg_link)
                );
            ?>
        </p>
        <p>
            <?php echo $this->Html->image($this->Html->url('/aa_auth/img/aasignon.gif', true), array('alt' => __('Sign In Using A&A'), 'url' => $login_link)); ?>
        </p>
    </div>
</div>