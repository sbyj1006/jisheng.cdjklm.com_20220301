define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url:  'xshop/category_qp/index?ids='+id,
                    add_url: 'xshop/category_qp/add/lineid/'+Config.lineid,
                    edit_url: 'xshop/category_qp/edit',
                    del_url: 'xshop/category_qp/del',
                    multi_url: 'xshop/category_qp/multi',
                    table: 'xshop/category_qp',
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
                        {field: 'id', title: __('Id'),operate:false},
                        // {field: 'category.name', title: __('分类名'), formatter: Table.api.formatter.search,operate:'LIKE'},
                        // {field: 'category.name', title: __('分类名'), searchList: $.getJSON("Article/nav")},
                        // {field: 'title', title: __('Title')},
                        {field: 'title', title:  __('Title'),operate:false},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        {field: 'sort', title: __('排序'),operate:false},
                        {field: 'create_time', title:  __('日期'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'createtime', title: __('Createtime'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate
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