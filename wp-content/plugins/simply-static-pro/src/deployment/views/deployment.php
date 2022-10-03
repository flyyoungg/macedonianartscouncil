<div id='deployment' class='tab-pane'>
	<h3 class="title"><?php esc_html_e( 'CDN', 'simply-static-pro' ); ?></h3>
	<p>
	<?php
	echo sprintf(
		esc_html__( 'Deploying to an CDN is the easiest way to get your static website up and running in no time. We currently support %s as a provider.', 'simply-static-pro' ),
		'<a target="_blank" href="https://bunny.net/">BunnyCDN</a>',
	);
	?>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='cdn-provider'><?php esc_html_e( 'CDN Provider', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="cdn-provider" name="cdn-provider">
						<option value="bunnycdn">BunnyCDN</option>
					</select>
					<div id='deploymentHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Choose a CDN Provider you want to deploy to.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-api-key'><?php esc_html_e( 'CDN API Key', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='password' id='cdn-api-key' name='cdn-api-key' value='[CDN_API_KEY]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo sprintf( esc_html__( 'Enter your API Key for your CDN Provider. In case of BunnyCDN you can find your API-Key as described %s', 'simply-static-pro' ), '<a target="_blank" href="https://support.bunny.net/hc/en-us/articles/360012168840-Where-do-I-find-my-API-key">here</a>.' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-storage-host'><?php esc_html_e( 'Storage Host', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='cdn-storage-host' name='cdn-storage-host' value='[CDN_STORAGE_HOST]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo sprintf( esc_html__( 'Depending on your location, you have a different storage host. You find out which URL to use %s', 'simply-static-pro' ), '<a target="_blank" href="https://docs.bunny.net/reference/put_-storagezonename-path-filename">here</a>.' ); ?></p>
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-access-key'><?php esc_html_e( 'CDN Access Key', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='password' id='cdn-access-key' name='cdn-access-key' value='[CDN_ACCESS_KEY]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo esc_html__( 'Enter your Acess Key for your CDN Provider. In case of BunnyCDN you will find it within your storage zone setttings within FTP & API Access -> Password.', 'simply-static-pro' ); ?><br>
						<?php echo esc_html__( "It's required to perform actions like uploading, deleting files and clearing the cache of your zone.", 'simply-static-pro' ); ?>
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-pull-zone'><?php esc_html_e( 'Pull Zone', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='cdn-pull-zone' name='cdn-pull-zone' value='[CDN_PULL_ZONE]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php esc_html_e( 'A pull zone is the connection of your CDN to the internet. Simply Static will try to find an existing pull zone with the provided name, if there is none it creates a new pull zone.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-storage-zone'><?php esc_html_e( 'Storage Zone', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='cdn-storage-zone' name='cdn-storage-zone' value='[CDN_STORAGE_ZONE]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php esc_html_e( 'A storage zone contains your static files. Simply Static will try to find an existing storage zone with the provided name, if there is none it creates a new storage zone.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-directory'><?php esc_html_e( 'Directory', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='cdn-directory' name='cdn-directory' value='[CDN_DIRECTORY]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php esc_html_e( '[OPTIONAL] If you want to transfer the files to a specific sub directory on your storage zone add the name of that directory here.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-404'><?php esc_html_e( '404', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='cdn-404' name='cdn-404' value='[CDN_404]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo esc_html__( 'Relative path to your custom 404 page. For example /custom-404/', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='cdn-cname-entry'><?php esc_html_e( 'CDN CNAME', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					[CDN_CNAME]
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php esc_html_e( 'This is the CNAME entry of your pull zone. Add a CNAME entry to the DNS entries of your domain to connect it with your CDN.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 class="title"><?php esc_html_e( 'GitHub', 'simply-static-pro' ); ?></h3>
	<p>
	<?php
	echo sprintf(
		esc_html__( 'We use GitHub to automate the deloyment. This allows you to easily publish your static site to %s, %s, %s, %s or any other static hosting provider.', 'simply-static-pro' ),
		'<a target="_blank" href="https://patrickposner.dev/docs/simply-static/deployment/#GitHub-Pages">GitHub Pages</a>',
		'<a target="_blank" href="https://patrickposner.dev/docs/simply-static/deployment/#Cloudflare-Pages">Cloudflare Pages</a>',
		'<a target="_blank" href="https://patrickposner.dev/docs/simply-static/deployment/#AWS-S3">Amazon S3</a>',
		'<a target="_blank" href="https://patrickposner.dev/docs/simply-static/deployment/#Other-Providers">Digital Ocean Spaces</a>'
	);
	?>
	</p>
	<table class='form-table'>
		<tbody>
			<tr>
				<th>
					<label for='github-user'><?php esc_html_e( 'GitHub User / Organization', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='github-user' name='github-user' value='[GITHUB_USER]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo sprintf( esc_html__( 'Enter your GitHub username or the name of your organization. You can create an account for free %s If you are using a repository from an organization, please provide the name of the organization here.', 'simply-static-pro' ), '<a target="_blank" href="https://github.com/join">here</a>.' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-email'><?php esc_html_e( 'GitHub E-Mail', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='github-email' name='github-email' value='[GITHUB_EMAIL]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php esc_html_e( 'Enter your GitHub email address. This will be used to commit files to your repository.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-personal-access-token'><?php esc_html_e( 'Personal Access Token', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='password' id='github-personal-access-token' name='github-personal-access-token' value='[GITHUB_TOKEN]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p>
						<?php echo sprintf( esc_html__( 'You need a personal access token from GitHub. Get one %s', 'simply-static-pro' ), '<a target="_blank" href="https://docs.github.com/en/github/authenticating-to-github/creating-a-personal-access-token">here</a>.' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-repository'><?php esc_html_e( 'Name your repository', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='github-repository' name='github-repository' value='[GITHUB_REPOSITORY]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Enter a name for your repository. This should be lowercase and without any spaces or special characters.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-repository-visibility'><?php esc_html_e( 'Visiblity of your repository', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<select id="github-repository-visibility" name="github-repository-visibility">
						[GITHUB_VISIBILITY]
					</select>
					<div id='deploymentHelpBlock' class='help-block'>
						<p><?php esc_html_e( 'Decide if you want to make your repository public or private. Please check the pricing of your hoster for each option.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-branch'><?php esc_html_e( 'Name your branch', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='text' id='github-branch' name='github-branch' value='[GITHUB_BRANCH]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p><?php echo sprintf( esc_html__( '[OPTIONAL] add a name for your branch. Simply Static Pro automatically uses main as branch. You may want to modify that for example to %s for GitHub Pages.', 'simply-static-pro' ), '<a target="_blank" href="https://docs.github.com/en/github/working-with-github-pages/creating-a-github-pages-site">gh-pages</a>.' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-webhook-url'><?php esc_html_e( 'Webhook URL', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					<input type='url' id='github-webhook-url' name='github-webhook-url' value='[GITHUB_WEBHOOK]' class='widefat' />
					<div id='deploymentHelpBlock' class='help-block'>
						<p><?php echo esc_html_e( 'Enter your Webhook URL here and Simply Static will send a POST request after all files are commited to GitHub.', 'simply-static-pro' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for='github-repository-link'><?php esc_html_e( 'Link to your repository', 'simply-static-pro' ); ?></label>
				</th>
				<td>
					[GITHUB_LINK]
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
						[GITHUB_ADD]
						[GITHUB_DELETE]
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
