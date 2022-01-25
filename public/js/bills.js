jQuery(document).ready(function ($) {
    'use strict';

    const orderIdUrl = $('[id$=form_orderId]').data('autocomplete-url');
    const providerUrl = $('[id$=form_provider]').data('autocomplete-url');
    const statusUrl = $('[id$=form_status]').data('autocomplete-url');
    const typeUrl = $('[id$=form_type]').data('autocomplete-url');
    const parentIdUrl = $('[id$=form_parentId]').data('autocomplete-url');

    $('[id$=form_orderId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 1,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: orderIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_provider]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 0,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: providerUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_status]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 0,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: statusUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_type]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 0,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: typeUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });

    $('[id$=form_parentId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 0,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: parentIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });
});
