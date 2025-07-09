<div class="row">
    <div class="col-md-3 col-xl-2">
        <div class="list-group mb-3">
            <a href="<?php echo $url_builder->build('/settings/auth'); ?>"
               class="list-group-item list-group-item-action <?php echo ($active_tab === 'auth') ? 'active' : ''; ?>" <?php echo ($active_tab === 'auth') ? 'aria-current="true"' : ''; ?>>
                Authentication
            </a>
        </div>
        <a class="mb-3 d-block" href="<?php echo $url_builder->build('/'); ?>">
            <i class="bi bi-arrow-left"></i> Back to list
        </a>
    </div>
    <div class="col-md-9 col-lg-7 offset-lg-1 col-xl-6 offset-xl-2">
		<?php echo $content; ?>
    </div>
</div>