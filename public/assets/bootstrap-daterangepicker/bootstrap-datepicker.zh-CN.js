function init() {
    //定义locale汉化插件
    var locale = {
        "format": 'YYYY-MM-DD',
        "separator": " -222 ",
        "applyLabel": "确定",
        "cancelLabel": "取消",
        "fromLabel": "起始时间",
        "toLabel": "结束时间'",
        "customRangeLabel": "自定义",
        "weekLabel": "W",
        "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
        "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        "firstDay": 1
    };
    //初始化显示当前时间
    // console.log($('#start_time').val());
    if(!$('#start_time').val() || !$('#end_time').val()){
        $('#daterange-btn span').html(moment().subtract(29, 'days').format('YYYY-MM-DD') + ' 至 ' + moment().format('YYYY-MM-DD'));
        $('#start_time').val(moment().subtract(29, 'days').format('YYYY-MM-DD'));
        $('#end_time').val(moment().format('YYYY-MM-DD'));
    }

    //日期控件初始化
    $('#daterange-btn').daterangepicker(
        {
            'locale': locale,
            //汉化按钮部分
            ranges: {
                '今日': [moment(), moment()],
                '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '最近7日': [moment().subtract(6, 'days'), moment()],
                '最近30日': [moment().subtract(29, 'days'), moment()],
                '本月': [moment().startOf('month'), moment().endOf('month')],
                '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens : 'right',    // 日期选择框的弹出位置
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
        },
        function (start, end) {
            $('#start_time').val(start.format('YYYY-MM-DD'));
            $('#end_time').val(end.format('YYYY-MM-DD'));
            $('#daterange-btn span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
        }
    );
};

$(document).ready(function() {
    init();
});