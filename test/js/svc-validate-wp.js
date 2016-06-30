/*jslint white: true */
/**
 * Service: Validate wordpress pages
 *
 */
angular.module('validate-wp', []);
angular.module('validate-wp').service('WPValidateSvc', ['$http', function ($http) {
	'use strict';

	/**
	 * Validate if the page is WordPress
	 *
	 */
	this.validate_home = function (url) {
		return $http({
			method: 'GET',
			url: url,
			headers: {
				'X-UA-Compatible': 'WordPress Post Simulator'
			}
		})

		.then(
			function (res) {
				if (-1 !== res.data.indexOf('wp-content')) {
					return {stat: 'Site is OK.'};
				} else {
					return {stat: 'Can\'t find "wp-content".'};
				}
			},

			function (res) {
				return {stat: res.message};
			}
		);
	};

	/**
	 * Validate if the comment form in the page
	 *
	 */
	this.validate_page = function (url) {
		return $http({
			method: 'GET',
			url: url,
			headers: {
				'X-UA-Compatible': 'WordPress Post Simulator'
			}
		})

		// data       – {string|Object} The response body.
		// status     – {number} HTTP status code of the response.
		// headers    – {function([headerName])} Header getter function.
		// config     – {Object} The configuration object used for the request.
		// statusText – {string} HTTP status text of the response.
		.then(
			function (res) {
				// Extract canonical URL
				var id = 0,
				    regexp = /<link[^>]+?rel=(['"]?)canonical\1.+/i,
				    match = res.data.match(regexp);
				if (match && match.length) {
					url = match[0].replace(/.*href=(['"]?)(.+?)\1.+/, '$2');
				}

				// Extract ID of the post
				regexp = /<input[^>]+?comment_post_ID.+?>/i;
				match = res.data.match(regexp);
				if (match && match.length) {
					// if found then get post comment ID
					id = match[0].replace(/[\D]/g, '');
				}
				return {
					url: url,
					id: id,
					stat: id ? 'Comment form is OK.' : 'Can\'t find comment form.'
				};
			},

			function (res) {
				return {
					url: url,
					id: 0,
					stat: res.message
				};
			}
		);
	};

	/**
	 * Get url to the forum
	 *
	 */
	this.get_forum = function (url) {
		return $http({
			method: 'GET',
			url: url,
			headers: {
				'X-UA-Compatible': 'WordPress Post Simulator'
			}
		})
		.then(
			function (res) {
				// Extract a link to forum
				var regexp = /<a\s+?class=["']bbp-forum-title["']\s+?href=["']([^"']+?)["']/i,
				    match = res.data.match(regexp);
				return {
					url: match ? (match[1] || url) : url
				};
			},
			function (res) {
				return {
					url: url
				};
			}
		);
	};
}]);