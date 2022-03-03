define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'carseries/index?ids='+id,
                    add_url: 'carseries/add/ids/'+Config.ids,
                    edit_url: 'carseries/edit',
                    del_url: 'carseries/del',
                    multi_url: 'carseries/multi',
                    dragsort_url: 'carseries/weigh',
                    table: 'carseries',
                }
            });

            var table = $("#table");
            var tableOptions = {
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'sfirst_letter',
                pagination: false,
                //禁用默认搜索
                search: false,
                commonSearch: true,
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'sname', title: __('Name'), align: 'left',operate:'LIKE'},
                        {field: 'scode', title: __('编码'), align: 'left'},

                        {field: 'sfirst_letter', title: __('首字母')},
                        // {field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'carmodel',
                                    text: __('车型'),
                                    title: __('车型'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-angellist',
                                    url: 'carmodel/index',
                                    extend:'data-area=\'["90%","90%"]\'',
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
            };
            // 初始化表格
            table.bootstrapTable(tableOptions);

            // 为表格绑定事件
            Table.api.bindevent(table);

            //绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // var options = table.bootstrapTable(tableOptions);
                var typeStr = $(this).attr("href").replace('#', '');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    // params.filter = JSON.stringify({type: typeStr});
                    params.type = typeStr;

                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;

            });

            //必须默认触发shown.bs.tab事件
            // $('ul.nav-tabs li.active a[data-toggle="tab"]').trigger("shown.bs.tab");

        },
        add: function () {
            Controller.api.bindevent();
            setTimeout(function () {
                $("#c-type").trigger("change");
            }, 100);
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                $(document).on("change", "#c-type", function () {
                    $("#c-pid option[data-type='all']").prop("selected", true);
                    $("#c-pid option").removeClass("hide");
                    $("#c-pid option[data-type!='" + $(this).val() + "'][data-type!='all']").addClass("hide");
                    $("#c-pid").data("selectpicker") && $("#c-pid").selectpicker("refresh");
                    // console.log("#c-pid option[data-type!='" + $(this).val() + "'][data-type!='all']");
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});