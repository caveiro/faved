<div class="row">
    <div class="col-md-2">

        <div style="max-height: calc(100vh - 40px); overflow-y: auto; margin-bottom: 15px;">

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
    <div class="col-md-10">
        <div class="mb-4 d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <div>
                <a class="btn btn-outline-primary me-2"
                   href="<?php echo $url_builder->build('/item', ['return' => urlencode($_SERVER['REQUEST_URI'])]); ?>"
                   id="add-item-button" role="button">
                    New item
                </a>
                <a class="btn btn-outline-danger"
                   href="<?php echo $url_builder->build('/pocket-import'); ?>"
                   role="button">
                    Import from Pocket
                </a>
            </div>
            <span><?php echo count($items); ?> items</span>
            <span>
                Bookmarklet: <a
                        href='javascript:(function(){ var meta_description = document.querySelector("meta[name=\"description\"]"); if (meta_description) { meta_description = meta_description.getAttribute("content"); } var rspW=700, rspH=700, rspL=parseInt((screen.width/2)-(rspW/2)), rspT=parseInt((screen.height/2)-(rspH/2)); window.open("http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; ?>?route=/item&url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&description="+((meta_description) ? encodeURIComponent(meta_description) : ""),"add-to-faved","width="+rspW+",height="+rspH+",resizable=yes,scrollbars=yes,status=false,location=false,toolbar=false,left="+rspL+",top="+rspT) })();'>Add to Faved</a>
            </span>
        </div>

		<?php include ROOT_DIR . '/views/partials/flash-messages.php'; ?>

        <table class="table table-hover" style="margin-top: 15px">
            <thead>
            <tr>
                <th>Image / Title / Url / Tags / Created at</th>
                <th>Description / Notes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($items as $item_id => $item) : ?>
                <tr>
                    <td style="max-width:400px; word-wrap: break-word;">
						<?php if (!empty($item['image'])) : ?>
                            <a href="<?php echo htmlspecialchars($item['image']); ?>"
                               style="margin-right:10px; float:left;">
                                <img style="max-width:150px;max-height:150px;" alt=""
                                     src="<?php echo htmlspecialchars($item['image']); ?>"/>
                            </a>
						<?php endif; ?>

                        <h6 style="margin:5px 0;">
							<?php echo htmlspecialchars($item['title']); ?>
                        </h6>
						<?php if ($item['url']) : ?>
                            <a href="<?php echo htmlspecialchars($item['url']); ?>"
                               style="display:block; margin:5px 0;">
								<?php echo htmlspecialchars($item['url']); ?>
                            </a>
						<?php endif; ?>

						<?php foreach ($item['tags'] as $tag_id) : ?>
							<?php echo $tag_renderer->render($tag_id); ?>
						<?php endforeach; ?>

                        <small>
							<?php echo $item['created_at']; ?>
                        </small>
                    </td>
                    <td style="max-width:400px; word-wrap: break-word;">
						<?php echo nl2br(htmlentities($item['description'])); ?>

						<?php if (!empty($item['comments'])) : ?>
                            <div style="margin-top: 10px;">
                                <b>Notes</b>
                            </div>
                            <div>
								<?php echo nl2br(htmlentities($item['comments'])); ?>
                            </div>
						<?php endif; ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Action <span class="caret"></span>
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
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
