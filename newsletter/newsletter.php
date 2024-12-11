<?php
    set_time_limit(5000);

	if ( php_sapi_name() !== 'cli' ) {
		die("Meant to be run from command line");
	}

	function find_wordpress_base_path() {
		$dir = dirname(__FILE__);
		do {
			if( file_exists($dir."/wp-config.php") ) {
				return $dir;
			}
		} while( $dir = realpath("$dir/..") );
		return null;
	}

	define( 'BASE_PATH', find_wordpress_base_path()."/" );
	define( 'WP_USE_THEMES', false );

	global $wpdb;
	require(BASE_PATH . 'wp-load.php');
	use DrewM\MailChimp\MailChimp;

	function create_mailchimp_campaign($opts) {
		$messages = array();

		# Connect to mailchimp
		$MailChimp = new \DrewM\MailChimp\MailChimp(MAILCHIMP_API_KEY);
		if ($opts['groups_ids']) {
			$conditions[] = array(
				'condition_type'	=> 'Interests',
				'op'				=> 'interestcontains',
				'field'				=> 'interests-'.$opts['category_id'],
				'value'				=> $opts['groups_ids'],
			);

			$recipients_options = array(
				'list_id'		=> $opts['list_id'],
				'segment_opts' 	=> array(
					'conditions' => $conditions
				),
			);
		} else {
			$recipients_options = array(
				'list_id'=> $opts['list_id']
			);
		}

		# Create campaign
		$campaign_parameters = array(
			'recipients'	=> $recipients_options,
			'type' 			=> 'regular',
			'settings' 		=> array(
				'title' 		=> $opts['title'],
				'subject_line' 	=> $opts['subject'],
				'reply_to' 		=> $opts['from_email'],
				'from_name' 	=> $opts['from_name'],
				'authenticate' 	=> $opts['authenticate'],
				'auto_footer' 	=> $opts['auto_footer']
			),
			'tracking' 		=> $opts['tracking']
		);

		# Time
		$init_time = microtime(TRUE);

		$create_campaign_result = $MailChimp->post('campaigns', $campaign_parameters, 60);

		# Time
		$create_campaign_time = microtime(TRUE);
		$execution_time = $create_campaign_time - $init_time;
		echo "Crear campaña ".number_format($execution_time, 3)." segundos.\n";

		# Add content if campaign was created
		if ($create_campaign_result) {
			$campaign_id = $create_campaign_result['id'];
			$content = $opts['content'];

			# Assign content to campaign
			$set_content = $MailChimp->put('campaigns/'.$campaign_id.'/content', $content, 60);

			# Time
			$set_template_time = microtime(TRUE);
			$execution_time = $set_template_time - $create_campaign_time;
			echo "Setear contenido ".number_format($execution_time, 3)." segundos.\n";

			if ($MailChimp->success()) {
				$messages['success']['content'] = array(
					'message' 		=> 'Content Updated',
					'last_response'	=> $MailChimp->getLastResponse(),
				);

				# Send campaign
				$send_campaing = $MailChimp->post('campaigns/'.$campaign_id.'/actions/send', [], 60);

				# Time
				$send_campaing_time = microtime(TRUE);
				$execution_time = $send_campaing_time - $set_template_time;
				echo "Enviar campaña ".number_format($execution_time, 3)." segundos.\n";

				if ($MailChimp->success()) {
					$messages['success']['sent'] = array(
						'message' 		=> 'Campaign Sent',
						'last_response'	=> $MailChimp->getLastResponse(),
					);
				} else {
					$messages['error']['sent'] = array(
						'message' 		=> 'Campaing Not Sent',
						'last_error'	=> $MailChimp->getLastError(),
						'last_response'	=> $MailChimp->getLastResponse(),
					);
				}
			} else {
				$messages['error']['content'] = array(
					'message' 		=> 'Content Not Updated',
					'last_error'	=> $MailChimp->getLastError(),
					'last_response'	=> $MailChimp->getLastResponse(),
				);
			}
		}

		# Time
		$final_time = microtime(TRUE);
		$execution_time = $final_time - $init_time;
		echo "Proceso campaña total ".number_format($execution_time, 3)." segundos.\n";

		return $messages;
	}

	function get_template_from_url($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($response === false) {
			$error = curl_error($curl);
			return FALSE;
		} else {
			return $response;
		}
	}

	#Init
	if (isset($argv[1]) && $argv[1]) {
		$post_id = $argv[1];
		$cron_table = $wpdb->prefix . 'newsletter_cronjob';
		$query = "SELECT * FROM $cron_table WHERE post_id = '$post_id' LIMIT 1 ;";
		$cron = $wpdb->get_row($query);

		if ($cron) {
			$post_fields = get_fields($cron->post_id);

			$subject = $post_fields['meta_newsletter_subject'];
			$permalink = get_permalink( $post_id );
			// $permalink = 'https://boletines.produ.com/DiarioNew2.php'; //	only for tests

			# Time
			$init_time = microtime(TRUE);

			$template = get_template_from_url($permalink);

			# Time
			$get_template_time = microtime(TRUE);
			$execution_time = $get_template_time - $init_time;
			echo "Obtener Template ".number_format($execution_time, 3)." segundos.\n";

			$newsletter_type_id = $post_fields['meta_newsletter_type'];
			$newsletter_type_id = (is_object($newsletter_type_id))?$newsletter_type_id->term_id:$newsletter_type_id;
			$newsletter_type = get_term_by('id', $newsletter_type_id, 'newsletter_type');
			$type_fields = get_fields('term_'.$newsletter_type_id);

			$custom_newsletter = FALSE;
			if ($type_fields['meta_newsletter_template']->slug === 'custom') {
				$custom_newsletter =TRUE;
			}

			$title = 'Default|'.$post_fields['meta_newsletter_datetime'];
			if ($newsletter_type && !is_wp_error($newsletter_type) ) {
				$title = $newsletter_type->name.'|'.$post_fields['meta_newsletter_datetime'];
			}

			$mailchimp_lists = $custom_newsletter?$post_fields['meta_newsletter_mailchimp_lists']:$type_fields['meta_newsletter_mailchimp_lists'];
			if ( $mailchimp_lists && $template ) {
				foreach ($mailchimp_lists as $mailchimp_list) {
					$mailchimp_list_opts = get_field('meta_mailchimplist_campaign_defaults_group', $mailchimp_list['list']);

					# The ids that come in $mailchimp_list are those of WP, you must obtain the mailchimp references
					$wp_mailchimp_list = $mailchimp_list['list'];
					$wp_mailchimp_category = $mailchimp_list['category'];
					$wp_mailchimp_groups = $mailchimp_list['groups'];

					# Mailchimp list data
					$data_list = get_fields($wp_mailchimp_list);
					$list_id = $data_list['meta_mailchimplist_id'];

					# Mailchimp category data
					$data_category = get_fields('term_'.$wp_mailchimp_category);
					$category_id = $data_category?$data_category['meta_mailchimp_category_id']:'';

					# Mailchimp group data
					$groups_ids = array();
					if ($wp_mailchimp_groups) {
						foreach ($wp_mailchimp_groups as $wp_mailchimp_group) {
							$data_groups = get_fields('term_'.$wp_mailchimp_group);
							# Array with Mailchimp group ids
							$groups_ids[] =  $data_groups['meta_mailchimp_group_id'];
						}
					}

					$sender = 'PRODU';
					$email_from = $mailchimp_list_opts['from_email'] ?? 'newsletters@produ.com';
					if (isset($mailchimp_list_opts['from_name']) && !empty($mailchimp_list_opts['from_name'])) {
						$sender = $mailchimp_list_opts['from_name'];
					}

					if ($custom_newsletter) {
						$sender = $post_fields['meta_newsletter_sender'];
					}

					if (isset($type_fields['meta_newsletter_sender']) && $type_fields['meta_newsletter_sender']) {
						$sender = trim($type_fields['meta_newsletter_sender']);
					}

					if (isset($type_fields['meta_newsletter_email_from']) && $type_fields['meta_newsletter_email_from']) {
						$email_from = trim($type_fields['meta_newsletter_email_from']);
					}

					$options = array(
						'subject'		=> $subject,
						'from_email'	=> $email_from,
						'from_name'		=> $sender,
						'tracking'		=> array(
							'opens' 		=> TRUE,
							'html_clicks' 	=> TRUE,
							'text_clicks' 	=> FALSE
						),
						'authenticate' 	=> TRUE,
						'title'			=> $title,
						'auto_footer'	=> FALSE,
						'generate_text'	=> TRUE,
						'content' 		=> array(
							'html' => $template
						),
						'list_id'		=> $list_id,
						'category_id' 	=> $category_id,
						'groups_ids'	=> $groups_ids
					);

					# Call the mailchimp api and create the newsletter sending
					$mailchimp_campaign_result = create_mailchimp_campaign($options);

					if (isset($mailchimp_campaign_result['success']['sent'])) {
						# Delete cron from crones table
						$where = array('post_id' => $post_id);
						$wpdb->delete($cron_table, $where);

						# Deactivate newsletter, prevents it from being generated again by error
						update_field('meta_newsletter_active', FALSE, $post_id);

						# Mark the newsletter as sent
						update_post_meta($post_id, 'sent', 1);
					}

					print_r($mailchimp_campaign_result);

					# Time
					$final_time = microtime(TRUE);
					$execution_time = $final_time - $init_time;
					echo "Proceso script total ".number_format($execution_time, 3)." segundos.\n";
				}
			} else {
				if ($template === FALSE) {
					echo "No se pudo obtener el template por cURL.";
				}
			}
		}
	}