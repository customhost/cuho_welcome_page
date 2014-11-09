<?php
	/**
	* Customhost software
	* Show or redirect user to the welcome page
	*
	* @package	cuho_welcome_page
	* @version	1.0
	* @author 	Artem Oliynyk of Customhost
	* @copyright	Copyright (c), Customhost  2014
	* @license	GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.txt
	* @link		http://cuho.eu/
	*
	* 
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*/

	$AMP = AMP;
	$BASE = BASE;
?>

<style type="text/css">
	#channels_entries {
		/*display: none;*/
	}
</style>

<script type="text/javascript">
	$(document).ready( function() {
		$("#channel").change( function() {
			var cid = parseInt( $(this).val() );

			if( cid > 0 ) {
				$("#entry").fadeIn( 200 );
			}
			else {
				$("#entry").fadeOut( 200 );
			}
		});
	});
</script>

<?php echo form_open( "{$module_base}&method=save_categories"); ?>

	<p>
		<?php echo lang( 'Here you can enable or disabla "welcome page" functionality' ); ?>
	</p>

	<table class="mainTable cats-groups" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<th colspan="2">
			Welcome page settings
		</th>
	</tr>
	<tr>
		<td>
			<b>Welcome page</b>
		</td>
		<td>
			<select name="page_id">
				<option value="0"><?= lang( '-- select entry --' ); ?></option>
				<?php foreach( $channels_entries as $chanel => $entries ): ?>
					<optgroup label="<?= htmlspecialchars( $chanel ); ?>">
						<?php foreach( $entries as $entry_id => $entry_title ): ?>
							<option value="<?= $entry_id; ?>" <?= (int) $settings['page_id'] == (int) $entry_id ? 'selected="selected"' : '' ?>><?= htmlspecialchars( $entry_title ); ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<b>Mode</b>
		</td>
		<td>
			<select name="redirect">
				<option value="0"><?= lang( 'Show page' ); ?></option>
				<option value="1" <?= (int) $settings['redirect'] ? 'selected="selected"' : '' ?>><?= lang( 'Redirect to URL' ); ?></option>
			</select>
		</td>
	</tr>
	<!-- <tr>
		<td>
			<b>Page display:</b>
		</td>
		<td>
			<select name="display">
				<option value="0"><?= lang( 'Show page again after re-enable ( Status: open > close > open )' ); ?></option>
				<option value="1" <?= (int) $settings['display'] ? 'selected="selected"' : '' ?>><?= lang( 'Show page only once with Status "Open"' ); ?></option>
			</select>
		</td>
	</tr> -->
	<tr>
		<td>
			<b>Page URL</b>
		</td>
		<td>
			<input name="url" size="100" value="<?= htmlspecialchars( $settings['url'] ); ?>" />
			<?php if( empty( $settings['url'] ) ): ?>
				<br/><b><?=lang('Warning') ?>:</b> <?=lang('Page URL is empty, but please enter page URL ( full or site relative )') ?>.
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Force page display/redirect next time</b>
		</td>
		<td>
			<label>
				<input type="checkbox" name="force" value="1" />
				<?=lang('tick this box to show/redirect page for user who already seen it') ?>
			</label>
		</td>
	</tr>
	</table>

	<br/>

	<input type="submit" class="submit" name="save" value="<?php echo lang( 'Save changes' ); ?>" />
	&nbsp;
	<input type="submit" class="submit" name="cancel" value="<?php echo lang( 'Cancel' ); ?>" />

<?php echo form_close(); ?>