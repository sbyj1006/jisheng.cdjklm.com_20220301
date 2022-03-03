define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'article/index' + location.search,
                    add_url: 'article/add',
                    edit_url: 'article/edit',
                    del_url: 'article/del',
                    multi_url: 'article/multi',
                    table: 'article',
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
                commonSearch: true,
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'category.name', title: __('分类名'), formatter: Table.api.formatter.search,operate:'LIKE'},
                        // {field: 'category.name', title: __('分类名'), searchList: $.getJSON("Article/nav")},
                        // {field: 'title', title: __('Title')},
                        {field: 'title', title:  __('Title'),operate:'LIKE'},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'recommend', title: __('推荐'), searchList: {"0":__('Recommend 0'),"1":__('Recommend 1')}, formatter: Table.api.formatter.normal},
                        {field: 'hot', title: __('热门'), searchList: {"0":__('Hot 0'),"1":__('Hot 1')}, formatter: Table.api.formatter.normal},
                        {field: 'sort', title: __('Sort'),operate:false},
                        {field: 'addtime', title:  __('日期'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'createtime', title: __('Createtime'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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