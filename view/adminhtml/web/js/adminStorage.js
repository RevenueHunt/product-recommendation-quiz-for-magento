/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */

define(['jquery', 'mage/url'], function ($, urlBuilder) {
    'use strict';

    return {
        /**
         * Perform asynchronous GET request to server.
         * @param {String} url
         * @param {Boolean} global
         * @param {String} contentType
         * @param {Object} headers
         * @returns {Deferred}
         */
        get: function (url, global, contentType, headers) {
            headers = headers || {};
            global = global === undefined ? true : global;
            contentType = contentType || 'application/json';

            return $.ajax({
                showLoader: true,
                url: url,
                type: 'GET',
                global: global,
                contentType: contentType,
                headers: headers
            });
        },

        /**
         * Perform asynchronous POST request to server.
         * @param {String} url
         * @param {String} data
         * @param {Boolean} global
         * @param {String} contentType
         * @param {Object} headers
         * @returns {Deferred}
         */
        post: function (url, data, global, contentType, headers) {
            headers = headers || {};
            global = global === undefined ? true : global;
            contentType = contentType || 'application/json';

            return $.ajax({
                showLoader: true,
                url: url,
                type: 'POST',
                data: data,
                global: global,
                contentType: contentType,
                headers: headers
            });
        },

        /**
         * Perform asynchronous PUT request to server.
         * @param {String} url
         * @param {String} data
         * @param {Boolean} global
         * @param {String} contentType
         * @param {Object} headers
         * @returns {Deferred}
         */
        put: function (url, data, global, contentType, headers) {
            var ajaxSettings = {};

            headers = headers || {};
            global = global === undefined ? true : global;
            contentType = contentType || 'application/json';
            ajaxSettings.url = url;
            ajaxSettings.type = 'PUT';
            ajaxSettings.data = data;
            ajaxSettings.global = global;
            ajaxSettings.contentType = contentType;
            ajaxSettings.headers = headers;

            return $.ajax(ajaxSettings);
        },

        /**
         * Perform asynchronous DELETE request to server.
         * @param {String} url
         * @param {Boolean} global
         * @param {String} contentType
         * @param {Object} headers
         * @returns {Deferred}
         */
        delete: function (url, global, contentType, headers) {
            headers = headers || {};
            global = global === undefined ? true : global;
            contentType = contentType || 'application/json';

            return $.ajax({
                url: url,
                type: 'DELETE',
                global: global,
                contentType: contentType,
                headers: headers
            });
        }
    };
});
