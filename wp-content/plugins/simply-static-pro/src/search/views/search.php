<div id='search' class='tab-pane'>
	<h2 class="title"><?php esc_html_e( 'Search', 'simply-static-pro' ); ?></h2>
	<p>
	<?php esc_html_e( 'Activate the usage of static search. It uses fuse.js or the Algolia API and creates an complete index to search by title and content of each page.', 'simply-static-pro' ); ?>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='use-search'><?php esc_html_e( 'Use search?', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="use-search" name="use-search">
						[USE_SEARCH]
					</select>
					<div id='formsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Decide whether or not you want to use search on your static site.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='static-search-url'><?php esc_html_e( 'Static URL', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='url' id='static-search-url' name='static-search-url' value='[STATIC_URL]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add the URL of your static website to create a working index with correct URLs for it.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='search-type'><?php esc_html_e( 'Search-Type', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="search-type" name="search-type">
						[SEARCH_TYPE]
					</select>
					<div id='formsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Decide wich search type you want to use. Fuse runs locally based on file and Algolia is an external API service.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 class="title"><?php esc_html_e( 'Indexing', 'simply-static-pro' ); ?></h3>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='search-index-title'><?php esc_html_e( 'CSS-Selector for Title', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type="text" id="search-index-title" name="search-index-title" value="[SEARCH_INDEX_TITLE]" class="widefat" />
					<div id="commentHelpBlock" class="help-block">
					<p class="description"><?php _e( 'Add the CSS selector which contains the title of the page/post', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='search-index-content'><?php esc_html_e( 'CSS-Selector for Content', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='search-index-content' name='search-index-content' value='[SEARCH_INDEX_CONTENT]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add the CSS selector which contains the content of the page/post.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='search-index-excerpt'><?php esc_html_e( 'CSS-Selector for Excerpt', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='search-index-excerpt' name='search-index-excerpt' value='[SEARCH_INDEX_EXCERPT]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add the CSS selector which contains the excerpt of the page/post.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='search-esclude-url'><?php esc_html_e( 'Exclude URLs', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<?php
					$options             = get_option( 'simply-static' );
					$urls_search_exclude = array();

					if ( ! empty( $options['search-excludable'] ) ) {
						$urls_search_exclude = $options['search-excludable'];
					}
					?>
					<div id="excludable-search-url-rows">
					<?php foreach ( $urls_search_exclude as $index => $url_search_exclude ) : ?>
						<div class="excludable-search-url-row" <?php if ( $index === 0 ) : ?> id="excludable-search-url-row-template"<?php endif; ?>>
							<input type="text" name="search-excludable[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $url_search_exclude ); ?>" size="40" />
							<input class="button remove-excludable-search-url-row" type="button" name="remove" value="<?php esc_html_e( 'Remove', 'simply-static-pro' ); ?>" />
						</div>
					<?php endforeach; ?>
					</div>

					<div>
						<input class='button' type='button' name='exclude_search_url' id="exclude-search-url" value='<?php esc_html_e( 'Add URL to Exclude', 'simply-static-pro' ); ?>' />
					</div>
					<div id='excludeUrlsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Exclude URLs from indexing. You can use full URLs, parts of an URL or plain words (like stop words).', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 class="title"><?php esc_html_e( 'Fuse.js', 'simply-static-pro' ); ?></h3>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='search-shortcode'><?php esc_html_e( 'Search Shortcode', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<code>[ssp-search]</code>
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Copy the shortcode and add it to your website. It renders a search box with autosuggestion.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 class="title"><?php esc_html_e( 'Algolia', 'simply-static-pro' ); ?></h3>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='algolia-app-id'><?php esc_html_e( 'Application ID', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='algolia-app-id' name='algolia-app-id' value='[ALGOLIA_APP_ID]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add your Algolia App ID here.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='algolia-admin-api-key'><?php esc_html_e( 'Admin API Key', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='algolia-admin-api-key' name='algolia-admin-api-key' value='[ALGOLIA_ADMIN_API_KEY]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add your Algolia Admin API Key here.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='algolia-search-api-key'><?php esc_html_e( 'Search-Only API Key', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='algolia-search-api-key' name='algolia-search-api-key' value='[ALGOLIA_SEARCH_API_KEY]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add your Algolia Search-Only API Key here. This is the only key that will be visible on your static site.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='algolia-index'><?php esc_html_e( 'Name for your index', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='algolia-index' name='algolia-index' value='[ALGOLIA_INDEX]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add your Algolia index name here. Default is simply_static', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='algolia-selector'><?php esc_html_e( 'CSS-Selector', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='algolia-selector' name='algolia-selector' value='[ALGOLIA_SELECTOR]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add the CSS selector of your search element here. The default value is .search-field', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class='form-table'>
		<tbody>
			<tr>
				<th></th>
				<td>
					<p class='submit'>
						<input class='button button-primary' type='submit' name='save' value='<?php esc_html_e( 'Save Changes', 'simply-static-pro' ); ?>' />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
