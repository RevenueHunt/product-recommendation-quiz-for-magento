/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'jquery',
    'uiComponent',
    'Revenuehunt_ProductQuiz/js/adminStorage',
    'mage/url'
], function(ko, $, Component, storage, url) {
    'use strict';

    return Component.extend({
        defaults: {
            hashId: ko.observable(''),
            secret: ko.observable(''),
            iframeSrc: ko.observable(''),
            template: 'Revenuehunt_ProductQuiz/productQuiz',
        },
        initialize: function () {
            this._super()
                .initObservable();

            this.getIframe();

            return this;
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super();
            this.observe(['hashId','secret']);

            return this;
        },
        create: function() {
            let self = this
            let createUrl =self.ajax.create

            $.ajax({
                type: 'GET',
                url: createUrl,
                dataType: 'json',
                contentType: "application/json",
                context: $('body')
            }).done(function (response) {
                if(response.error === false)  {
                    self.hashId = response.data.hashId
                    self.secret = response.data.secret
                    location.reload()
                } else if (response.error === true) {
                    location.reload()
                }
            }).fail(function(response) {
                console.log('ERROR BLOCK');
                console.log(response);
            });

        },
        getIframe: function() {
            let self = this
            let createUrl =self.ajax.auth
            $.ajax({
                type: 'GET',
                url: createUrl,
                data: {
                    'form_key': FORM_KEY,
                },
                dataType: 'text',
                context: $('body')
            }).always(function (data) {
                console.log(data)
                self.iframeSrc(data)
            });

            return '';

        }
    });
});
