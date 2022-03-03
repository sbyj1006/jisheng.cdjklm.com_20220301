define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'backend/xshop/libs/Hook'], function ($, undefined, Backend, Table, Form, Hook) {

    var Controller = {
        index: function() {
            Hook.init(function() {
                Controller.bootIndex()
            })
        },
        bootIndex: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/service_tag/index' + location.search,
                    add_url: 'xshop/service_tag/add',
                    edit_url: 'xshop/service_tag/edit',
                    del_url: 'xshop/service_tag/del',
                    multi_url: 'xshop/service_tag/multi',
                    table: 'xshop_service_tag',
                }
            });

            var table = $("#table");

            var config = {
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'description', title: __('Description')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            }
            config = Hook.listen('table_config', config)
            // 初始化表格
            table.bootstrapTable(config);

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table', function() {
                var payload = {
                    e: this,
                    table: table
                }
                Hook.listen('table_event', payload)
            })
            Hook.listen('document', table)
        },
        add: function () {
            Controller.api.bindevent();
            Hook.listen('form')
        },
        edit: function () {
            Controller.api.bindevent();
            Hook.listen('form')
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});