<div id='form' class='tab-pane'>
	<h2 class="title"><?php esc_html_e( 'CORS', 'simply-static-pro' ); ?></h2>
	<p>
	<?php esc_html_e( 'When using Forms and Comments in Simply Static Pro you may encouter CORS issues as you make requests from your static website to your original one.', 'simply-static-pro' ); ?></br>
	<?php esc_html_e( 'Due to the variety of server setups out there, you may need to make changes on your server. if you need a more advanced solution try out', 'simply-static-pro' ); ?> <a target="_blank" href="https://de.wordpress.org/plugins/http-headers/">HTTP Headers</a>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='static-url'><?php esc_html_e( 'Static URL', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='url' id='static-url' name='static-url' value='[STATIC_URL]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p class="description"><?php _e( 'Add the URL of your static website to allow CORS for it.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='fix-cors'><?php esc_html_e( 'Fix CORS with method', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="fix-cors" name="fix-cors">
						[FIX_CORS]
					</select>
					<div id='commentsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Choose one of the methods to allow CORS for your website.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h2 class="title"><?php esc_html_e( 'Comments', 'simply-static-pro' ); ?></h2>
	<p>
	<?php esc_html_e( 'Activate the usage of static comments. It uses a webhook to send the data to the configured endpoint.', 'simply-static-pro' ); ?></br>
	<?php esc_html_e( 'Make sure to save your permalinks in Settings->Permalinks after saving an endpoint.', 'simply-static-pro' ); ?>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='use-comments'><?php esc_html_e( 'Use comments?', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="use-comments" name="use-comments">
						[USE_COMMENTS]
					</select>
					<div id='commentsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Decide whether or not you want to use comments on your static site.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='comment-redirect'><?php esc_html_e( 'Comment Redirect', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='url' id='comment-redirect' name='comment-redirect' value='[COMMENT_REDIRECT]' class='widefat' />
					<div id='commentHelpBlock' class='help-block'>
						<p>
							<?php esc_html_e( 'Due to the fact that the page needs to be regenerated after a comment was added, you should redirect your user to a custom thank you for your comment page.', 'simply-static-pro' ); ?><br>
							<?php esc_html_e( 'The page will be generated and commited automatically, but it may take up to a minute.', 'simply-static-pro' ); ?>
						</p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h2 class="title"><?php esc_html_e( 'Forms', 'simply-static-pro' ); ?></h2>
	<p>
	<?php esc_html_e( 'Generate a form index file to use forms on your static website. This file will be used to connect the form on your website with the connector you added in Simply Static -> Forms. ', 'simply-static-pro' ); ?>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='use-forms'><?php esc_html_e( 'Use forms?', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="use-forms" name="use-forms">
						[USE_FORMS]
					</select>
					<div id='formsHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Decide whether or not you want to use forms on your static site.', 'simply-static-pro' ); ?></p>
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
						[CREATE_FORM_CONFIG]
						<span class="spinner"></span>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
