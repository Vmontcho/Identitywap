<?php
/**
 * The Publicize Rest Controller class.
 * Registers the REST routes for Publicize.
 *
 * @package automattic/jetpack-publicize
 */

namespace Automattic\Jetpack\Publicize;

use Automattic\Jetpack\Connection\Client;
use Automattic\Jetpack\Connection\Rest_Authentication;
use Jetpack_Options;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Registers the REST routes for Search.
 */
class REST_Controller {
	/**
	 * Whether it's run on WPCOM.
	 *
	 * @var bool
	 */
	protected $is_wpcom;

	/**
	 * Social Product Slugs
	 *
	 * @var string
	 */
	const JETPACK_SOCIAL_V1_YEARLY = 'jetpack_social_v1_yearly';

	const SOCIAL_SHARES_POST_META_KEY = '_publicize_shares';

	/**
	 * Constructor
	 *
	 * @param bool $is_wpcom - Whether it's run on WPCOM.
	 */
	public function __construct( $is_wpcom = false ) {
		$this->is_wpcom = $is_wpcom;
	}

	/**
	 * Registers the REST routes for Search.
	 *
	 * @access public
	 * @static
	 */
	public function register_rest_routes() {
		register_rest_route(
			'jetpack/v4',
			'/publicize/connection-test-results',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_publicize_connection_test_results' ),
				'permission_callback' => array( $this, 'require_author_privilege_callback' ),
			)
		);

		register_rest_route(
			'jetpack/v4',
			'/publicize/connections',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_publicize_connections' ),
				'permission_callback' => array( $this, 'require_author_privilege_callback' ),
			)
		);

		// Get current social product from the product's endpoint.
		register_rest_route(
			'jetpack/v4',
			'/social-product-info',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_social_product_info' ),
				'permission_callback' => array( $this, 'require_admin_privilege_callback' ),
			)
		);

		// Dismiss a notice.
		// Flagged to be removed after deprecation.
		// @deprecated 0.47.2
		register_rest_route(
			'jetpack/v4',
			'/social/dismiss-notice',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_dismissed_notices' ),
				'permission_callback' => array( $this, 'require_author_privilege_callback' ),
				'args'                => rest_get_endpoint_args_for_schema( $this->get_dismiss_notice_endpoint_schema(), WP_REST_Server::CREATABLE ),
				'schema'              => array( $this, 'get_dismiss_notice_endpoint_schema' ),
			)
		);

		register_rest_route(
			'jetpack/v4',
			'/publicize/(?P<postId>\d+)',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'share_post' ),
				'permission_callback' => array( $this, 'require_author_privilege_callback' ),
				'args'                => array(
					'message'             => array(
						'description'       => __( 'The message to share.', 'jetpack-publicize-pkg' ),
						'type'              => 'string',
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_string( $param );
						},
						'sanitize_callback' => 'sanitize_textarea_field',
					),
					'skipped_connections' => array(
						'description'       => __( 'Array of external connection IDs to skip sharing.', 'jetpack-publicize-pkg' ),
						'type'              => 'array',
						'required'          => false,
						'validate_callback' => function ( $param ) {
							return is_array( $param );
						},
						'sanitize_callback' => function ( $param ) {
							return array_map( 'absint', $param );
						},
					),
				),
			)
		);

		// Create a Jetpack Social connection.
		register_rest_route(
			'jetpack/v4',
			'/social/connections',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_publicize_connection' ),
				'permission_callback' => array( $this, 'require_author_privilege_callback' ),
				'schema'              => array( $this, 'get_jetpack_social_connections_schema' ),
			)
		);

		// Update a Jetpack Social connection.
		register_rest_route(
			'jetpack/v4',
			'/social/connections/(?P<connection_id>\d+)',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_publicize_connection' ),
				'permission_callback' => array( $this, 'update_connection_permission_check' ),
				'schema'              => array( $this, 'get_jetpack_social_connections_update_schema' ),
			)
		);

		// Delete a Jetpack Social connection.
		register_rest_route(
			'jetpack/v4',
			'/social/connections/(?P<connection_id>\d+)',
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_publicize_connection' ),
				'permission_callback' => array( $this, 'manage_connection_permission_check' ),
			)
		);

		register_rest_route(
			'jetpack/v4',
			'/social/sync-shares/post/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_post_shares' ),
					'permission_callback' => array( Rest_Authentication::class, 'is_signed_with_blog_token' ),
					'args'                => array(
						'meta' => array(
							'type'       => 'object',
							'required'   => true,
							'properties' => array(
								'_publicize_shares' => array(
									'type'     => 'array',
									'required' => true,
								),
							),
						),
					),
				),
			)
		);

		register_rest_route(
			'jetpack/v4',
			'/social/share-status/(?P<post_id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_post_share_status' ),
					'permission_callback' => array( $this, 'require_author_privilege_callback' ),
				),
			)
		);
	}

	/**
	 * Manage connection permission check
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 *
	 * @return bool True if the user can manage the connection, false otherwise.
	 */
	public function manage_connection_permission_check( WP_REST_Request $request ) {

		if ( current_user_can( 'edit_others_posts' ) ) {
			return true;
		}

		/**
		 * Publicize instance.
		 *
		 * @var Publicize $publicize Publicize instance.
		 */
		global $publicize;

		$connection = $publicize->get_connection_for_user( $request->get_param( 'connection_id' ) );

		$owns_connection = isset( $connection['user_id'] ) && get_current_user_id() === (int) $connection['user_id'];

		return $owns_connection;
	}

	/**
	 * Update connection permission check.
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 *
	 * @return bool True if the user can update the connection, false otherwise.
	 */
	public function update_connection_permission_check( WP_REST_Request $request ) {

		// If the user cannot manage the connection, they can't update it either.
		if ( ! $this->manage_connection_permission_check( $request ) ) {
			return false;
		}

		// If the connection is being marked/unmarked as shared.
		if ( $request->has_param( 'shared' ) ) {
			// Only editors and above can mark a connection as shared.
			return current_user_can( 'edit_others_posts' );
		}

		return $this->require_author_privilege_callback();
	}

	/**
	 * Only administrators can access the API.
	 *
	 * @return bool|WP_Error True if a blog token was used to sign the request, WP_Error otherwise.
	 */
	public function require_admin_privilege_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Only Authors can access the API.
	 *
	 * @return bool|WP_Error True if a blog token was used to sign the request, WP_Error otherwise.
	 */
	public function require_author_privilege_callback() {
		return current_user_can( 'publish_posts' );
	}

	/**
	 * Retrieves the JSON schema for creating a jetpack social connection.
	 *
	 * @return array Schema data.
	 */
	public function get_jetpack_social_connections_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'jetpack-social-connection',
			'type'       => 'object',
			'properties' => array(
				'keyring_connection_ID' => array(
					'description' => __( 'Keyring connection ID', 'jetpack-publicize-pkg' ),
					'type'        => 'integer',
					'required'    => true,
				),
				'external_user_ID'      => array(
					'description' => __( 'External User Id - in case of services like Facebook', 'jetpack-publicize-pkg' ),
					'type'        => 'string',
				),
				'shared'                => array(
					'description' => __( 'Whether the connection is shared with other users', 'jetpack-publicize-pkg' ),
					'type'        => 'boolean',
				),
			),
		);

		return rest_default_additional_properties_to_false( $schema );
	}

	/**
	 * Retrieves the JSON schema for updating a jetpack social connection.
	 *
	 * @return array Schema data.
	 */
	public function get_jetpack_social_connections_update_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'jetpack-social-connection',
			'type'       => 'object',
			'properties' => array(
				'external_user_ID' => array(
					'description' => __( 'External User Id - in case of services like Facebook', 'jetpack-publicize-pkg' ),
					'type'        => 'string',
				),
				'shared'           => array(
					'description' => __( 'Whether the connection is shared with other users', 'jetpack-publicize-pkg' ),
					'type'        => 'boolean',
				),
			),
		);

		return rest_default_additional_properties_to_false( $schema );
	}

	/**
	 * Retrieves the JSON schema for dismissing notices.
	 *
	 * @return array Schema data.
	 */
	public function get_dismiss_notice_endpoint_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'jetpack-social-dismiss-notice',
			'type'       => 'object',
			'properties' => array(
				'notice'            => array(
					'description' => __( 'Name of the notice to dismiss', 'jetpack-publicize-pkg' ),
					'type'        => 'string',
					'enum'        => array( 'instagram', 'advanced-upgrade-nudge-admin', 'advanced-upgrade-nudge-editor' ),
					'required'    => true,
				),
				'reappearance_time' => array(
					'description' => __( 'Time when the notice should reappear', 'jetpack-publicize-pkg' ),
					'type'        => 'integer',
					'default'     => 0,
				),
			),
		);

		return rest_default_additional_properties_to_false( $schema );
	}

	/**
	 * Gets the current Publicize connections, with the resolt of testing them, for the site.
	 *
	 * GET `jetpack/v4/publicize/connection-test-results`
	 */
	public function get_publicize_connection_test_results() {
		$blog_id  = $this->get_blog_id();
		$path     = sprintf( '/sites/%d/publicize/connection-test-results', absint( $blog_id ) );
		$response = Client::wpcom_json_api_request_as_user( $path, '2', array(), null, 'wpcom' );
		return rest_ensure_response( $this->make_proper_response( $response ) );
	}

	/**
	 * Gets the current Publicize connections for the site.
	 *
	 * GET `jetpack/v4/publicize/connections`
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 */
	public function get_publicize_connections( $request ) {
		$run_test_results = $request->get_param( 'test_connections' );
		$clear_cache      = $request->get_param( 'clear_cache' );

		$args = array();

		if ( ! empty( $run_test_results ) ) {
			$args['test_connections'] = true;
		}

		if ( ! empty( $clear_cache ) ) {
			$args['clear_cache'] = true;
		}

		global $publicize;
		return rest_ensure_response( $publicize->get_all_connections_for_user( $args ) );
	}

	/**
	 * Gets information about the current social product plans.
	 *
	 * @return string|WP_Error A JSON object of the current social product being if the request was successful, or a WP_Error otherwise.
	 */
	public static function get_social_product_info() {
		$request_url   = 'https://public-api.wordpress.com/rest/v1.1/products?locale=' . get_user_locale() . '&type=jetpack';
		$wpcom_request = wp_remote_get( esc_url_raw( $request_url ) );
		$response_code = wp_remote_retrieve_response_code( $wpcom_request );

		if ( 200 !== $response_code ) {
			// Something went wrong so we'll just return the response without caching.
			return new WP_Error(
				'failed_to_fetch_data',
				esc_html__( 'Unable to fetch the requested data.', 'jetpack-publicize-pkg' ),
				array(
					'status'  => $response_code,
					'request' => $wpcom_request,
				)
			);
		}

		$products = json_decode( wp_remote_retrieve_body( $wpcom_request ) );
		return array(
			'v1' => $products->{self::JETPACK_SOCIAL_V1_YEARLY},
		);
	}

	/**
	 * Dismisses a notice to prevent it from appearing again.
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 * @return WP_REST_Response|WP_Error True if the request was successful, or a WP_Error otherwise.
	 */
	public function update_dismissed_notices( $request ) {
		$notice            = $request->get_param( 'notice' );
		$reappearance_time = $request->get_param( 'reappearance_time' );
		$dismissed_notices = get_option( Publicize::OPTION_JETPACK_SOCIAL_DISMISSED_NOTICES );

		if ( ! is_array( $dismissed_notices ) ) {
			$dismissed_notices = array();
		}

		if ( array_key_exists( $notice, $dismissed_notices ) && $dismissed_notices[ $notice ] === $reappearance_time ) {
			return rest_ensure_response( array( 'success' => true ) );
		}

		$dismissed_notices[ $notice ] = $reappearance_time;
		update_option( Publicize::OPTION_JETPACK_SOCIAL_DISMISSED_NOTICES, $dismissed_notices );

		return rest_ensure_response( array( 'success' => true ) );
	}

	/**
	 * Calls the WPCOM endpoint to reshare the post.
	 *
	 * POST jetpack/v4/publicize/(?P<postId>\d+)
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 */
	public function share_post( $request ) {
		$post_id             = $request->get_param( 'postId' );
		$message             = trim( $request->get_param( 'message' ) );
		$skip_connection_ids = $request->get_param( 'skipped_connections' );
		$async               = (bool) $request->get_param( 'async' );

		/*
		 * Publicize endpoint on WPCOM:
		 * [POST] wpcom/v2/sites/{$siteId}/posts/{$postId}/publicize
		 * body:
		 *   - message: string
		 *   - skipped_connections: array of connection ids to skip
		 */
		$url = sprintf(
			'/sites/%d/posts/%d/publicize',
			$this->get_blog_id(),
			$post_id
		);

		$response = Client::wpcom_json_api_request_as_user(
			$url,
			'v2',
			array(
				'method' => 'POST',
			),
			array(
				'message'             => $message,
				'skipped_connections' => $skip_connection_ids,
				'async'               => $async,
			)
		);

		return rest_ensure_response( $this->make_proper_response( $response ) );
	}

	/**
	 * Forward remote response to client with error handling.
	 *
	 * @param array|WP_Error $response - Response from WPCOM.
	 */
	public function make_proper_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body        = json_decode( wp_remote_retrieve_body( $response ), true );
		$status_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status_code ) {
			return $body;
		}

		return new WP_Error(
			isset( $body['error'] ) ? 'remote-error-' . $body['error'] : 'remote-error',
			isset( $body['message'] ) ? $body['message'] : 'unknown remote error',
			array( 'status' => $status_code )
		);
	}

	/**
	 * Get blog id
	 */
	protected function get_blog_id() {
		return $this->is_wpcom ? get_current_blog_id() : Jetpack_Options::get_option( 'id' );
	}

	/**
	 * Calls the WPCOM endpoint to update the publicize connection.
	 *
	 * POST jetpack/v4/social/connections/{connection_id}
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 */
	public function update_publicize_connection( $request ) {
		$external_user_id = $request->get_param( 'external_user_ID' );
		$shared           = $request->get_param( 'shared' );
		$blog_id          = $this->get_blog_id();
		$connection_id    = $request->get_param( 'connection_id' );

		$path = sprintf(
			'/sites/%d/jetpack-social-connections/%d',
			$blog_id,
			$connection_id
		);

		$body = array();

		if ( ! empty( $external_user_id ) ) {
			$body['external_user_ID'] = $external_user_id;
		}

		if ( $shared || ( false === $shared ) ) {
			$body['shared'] = $shared;
		}

		$response = Client::wpcom_json_api_request_as_user(
			$path,
			'2',
			array(
				'method'  => 'POST',
				'timeout' => 120,
			),
			$body,
			'wpcom'
		);

		$response = $this->make_proper_response( $response );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		global $publicize;
		return rest_ensure_response( $publicize->get_connection_for_user( (int) $connection_id ) );
	}

	/**
	 * Calls the WPCOM endpoint to delete the publicize connection.
	 *
	 * DELETE jetpack/v4/social/connections/{connection_id}
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 */
	public function delete_publicize_connection( $request ) {
		$connection_id = $request->get_param( 'connection_id' );
		$blog_id       = $this->get_blog_id();

		$path = sprintf(
			'/sites/%d/jetpack-social-connections/%d',
			$blog_id,
			$connection_id
		);

		$response = Client::wpcom_json_api_request_as_user( $path, '2', array( 'method' => 'DELETE' ), null, 'wpcom' );
		return rest_ensure_response( $this->make_proper_response( $response ) );
	}

	/**
	 * Create a publicize connection
	 *
	 * @param WP_REST_Request $request The request object, which includes the parameters.
	 * @return WP_REST_Response|WP_Error True if the request was successful, or a WP_Error otherwise.
	 */
	public function create_publicize_connection( $request ) {
		$keyring_connection_id = $request->get_param( 'keyring_connection_ID' );
		$shared                = $request->get_param( 'shared' );
		$external_user_id      = $request->get_param( 'external_user_ID' );
		$blog_id               = $this->get_blog_id();

		$path = sprintf(
			'/sites/%d/jetpack-social-connections/new',
			$blog_id
		);

		$body = array(
			'keyring_connection_ID' => $keyring_connection_id,
			'shared'                => $shared,
		);

		if ( ! empty( $external_user_id ) ) {
			$body['external_user_ID'] = $external_user_id;
		}

		$response = Client::wpcom_json_api_request_as_user(
			$path,
			'2',
			array(
				'method'  => 'POST',
				'timeout' => 120,
			),
			$body,
			'wpcom'
		);

		$response = $this->make_proper_response( $response );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( isset( $response['ID'] ) ) {
			global $publicize;
			return rest_ensure_response( $publicize->get_connection_for_user( (int) $response['ID'] ) );
		}

		return new WP_Error(
			'could_not_create_connection',
			__( 'Something went wrong while creating a connection.', 'jetpack-publicize-pkg' )
		);
	}

	/**
	 * Update the post with information about shares.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function update_post_shares( $request ) {
		$request_body = $request->get_json_params();

		$post_id   = $request->get_param( 'id' );
		$post_meta = $request_body['meta'];
		$post      = get_post( $post_id );

		if ( $post && 'publish' === $post->post_status && isset( $post_meta[ self::SOCIAL_SHARES_POST_META_KEY ] ) ) {
			update_post_meta( $post_id, self::SOCIAL_SHARES_POST_META_KEY, $post_meta[ self::SOCIAL_SHARES_POST_META_KEY ] );
			$urls = array();
			foreach ( $post_meta[ self::SOCIAL_SHARES_POST_META_KEY ] as $share ) {
				if ( isset( $share['status'] ) && 'success' === $share['status'] ) {
					$urls[] = array(
						'url'     => $share['message'],
						'service' => $share['service'],
					);
				}
			}
			/**
			 * Fires after Publicize Shares post meta has been saved.
			 *
			 * @param array $urls {
			 *     An array of social media shares.
			 *     @type array $url URL to the social media post.
			 *     @type string $service Social media service shared to.
			 * }
			 */
			do_action( 'jetpack_publicize_share_urls_saved', $urls );
			return rest_ensure_response( new WP_REST_Response() );
		}

		return new WP_Error(
			'rest_cannot_edit',
			__( 'Failed to update the post meta', 'jetpack-publicize-pkg' ),
			array( 'status' => 500 )
		);
	}

	/**
	 * Gets the share status for a post.
	 *
	 * GET `jetpack/v4/social/share-status/<post_id>`
	 *
	 * @param WP_REST_Request $request The request object.
	 */
	public function get_post_share_status( WP_REST_Request $request ) {
		$post_id = $request->get_param( 'post_id' );

		$shares = get_post_meta( $post_id, self::SOCIAL_SHARES_POST_META_KEY, true );

		// If the data is not an array, it means that sharing is not done yet.
		$done = is_array( $shares );

		if ( $done ) {
			// The site could have multiple admins, editors and authors connected. Load shares information that only the current user has access to.
			global $publicize;
			$connection_ids = array_map(
				function ( $connection ) {
					if ( isset( $connection['connection_id'] ) ) {
						return (int) $connection['connection_id'];
					}
					return 0;
				},
				$publicize->get_all_connections_for_user()
			);
			$shares         = array_values(
				array_filter(
					$shares,
					function ( $share ) use ( $connection_ids ) {
						return in_array( (int) $share['connection_id'], $connection_ids, true );
					}
				)
			);
		}

		return rest_ensure_response(
			array(
				'shares' => $done ? $shares : array(),
				'done'   => $done,
			)
		);
	}
}
