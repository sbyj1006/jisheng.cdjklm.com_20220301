define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $(".btn-dialog").data("area",["80%","80%"]);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'chelistok/index' + location.search,
                    add_url: 'chelistok/add',
                    edit_url: 'chelistok/edit',
                    del_url: 'chelistok/del',
                    multi_url: 'chelistok/multi',
                    table: 'chelistok',
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
                        {field: 'user_name', title: __('回收人员'),operate:'LIKE'},

                        {field: 'pinpai', title:  __('品牌'),operate:'LIKE'},
                        {field: 'chepainum', title:  __('车牌号'),operate:'LIKE'},
                        {field: 'ruchangbianhao', title:  __('入场编号'),operate:'LIKE'},
                        {field: 'f_name', title:  __('客户姓名'),operate:'LIKE'},
                        {field: 'f_phone', title:  __('客户电话'),operate:'LIKE'},
                        // {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image,operate:false},
                        {field: 'statue', title: __('statue'), searchList: {"7":__('statue 7'),"8":__('statue 8')}, formatter: Table.api.formatter.status},

                        // {field: 'createtime', title: __('Createtime'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'chepeijian',
                                    text: __('配件'),
                                    title: __('配件'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-angellist',
                                    extend:'data-area=\'["80%","80%"]\'',
                                    url: 'chepeijian/index',
                                    // url: function (row, column) { //row 表格接收到的数据
                                    //     return "pro_pricejd/index/lineid/" + row.id; //弹窗的对应后台控制器方法 这里是默认index方法 加id参数
                                    // },
                                    visible: function (row) {
                                        //返回true时按钮显示,返回false隐藏
                                        return true;

                                    }
                                }
                            ]
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