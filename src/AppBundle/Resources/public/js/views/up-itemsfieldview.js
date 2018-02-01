/*
 * Copyright (C) eZ Systems AS. All rights reserved.
 * For full copyright and license information view LICENSE file distributed with this source code.
 */
YUI.add('up-itemsfieldview', function (Y) {
    'use strict';

    Y.namespace('up');

    var TAG_ITEMS_SELECTOR = ".ez-view-itemsfieldview .item:not(.item-disabled) .tag-container",
        TAG_INPUTS_SELECTOR = '.ez-view-userprofilingblockconfigformview:not(.ezs-is-hidden)',
        TAG_ID_SELECTOR = 'input.tagids[type=hidden]',
        TAG_PID_SELECTOR = 'input.tagpids[type=hidden]',
        TAG_NAME_SELECTOR = 'input.tagnames[type=hidden]',
        TAG_LOCATE_SELECTOR = 'input.taglocales[type=hidden]',
        SELECTOR_ITEM_CONTENT = '.item-content',
        SELECTOR_DELETE_ITEM = '.delete-item',
        SELECTOR_ADD_ITEM = '.add-item',

        EVENTS = {};
        EVENTS[SELECTOR_ITEM_CONTENT] = {tap: '_chooseContent'};
        EVENTS[SELECTOR_DELETE_ITEM] = {tap: '_deleteItem'};
        EVENTS[SELECTOR_ADD_ITEM] = {tap: '_addItem'};

    /**
     * The Items Field view
     *
     * @namespace up
     * @class ItemsFieldView
     * @constructor
     * @extends eZS.SelectFieldView
     */
    Y.upItemsFieldView = Y.Base.create('itemsFieldView', Y.eZS.SelectFieldView, [], {
        initializer: function () {
            this._addDOMEventHandlers(EVENTS);
        },

        /**
         * Renders the Items Field view
         *
         * @method render
         * @return {Y.up.ItemsFieldView} the view itself
         */
        render: function () {
            this._appendItemOnEmptyCollection();
            this.get('container')
                .setHTML(this.template({
                    items: this.get('items')
                }));
            this.loadEzTags();

            return this;
        },

        /**
         * Validates form data
         *
         * @method validate
         * @return {Boolean}
         */
        validate: function () {
            var areItemsValid = true,
                items = [];

            this._updateTags();

            this.get('items').forEach(function (item) {
                if ( !item.tagId || !item.contentId) {
                    item.invalidTag = !item.tagId;
                    item.invalidContent = !item.contentId;
                    areItemsValid = false;
                }

                items.push(item);
            });

            if (!items.length)  {
                areItemsValid = false;
            }

            this.set('items', items);
            this.render();

            return areItemsValid;
        },

        /**
         * Appends item to items attribute if items collection is empty
         *
         * @method _appendItemOnEmptyCollection
         */
        _appendItemOnEmptyCollection: function () {
            var items = this.get('items');

            if (!items) {
                items = [];
            }

            if (!items.length) {
                items.push({ contentId: null, tagId: null });
            }

            this.set('items', items);
        },

        /**
         * Loads EzTags when items tags are ready
         *
         * @method loadEzTags
         */
        loadEzTags: function () {
            var isReadyToLoad;

            this._updateTags();
            this._loadEzTagsOnNewItems();

            isReadyToLoad = setInterval(function () {
                if (this._loadEzTagsOnNewItems()) {
                    clearInterval(isReadyToLoad);
                }
            }.bind(this), 500);
        },

        /**
         * Loads EzTags on items tag
         *
         * @method loadEzTags
         * @return {Boolean}
         */
        _loadEzTagsOnNewItems: function() {
            var selector = jQuery(TAG_ITEMS_SELECTOR).not(":has('.tagssuggest-ui')");

            if (selector) {
                jQuery(selector).EzTags({
                    autocompleteUrl: window.Routing.generate('netgen_tags_admin_autocomplete'),
                    maxTags: 1,
                    subtreeLimit: 513,
                    hideRootTag: 1,
                    sortable: false,
                    translations: {
                        selectedTags: '',
                        noSelectedTags: ''
                    }
                });

                return true;
            }

            return false;
        },

        /**
         * Updates form field value with new values
         *
         * @method _updateValues
         * @protected
         */
        _updateValues: function () {
            var items = this.get('items');

            if (!items.length) {
                return;
            }

            this.set('values', [items]);
        },

        /**
         * Updates item value with new tag values
         *
         * @method _updateTags
         * @protected
         */
        _updateTags: function () {
            var items = [],
                tagContainer,
                tagId;

            this.get('items').forEach(function (item, i) {
                tagContainer = jQuery(TAG_INPUTS_SELECTOR + ' ' + TAG_ITEMS_SELECTOR).eq(i);
                tagId = tagContainer.find(TAG_ID_SELECTOR).val();

                if (tagId) {
                    item.tagId = tagId;
                    item.tagPid = tagContainer.find(TAG_PID_SELECTOR).val();
                    item.tagName = tagContainer.find(TAG_NAME_SELECTOR).val();
                    item.tagLocate = tagContainer.find(TAG_LOCATE_SELECTOR).val();
                }
                items.push(item);
            });

            this.set('items', items);
        },

        /**
         * Adds new item to items
         *
         * @method _addItem
         * @protected
         * @param event {Object} event facade
         */
        _addItem: function(event) {
            var items;

            event.preventDefault();
            this._updateTags();

            items = this.get('items');
            items.push({ contentId: null, tagId: null });
            this.set('items', items);
            this.render();
        },

        /**
         * Removes item from items
         *
         * @method _deleteItem
         * @protected
         * @param event {Object} event facade
         */
        _deleteItem: function(event) {
            var itemIndex,
                items;

            event.preventDefault();
            this._updateTags();

            itemIndex = parseInt(event.currentTarget.getDOMNode().getAttribute('data-item-index'), 10);
            items = this.get('items').filter( function (item, i) {
                return i !== itemIndex;
            });

            this.set('items', items);
            this._updateValues();
            this.render();
        },

        /**
         * Updates item with selected content info
         *
         * @method _updateContentDescription
         * @protected
         * @param event {Object} event facade
         */
        _updateContentDescription: function (event) {
            var contentInfo,
                lastChangedItemIndex,
                items = [];

            this._updateTags();

            contentInfo = event.selection.contentInfo.toJSON();
            lastChangedItemIndex = parseInt(this.get('lastChangedItemIndex'), 10);

            this.get('items').forEach( function (item, i) {
                if (i === lastChangedItemIndex) {
                    item.contentId = contentInfo.contentId;
                    item.contentName = contentInfo.name;
                }
                items.push(item);
            });

            this.set('items', items);
            this._updateValues();
            this.render();
        },

        /**
         * Triggers the UDW to choose the content to embed.
         *
         * @method _chooseContent
         * @protected
         * @param event {Object} event facade
         */
        _chooseContent: function (event) {
            var itemIndex = event.currentTarget.getDOMNode().getAttribute('data-item-index');

            event.preventDefault();

            this.set('lastChangedItemIndex', itemIndex);
            this.fire('contentDiscover', {
                config: {contentDiscoveredHandler: this._updateContentDescription.bind(this)}
            });
        },

        _updateSelectedOption: function () {}
    }, {
        ATTRS: {

            /**
             * Index of last changed item
             *
             * @attribute lastChangedItemIndex
             * @type Integer
             */
            lastChangedItemIndex: {},

            /**
             * Items Collection
             *
             * @attribute type
             * @type Array
             */
            items: {
                value: []
            },

            /**
             * The type of the field
             *
             * @attribute type
             * @type String
             * @default 'items'
             */
            type: {
                value: 'items'
            }
        }
    });
});
