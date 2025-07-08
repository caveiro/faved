<div class="row">
    <div class="col-md-3 col-lg-2">
        <div class="offcanvas-md offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebar-label">
            <div class="offcanvas-header d-md-none">
                <h5 class="offcanvas-title" id="sidebar-label">Faved</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="sidebar-content">
                    <div class="list-group mb-3">
                        <a class="list-group-item list-group-item-action <?php echo $selected_tag === null ? 'active' : ''; ?>"
                           href="<?php echo $url_builder->build('/'); ?>">All items</a>
                        <a class="list-group-item list-group-item-action <?php echo $selected_tag === 'notag' ? 'active' : ''; ?>"
                           href="<?php echo $url_builder->build('/', ['tag' => 'notag']); ?>">Untagged </a>
                    </div>
                    <ul class="tags-list list-group">
						<?php echo $pinned_tags_output; ?>
						<?php echo $unpinned_tags_output; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-lg-10">
        <div class="mb-4 d-flex justify-content-end justify-content-md-start align-items-center flex-wrap gap-2">
            <button class="btn btn-outline-dark d-md-none me-auto" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebar" aria-controls="sidebar">
                <i class="bi bi-list"></i>
            </button>
            <a class="btn btn-outline-primary"
               href="<?php echo $url_builder->build('/item', ['return' => urlencode($_SERVER['REQUEST_URI'])]); ?>"
               id="add-item-button" role="button">
                New item
            </a>
            <a class="btn btn-outline-danger"
               href="<?php echo $url_builder->build('/pocket-import'); ?>"
               role="button">
                Import from Pocket
            </a>
            <a class="btn btn-outline-secondary ms-md-auto"
               data-bs-title="This is as bookmarklet" data-bs-html="true"
               data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-trigger="hover"
               data-bs-content="A bookmarklet is a bookmark stored in a web browser that contains JavaScript commands that add new features to the browser. Unlike browser extensions, they are lightweight and don't have access to your viewed page until you intentionally click to use them.<br><br> Drag this button to your browser bookmarks bar to save the bookmarklet that will let you quickly add any page you visit to Faved."
               href='javascript:(function(){ var meta_description = document.querySelector("meta[name=\"description\"]"); if (meta_description) { meta_description = meta_description.getAttribute("content"); } var rspW=700, rspH=700, rspL=parseInt((screen.width/2)-(rspW/2)), rspT=parseInt((screen.height/2)-(rspH/2)); window.open("http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; ?>?route=/item&url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&description="+((meta_description) ? encodeURIComponent(meta_description) : ""),"add-to-faved","width="+rspW+",height="+rspH+",resizable=yes,scrollbars=yes,status=false,location=false,toolbar=false,left="+rspL+",top="+rspT) })();'>Add
                to Faved</a>
        </div>

		<?php include ROOT_DIR . '/views/partials/flash-messages.php'; ?>

        <div class="d-table w-100">
			<?php foreach ($items as $item_id => $item) : ?>
                <div class="d-table-row">
                    <div class="d-table-cell text-break pb-4 pe-2">
						<?php if (!empty($item['image'])) : ?>
                            <a href="<?php echo htmlspecialchars($item['image']); ?>"
                               class="me-3 float-start border-1 border border-secondary border-opacity-10 p-1">
                                <img style="max-width:150px;max-height:150px;"
                                     alt="<?php echo htmlspecialchars($item['image']); ?>"
                                     src="<?php echo htmlspecialchars($item['image']); ?>"/>
                            </a>
						<?php endif; ?>

                        <h6 class="mb-2">
							<?php echo htmlspecialchars($item['title']); ?>
                        </h6>

						<?php if ($item['url']) : ?>
                            <div class="my-2">
                                <a href="<?php echo htmlspecialchars($item['url']); ?>">
									<?php echo htmlspecialchars($item['url']); ?>
                                </a>
                            </div>
						<?php endif; ?>

                        <div class="d-sm-none text-break my-2">
							<?php echo nl2br(htmlentities($item['description'])); ?>

							<?php if (!empty($item['comments'])) : ?>
                                <div class="bi bi-chat-left-text mt-2">
									<?php echo nl2br(htmlentities($item['comments'])); ?>
                                </div>
							<?php endif; ?>
                        </div>

						<?php foreach ($item['tags'] as $tag_id) : ?>
							<?php echo $tag_renderer->render($tag_id); ?>
						<?php endforeach; ?>

                        <small class="text-nowrap d-block d-md-inline" style="line-height: 2.5">
							<?php echo $item['created_at']; ?>
                        </small>
                    </div>
                    <div class="d-none d-sm-table-cell text-break pb-4 pe-2" style="width: 45%">
						<?php echo nl2br(htmlentities($item['description'])); ?>

						<?php if (!empty($item['comments'])) : ?>
                            <div class="bi bi-chat-left-text mt-2">
								<?php echo nl2br(htmlentities($item['comments'])); ?>
                            </div>
						<?php endif; ?>
                    </div>
                    <div class="d-table-cell">
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a class="dropdown-item"
                                       href="<?php echo $url_builder->build('/item', ['item-id' => $item_id, 'return' => urlencode($_SERVER['REQUEST_URI'])]); ?>">
                                        Edit
                                    </a>
                                </li>
                                <li>

                                    <button type="button"
                                            class="dropdown-item"
                                            onclick="submitRequest(
                                                    'DELETE',
                                                    '<?php echo $url_builder->build('/item', ['item-id' => $item_id, 'return' => urlencode($_SERVER['REQUEST_URI'])]); ?>',
                                                    '<?php echo htmlspecialchars($csrf_token); ?>',
                                                    'Are you sure you want to delete this item?'
                                                    )"
                                    >
                                        Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
        <div class="text-center">
			<?php echo count($items); ?>
			<?php echo count($items) !== 1 ? 'items' : 'item'; ?>
        </div>
    </div>
</div>
