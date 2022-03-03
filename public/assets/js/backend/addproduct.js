define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $(".btn-dialog").data("area",["80%","80%"]);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url:  'addproduct/index?ids='+id,
                    add_url: 'addproduct/add?ids='+ids,
                    edit_url: '',
                    del_url: '',
                    multi_url: 'addproduct/multi',
                    table: 'addproduct',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                //禁用默认搜索
                search: false,
                //启用普通表单搜索
                commonSearch: false,
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'pinpai', title:  __('pinpai')},
                        {field: 'chexi', title:  __('chexi')},
                        {field: 'ruchangbianhao', title:  __('ruchangbianhao')},
                        {field: 'itemname', title:  __('itemname'),operate:'LIKE'},
                        {field: 'rukunum', title:  __('rukunum'),operate:'LIKE'},
                        {field: 'itemnum', title:  __('itemnum'),},
                        {field: 'statue', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')},formatter: Table.api.formatter.normal},

                        // {field: 'createtime', title:  __('日期'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,

                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});