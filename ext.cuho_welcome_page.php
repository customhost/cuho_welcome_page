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
	
	if( !defined( 'BASEPATH' ) ) 
		exit( 'No direct script access allowed' );

	class Cuho_welcome_page_ext {
		var $name = 'CUHO Welcome Page';
		var $version = '1.0';
		var $description = 'Show user a page or redirect to the URL on first visit or page status';
		var $settings_exist = 'y';
		var $docs_url = '';

		protected $basename = 'cuho_welcome_page';

		// 'http://ellislab.com/expressionengine/user-guide/';
		var $settings = array( );

		public function __construct( $settings = array( ) ) {
			$AMP = AMP;
			$BASE = @constant('BASE');
			
			$this->settings = $settings;
			
			$this->module_base = "{$BASE}{$AMP}C=addons_extensions{$AMP}M=save_extension_settings{$AMP}file={$this->basename}";
		}

		/**
		 * Activate Extension
		 *
		 * This public function enters the extension into the exp_extensions table
		 *
		 * @see http://ellislab.com/codeigniter/user-guide/database/index.html for
		 * more information on the db class.
		 *
		 * @return void
		*/

		public function activate_extension( ) {
			$this->settings = array(
				'page_id' => 0,
				'redirect' => 0,
				'url' => '',
				'last_status' => 'open',
				'show_hash' => false,
				// 'force_show' => false,
			);
			
			$data = array(
				'class' => __CLASS__,
				'method' => 'process_welcome',
				'hook' => 'core_template_route',
				'settings' => serialize( $this->settings ),
				'priority' => 10,
				'version' => $this->version,
				'enabled' => 'y',
			);
			ee()->db->insert( 'extensions', $data );
			

			$data = array(
				'class' => __CLASS__,
				'method' => 'entry_changed',
				'hook' => 'entry_submission_absolute_end',
				'settings' => serialize( $this->settings ),
				'priority' => 10,
				'version' => $this->version,
				'enabled' => 'y',
			);
			ee()->db->insert( 'extensions', $data );
		}

		/**
		 * Update Extension
		 *
		 * This public function performs any necessary db updates when the extension
		 * page is visited
		 *
		 * @return  mixed   void on update / false if none
		*/

		public function update_extension( $current = '' ) {
			if( $current == '' OR $current == $this->version ) {
				return FALSE;
			}
			
			if( $current < '1.0' ) {

				// Update to version 1.0
			}
			
			ee()->db->where( 'class', __CLASS__ );
			ee()->db->update( 'extensions', array( 'version' => $this->version ) );
		}

		/**
		 * Disable Extension
		 *
		 * This method removes information from the exp_extensions table
		 *
		 * @return void
		*/

		public function disable_extension( ) {
			ee()->db->where( 'class', __CLASS__ );
			ee()->db->delete( 'extensions' );
		}

		/**
		 * Process welcome page status
		 *
		 * This function is the meat & potatoes of the extension, where all
		 * the work is done.
		 *
		 * @see http://ellislab.com/expressionengine/user-guide/development/extension_hooks/global/typography/index.html#typography-parse-type-end
		 *
		 * @param   string  string to look
		 * @param   object  typography object
		 * @param   array   array of preferences
		 * @return  string
		*/

		public function process_welcome( $uri_string ) {

			$page_id = (int) $this->settings['page_id'];

			if( $page_id ) {
				$page = ee()->db->select( 'entry_id, status' )->from( 'channel_titles' )->where( 'entry_id', $page_id )->get( );

				// avoid redirtecting infinitely
				if( empty( $this->settings['show_hash'] ) ) {
					$this->settings['show_hash'] = md5( $page_id );
				}
				
				if( $page->num_rows ) {
					ee()->load->helper('url_helper');

					if( isset( $_REQUEST['_ch_wpp'] ) ) {
						setcookie( 'cuho_welcome_page', $this->settings['show_hash'], time() + 86400 * 365 );
						redirect( $uri_string );
					}

					$page_data = $page->row_array( );
					$open = ( $page_data['status'] == 'open' );

					if( $open && !isset( $_GET['_ch_rd'] ) ) {

						$show = ( !isset( $_COOKIE['cuho_welcome_page'] ) || $_COOKIE['cuho_welcome_page'] != $this->settings['show_hash'] );
						$redirect = ( 1 == (int) $this->settings['redirect'] );
						
						if( $show ) {
							$url = $this->settings['url'];

							$proto = substr( URL_THIRD_THEMES, 0, 5);
							$proto = trim( $proto, ':' );

							if( false === strpos( $url, '://' ) ) {
								$url = "{$_SERVER['HTTP_HOST']}/{$url}";
								$url = "{$proto}://" . str_replace( '//', '/', $url );
							}

							if( $redirect ) {
								$parsed = parse_url( $url );

								if( !isset( $parsed['query'] ) ) {
									$parsed['query'] = '_ch_rd=1';
								}
								elseif( false === strpos( $parsed['query'], '_ch_rd' ) ) {
									$parsed['query'] .= '&_ch_rd=1';	
								
								}
								$url = "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}?{$parsed['query']}";

								redirect( $url );
							}
							else {
								$this->settings['page_id'] = 0;
								ee()->db->where( 'class', __CLASS__ )->update( 'extensions', array( 'settings' => serialize( $this->settings ) ) );

								$page_content = file_get_contents( $url );

								$this->settings['page_id'] = $page_id;
								ee()->db->where( 'class', __CLASS__ )->update( 'extensions', array( 'settings' => serialize( $this->settings ) ) );

								// ob_clean();
								die( $page_content );
							}
						}
					}
				}
			}

			return $uri_string;
		}

		/** 
		* @param entry_id	int		Entry ID of submitted entry
		* @param meta		array	Entry’s metadata (channel_id, entry_date, i.e. fields for exp_channel_titles)
		* @param data		array	Entry’s field data
		* @param view_url	string	Control Panel URL to view submitted entry
		*/
		public function entry_changed( $entry_id, $meta, $data, $view_url ) {
			if( (int) $this->settings['page_id'] == (int) $entry_id ) {
				$this->settings['show_hash'] = md5( $page_id . $meta['edit_date'] );

				$this->settings['last_status'] = $meta['status'];

				ee()->db->where( 'class', __CLASS__ );
				ee()->db->update( 'extensions', array( 'settings' => serialize( $this->settings ) ) );
			}
		}

		/**
		 * Settings Form
		 *
		 * @param   Array   Settings
		 * @return  void
		 */
		function settings_form( $current ) {
			ee()->load->helper( 'form' );
			ee()->load->library( 'table' );
			
			$entries = array( );
			$channels = ee()->db->select( 'channel_id, channel_title' )->from( 'channels' )->get( );
			if( $channels->num_rows( ) ) {
				foreach( $channels->result_array( ) as $chann ) {
					$chan_entries[$chann['channel_title']] = array( );
					
					$chan_entries = ee()->db->select( 'entry_id, title' )->from( 'channel_titles' )->where( 'channel_id', $chann['channel_id'] )->get( );
					
					if( $chan_entries->num_rows( ) ) {
						foreach( $chan_entries->result_array( ) as $entry ) {
							$entries[$chann['channel_title']][$entry['entry_id']] = $entry['title'];
						}
					}
					ee()->db->flush_cache( );
					unset( $chan_entries );
				}
			}
			
			$vars = array( );
			$vars['module_base'] = $this->module_base;
			$vars['settings'] = (array) $current;
			$vars['channels_entries'] = $entries;
			
			if( ee()->config->item( 'forum_is_installed' ) == 'y' ) {
				$use_in_forum = isset( $current['use_in_forum'] ) ? $current['use_in_forum'] : 'no';
				
				$vars['settings']['use_in_forum'] = form_dropdown( 'use_in_forum', $yes_no_options, $use_in_forum );
			}
			
			return ee()->load->view( 'settings', $vars, TRUE );
		}

		/**
		 * Save Settings
		 *
		 * This function provides a little extra processing and validation
		 * than the generic settings form.
		 *
		 * @return void
		*/

		function save_settings( ) {
			
			if( isset( $_POST ) && !empty( $_POST ) ) {
				$settings = $_POST;
				unset( $settings['save'], $settings['force'] );
				
				$settings['last_status'] = 'open';
				$settings['show_hash'] = '';

				
				$page_id = (int) $settings['page_id'];
				
				if( $page_id ) {
					$page = ee()->db->select( 'entry_id, status, edit_date' )->from( 'channel_titles' )->where( 'entry_id', $page_id )->get( );
					
					if( !$page->num_rows ) {
						$error = true;
						ee()->session->set_flashdata( 'message_error', lang( 'Selected page is not found' ) );
					}
					else {						
						$page_data = $page->row_array( );
						
						$settings['last_status'] = $page_data['status'];
					
						if( isset( $_POST['force'] ) ) {
							$settings['show_hash'] = md5( $page_id . time() );
						}
						else {
							$settings['show_hash'] = md5( $page_id . $page_data['edit_date'] );
						}
					}
				}
				
				ee()->lang->loadfile( $this->basename );
				
				ee()->db->where( 'class', __CLASS__ );
				ee()->db->update( 'extensions', array( 'settings' => serialize( $settings ) ) );
			}
			
			if( $error ) {
				ee()->functions->redirect( $this->module_base );
			}
		}
	}
	