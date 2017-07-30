let SearchForm = Backbone.Model.extend({
    defaults: {
        'term': '',
        'type': 'keywords',
        'category': 'All',
        'withVariants': 'no',
        'loading': false,
        'error': false,
        'errorMessage': null,
        'providerConfigured': false
    },

    /**
     * Submit the form the form and trigger the loading animation.
     *
     * @since 0.9
     * @public
     */
    submit() {
        this.set({
            'loading': true,
            'error': false,
            'errorMessage': null,
        });

        this.trigger('aff:amazon-import:search:search-form:submit', this);
    },

    /**
     * Finish the submit and stop the loading animation.
     *
     * @since 0.9
     * @public
     */
    done() {
        this.set('loading', false);

        this.trigger('aff:amazon-import:search:search-form:done', this);
    },

    /**
     * Show a submit error and stop the loading animation.
     *
     * @since 0.9
     * @param {string|null} message
     * @public
     */
    error(message = null) {
        this.set({
            'loading': false,
            'error': true,
            'errorMessage': message,
        });

        this.trigger('aff:amazon-import:search:search-form:error', this);
    }
});

export default SearchForm;