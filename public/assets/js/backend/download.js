define(['jquery', 'bootstrap', 'backend', 'form', 'table'], function ($, undefined, Backend, Form, Table) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'download/index',
                    add_url: 'download/add',
                    edit_url: 'download/edit',
                    del_url: 'download/del',
                    multi_url: 'download/multi',
                    table: 'download'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'number', title: __('编号')},
                        {field: 'wj_name', title: __('文件名称')},
                        {field: 'admin_id', title: __('Admin_id'), visible: false, addClass: "selectpage", extend: "data-source='auth/admin/index' data-field='nickname'"},
                        {field: 'user_id', title: __('User_id'), visible: false, addClass: "selectpage", extend: "data-source='user/user/index' data-field='nickname'"},
                        {field: 'url', title: __('Preview'), formatter: Controller.api.formatter.thumb, operate: false},
                        // {field: 'url', title: __('Url'), formatter: Controller.api.formatter.url},
                        {field: 'imagewidth', title: __('Imagewidth'), sortable: true},
                        {field: 'imageheight', title: __('Imageheight'), sortable: true},
                        {field: 'imagetype', title: __('Imagetype'), formatter: Table.api.formatter.search},
                        // {field: 'storage', title: __('Storage'), formatter: Table.api.formatter.search},
                        {
                            field: 'filesize', title: __('Filesize'), operate: 'BETWEEN', sortable: true, formatter: function (value, row, index) {
                                var size = parseFloat(value);
                                var i = Math.floor(Math.log(size) / Math.log(1024));
                                return (size / Math.pow(1024, i)).toFixed(i < 2 ? 0 : 2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
                            }
                        },
                        {field: 'mimetype', title: __('Mimetype'), formatter: Table.api.formatter.search},
                        {field: 'uploadtime', title:  __('上传时间'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {
                        //     field: 'createtime',
                        //     title: __('Createtime'),
                        //     formatter: Table.api.formatter.datetime,
                        //     operate: 'RANGE',
                        //     addclass: 'datetimerange',
                        //     sortable: true
                        // },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'download/select',
                }
            });
            var urlArr = [];

            var table = $("#table");

            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
                if (e.type == 'check' || e.type == 'uncheck') {
                    row = [row];
                } else {
                    urlArr = [];
                }
                $.each(row, function (i, j) {
                    if (e.type.indexOf("uncheck") > -1) {
                        var index = urlArr.indexOf(j.url);
                        if (index > -1) {
                            urlArr.splice(index, 1);
                        }
                    } else {
                        urlArr.indexOf(j.url) == -1 && urlArr.push(j.url);
                    }
                });
            });

            // 初始化表格
            // table.bootstrapTable({
            //     url: $.fn.bootstrapTable.defaults.extend.index_url,
            //     sortName: 'id',
            //     showToggle: false,
            //     showExport: false,
            //     columns: [
            //         [
            //             {field: 'state', checkbox: true},
            //             {field: 'id', title: __('Id')},
            //             {field: 'admin_id', title: __('Admin_id'), formatter: Table.api.formatter.search, visible: false},
            //             {field: 'user_id', title: __('User_id'), formatter: Table.api.formatter.search, visible: false},
            //             {field: 'url', title: __('Preview'), formatter: Controller.api.formatter.thumb, operate: false},
            //             {field: 'imagewidth', title: __('Imagewidth'), operate: false},
            //             {field: 'imageheight', title: __('Imageheight'), operate: false},
            //             {
            //                 field: 'mimetype', title: __('Mimetype'), operate: 'LIKE %...%',
            //                 process: function (value, arg) {
            //                     return value.replace(/\*/g, '%');
            //                 }
            //             },
            //             {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
            //             {
            //                 field: 'operate', title: __('Operate'), events: {
            //                     'click .btn-chooseone': function (e, value, row, index) {
            //                         var multiple = Backend.api.query('multiple');
            //                         multiple = multiple == 'true' ? true : false;
            //                         Fast.api.close({url: row.url, multiple: multiple});
            //                     },
            //                 }, formatter: function () {
            //                     return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
            //                 }
            //             }
            //         ]
            //     ]
            // });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                // var urlArr = [];
                // $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                //     urlArr.push(j.url);
                // });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({url: urlArr.join(","), multiple: multiple});
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            require(['upload'], function (Upload) {
                Upload.api.plupload($("#toolbar .plupload"), function () {
                    $(".btn-refresh").trigger("click");
                });
            });
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
            },
            formatter: {
                thumb: function (value, row, index) {
                    if (row.mimetype.indexOf("image") > -1) {
                        var style = row.storage == 'upyun' ? '!/fwfh/120x90' : '';
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + style + '" alt="" style="max-height:90px;max-width:120px"></a>';
                    } else {
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="https://tool.fastadmin.net/icon/' + row.imagetype + '.png" alt=""></a>';
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
            }
        }

    };
    return Controller;
});