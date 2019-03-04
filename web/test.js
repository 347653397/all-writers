$(function() {
    (t = function(t) {
            this.defaults = {
                parentsEle: "body",
                rPath: "/",
                ajaxSet: {
                    url: null,
                    type: "GET",
                    data: {},
                    dataType: "json",
                    onRequestSuccess: function(t) {},
                    onRequestFail: function(t) {}
                },
                ajaxSetData: {
                    url: null,
                    type: "GET",
                    data: {},
                    dataType: "json",
                    onRequestSuccess: function(t) {},
                    onRequestFail: function(t) {}
                },
                ajaxSetSign: {
                    url: null,
                    type: "GET",
                    data: {},
                    dataType: "jsonp",
                    onRequestSuccess: function(t) {},
                    onRequestFail: function(t) {}
                },
                ajaxSetSignInfo: {
                    url: null,
                    type: "GET",
                    data: {},
                    dataType: "jsonp",
                    onRequestSuccess: function(t) {},
                    onRequestFail: function(t) {}
                }
            },
                this.options = $.extend(!0, {}, this.defaults, t),
                this.html = [],
                this.isOnOff = !0,
                this.page = 1,
                this.totalPage = 2,
                this.is_signed = 0,
                this.init()
        }
    ).prototype = {
        init: function() {
            this.dynamicLoadCss(this.options.rPath + "css/style.css?v=20180809a"),
                this.createDom()
        },
        loading: function() {
            var t = '<div class="loadingO"><img src="' + $.globalSet.img_default.loading + '" alt=""></div>';
            $(this.options.parentsEle).html(t)
        },
        ajaxRequest: function() {
            var d = this
                , t = $.ajax({
                url: this.options.ajaxSet.url,
                type: this.options.ajaxSet.type,
                data: this.options.ajaxSet.data,
                dataType: this.options.ajaxSet.dataType,
                timeout: 4e3
            });
            t.done(function(t) {
                if ("200" == t.status) {
                    var a = null;
                    a = 0 < t.data.count ? "visibility: visible;" : "visibility: hidden;",
                        $(".iconBtn").html('            <a class="goLook" href="https://home.youzu.com/msg/list" title="我的消息" target="_blank">\n                <span style="' + a + '">' + t.data.count + '</span>\n            </a>\n            <div class="signIn" title="每日签到"></div>\n')
                } else
                    $(".iconBtn").html("");
                d.getSignDate(),
                    $(".signIn").click(function() {
                        d.options.ajaxSetSignInfo.onRequestSuccess = function(t) {
                            d.popSign(t),
                                d.options.ajaxSetSignInfo.onRequestSuccess = null
                        }
                            ,
                            d.options.ajaxSetSign.onRequestSuccess = function(t) {
                                d.getSignDate()
                            }
                            ,
                            d.ajaxRequestSign()
                    }),
                    d.options.ajaxSet.onRequestSuccess({
                        data: t
                    })
            }),
                t.fail(function(t) {
                    d.options.ajaxSet.onRequestFail()
                })
        },
        ajaxRequestData: function() {
            var a = this
                , t = $.ajax({
                url: this.options.ajaxSetData.url,
                type: this.options.ajaxSetData.type,
                data: this.options.ajaxSetData.data,
                dataType: this.options.ajaxSetData.dataType,
                timeout: 4e3
            });
            t.done(function(t) {
                "200" == t.status && (a.createDetail(t.data),
                    a.totalPage = t.totalPage),
                    a.options.ajaxSet.onRequestSuccess({
                        data: t
                    })
            }),
                t.fail(function(t) {
                    a.options.ajaxSet.onRequestFail()
                })
        },
        ajaxRequestSign: function() {
            var a = this;
            $.getJSON(this.options.ajaxSetSign.url, function(t) {
                200 == t.status && (1 == t.data.is_signed && (a.is_signed = 1),
                    a.options.ajaxSetSign.onRequestSuccess())
            })
        },
        getSignDate: function() {
            var a = this;
            $.getJSON(this.options.ajaxSetSignInfo.url, function(t) {
                200 == t.status && (0 < (a.data_sign = t).data.todaySignNum ? (a.is_signed = 1,
                    $(".signIn").hide()) : $(".signIn").show(),
                    a.options.ajaxSetSignInfo.onRequestSuccess(t))
            })
        },
        createDom: function(t) {
            $(this.options.parentsEle).append('<div class="radioBox">\n    <div class="radioOpen">\n    <div class="goTop"></div>\n    </div>\n    <div class="radioDetail">\n        <div class="radioClose"></div>\n        <a class="radioChange"></a>\n        <div class="iconBtn">\n        </div>\n    <div class="goTop1"></div>\n    </div>\n</div>'),
                this.bind()
        },
        createDetail: function(t) {
            var a = '<div class="detailMsg" data_loc_id="000100013" event_type="click" custom="id:' + t.id + '" data_id="' + this.page + '_website">\n<a class="detailTitle" href="' + t.link + '" target="_blank" title="' + t.title + '">' + t.title + '</a>\n<a class="detailPart" href="' + t.link + '" target="_blank">' + t.content + '</a>\n<div class="radioWow ' + (1 == t.subscript ? "" : "controlHide") + '"></div>\n</div>\n';
            $(".detailMsg").remove(),
                $(".radioDetail").append(a)
        },
        bind: function() {
            var t = this;
            t.ajaxRequestData(),
                this.getCookie("isRadioGram") ? ($(".radioOpen").hide(),
                    $(".radioDetail").show(),
                    t.ajaxRequest()) : ($(".radioOpen").show(),
                    $(".radioDetail").hide()),
                $(".radioOpen").click(function() {
                    $(".radioOpen").hide(),
                        t.addCookie("isRadioGram", "!0"),
                        $(".radioDetail").show(),
                        t.ajaxRequest()
                }),
                $(".radioClose").click(function() {
                    $(".radioDetail").hide(),
                        t.isOnOff = !0,
                        t.deleteCookie("isRadioGram"),
                        $(".radioOpen").show()
                }),
                $(".radioChange").click(function() {
                    t.page < t.totalPage ? t.page++ : t.page = 1,
                        t.options.ajaxSetData.data = {
                            page: t.page,
                            "int": 1
                        },
                        t.ajaxRequestData()
                }),
                $(".goTop").click(function(t) {
                    t.stopPropagation();
                    $(window).scrollTop();
                    $("body,html").animate({
                        scrollTop: "0px"
                    }, 100)
                }),
                $(".goTop1").click(function(t) {
                    t.stopPropagation(),
                        $("body,html").animate({
                            scrollTop: "0px"
                        }, 100)
                });
            var a = null;
            $(window).scroll(function() {
                null !== a && clearTimeout(a),
                    a = setTimeout(function() {
                        500 < $(window).scrollTop() ? ($(".goTop").show(),
                            $(".goTop1").show()) : ($(".goTop").hide(),
                            $(".goTop1").hide())
                    }
                        .bind(this), 200)
            })
        },
        addCookie: function(t, a, d, i, e) {
            e = e || "/";
            var s = t + "=" + escape(a) + "; path=" + e;
            if (0 < d) {
                var n = new Date;
                n.setTime(n.getTime() + 3600 * d * 1e3),
                    s = s + "; expires=" + n.toGMTString()
            }
            i && (s = s + "; domain=" + i),
                document.cookie = s
        },
        getCookie: function(t) {
            for (var a = document.cookie.split("; "), d = 0; d < a.length; d++) {
                var i = a[d].split("=");
                if (i[0] == t)
                    return unescape(i[1])
            }
            return ""
        },
        deleteCookie: function(t, a, d) {
            d = d || "/";
            var i = new Date;
            i.setTime(i.getTime() - 1e4);
            var e = t + "=v; expires=" + i.toGMTString() + "; path=" + d;
            a && (e = e + "; domain=" + a),
                document.cookie = e
        },
        popSign: function(t) {
            var a = this
                , d = new Date
                , i = d.getFullYear()
                , e = d.getMonth() + 1
                , s = i + (e = e < 10 ? "0" + e : "" + e);
            if (t && 200 == t.status) {
                var n = t.data.dataList
                    , o = ""
                    , l = ""
                    , c = ""
                    , p = ""
                    , u = ""
                    , v = ""
                    , r = ""
                    , _ = "";
                n[s + "0"] && (l = " date_on"),
                n[s + "0"] && (c = " date_on"),
                n[s + "0"] && (p = " date_on"),
                n[s + "01"] && (u = " date_on"),
                n[s + "02"] && (v = " date_on"),
                n[s + "03"] && (r = " date_on"),
                n[s + "04"] && (_ = " date_on"),
                    o += '<tr><td class="date_d ' + l + '"><div></div></td><td class="date_d ' + c + '"><div></div></td><td class="date_d ' + p + '"><div></div></td><td class="date_d ' + u + '"><div>1</div></td><td class="date_d ' + v + '"><div>2</div></td><td class="date_d ' + r + '"><div>3</div></td><td class="date_d ' + _ + '"><div>4</div></td></tr>';
                l = "",
                    c = "",
                    p = "",
                    u = "",
                    v = "",
                    r = "",
                    _ = "";
                n[s + "05"] && (l = " date_on"),
                n[s + "06"] && (c = " date_on"),
                n[s + "07"] && (p = " date_on"),
                n[s + "08"] && (u = " date_on"),
                n[s + "09"] && (v = " date_on"),
                n[s + "10"] && (r = " date_on"),
                n[s + "11"] && (_ = " date_on"),
                    o += '<tr><td class="date_d ' + l + '"><div>5</div></td><td class="date_d ' + c + '"><div>6</div></td><td class="date_d ' + p + '"><div>7</div></td><td class="date_d ' + u + '"><div>8</div></td><td class="date_d ' + v + '"><div>9</div></td><td class="date_d ' + r + '"><div>10</div></td><td class="date_d ' + _ + '"><div>11</div></td></tr>';
                l = "",
                    c = "",
                    p = "",
                    u = "",
                    v = "",
                    r = "",
                    _ = "";
                n[s + "12"] && (l = " date_on"),
                n[s + "13"] && (c = " date_on"),
                n[s + "14"] && (p = " date_on"),
                n[s + "15"] && (u = " date_on"),
                n[s + "16"] && (v = " date_on"),
                n[s + "17"] && (r = " date_on"),
                n[s + "18"] && (_ = " date_on"),
                    o += '<tr><td class="date_d ' + l + '"><div>12</div></td><td class="date_d ' + c + '"><div>13</div></td><td class="date_d ' + p + '"><div>14</div></td><td class="date_d ' + u + '"><div>15</div></td><td class="date_d ' + v + '"><div>16</div></td><td class="date_d ' + r + '"><div>17</div></td><td class="date_d ' + _ + '"><div>18</div></td></tr>';
                l = "",
                    c = "",
                    p = "",
                    u = "",
                    v = "",
                    r = "",
                    _ = "";
                n[s + "19"] && (l = " date_on"),
                n[s + "20"] && (c = " date_on"),
                n[s + "21"] && (p = " date_on"),
                n[s + "22"] && (u = " date_on"),
                n[s + "23"] && (v = " date_on"),
                n[s + "24"] && (r = " date_on"),
                n[s + "25"] && (_ = " date_on"),
                    o += '<tr><td class="date_d ' + l + '"><div>19</div></td><td class="date_d ' + c + '"><div>20</div></td><td class="date_d ' + p + '"><div>21</div></td><td class="date_d ' + u + '"><div>22</div></td><td class="date_d ' + v + '"><div>23</div></td><td class="date_d ' + r + '"><div>24</div></td><td class="date_d ' + _ + '"><div>25</div></td></tr>';
                l = "",
                    c = "",
                    p = "",
                    u = "",
                    v = "",
                    r = "",
                    _ = "";
                n[s + "26"] && (l = " date_on"),
                n[s + "27"] && (c = " date_on"),
                n[s + "28"] && (p = " date_on"),
                n[s + "29"] && (u = " date_on"),
                n[s + "30"] && (v = " date_on"),
                n[s + "31"] && (r = " date_on"),
                n[s + "0"] && (_ = " date_on");
                o = '<div class="pop_qiandao_bg"></div><div class="pop_qiandao_Group" >                <div class="pop_titleZu">                <span class="tit_biaoti">每日签到</span>                <a  href="javascript:;" class="ic_close"></a>                </div>                <div class="pop_neirongZu">                <div class="eshop_nr">                <div class="qiandao_left">                <p class="qiandao_left_title"><b>签到日记</b></p>                <div class="calendar_group">                <div class="calendar_ym"> ' + i + " 年 " + e + ' 月  </div>            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="calendar_date">                <thead>                <tr>                <td class="date_w"><div>日</div></td>                <td class="date_w"><div>一</div></td>                <td class="date_w"><div>二</div></td>                <td class="date_w"><div>三</div></td>                <td class="date_w"><div>四</div></td>                <td class="date_w"><div>五</div></td>                <td class="date_w"><div>六</div></td>                </tr>                </thead>                <tbody>' + (o += '<tr><td class="date_d ' + l + '"><div>26</div></td><td class="date_d ' + c + '"><div>27</div></td><td class="date_d ' + p + '"><div>28</div></td><td class="date_d ' + u + '"><div>29</div></td><td class="date_d ' + v + '"><div>30</div></td><td class="date_d ' + r + '"><div>31</div></td><td class="date_d ' + _ + '"><div></div></td></tr>') + '<tr>            <td colspan="7"  class="date_days">已经连续签到：<span class="qd_day_num">' + t.data.todaySignNum + '</span>天 累计签到：<span class="qd_day_num">' + t.data.totalSigned + '</span>天</td>                </tr>                </tbody>                </table>                </div>                </div>                <div class="qiandao_right">                <p class="title_qiandao">每天签到得<span class="qd_num">2</span>积分</p>                <p><b>签到规则：</b></p>            <ul class="qiao_list">                <li>连续签到3天，可额外得<span class="c_num">5</span>积分</li>                <li>连续签到7天，可额外得<span class="c_num">10</span>积分</li>                <li>连续签到15天，可额外得<span class="c_num">15</span>积分</li>                <li>连续签到30天，可额外得<span class="c_num">20</span>积分</li>                </ul>                </div>                <div class="clear"></div>                </div>                </div>                </div>';
                try {
                    com_login.picProp.init(o, function(t) {}).show(),
                        $(".pop_qiandao_Group").css("margin-left", "-320px"),
                        $(".pop_qiandao_bg").remove()
                } catch (g) {
                    $(a.options.parentsEle).append(o),
                        $(".pop_qiandao_Group").css({
                            "margin-left": "-320px",
                            "margin-top": "-193px",
                            "z-index": 100,
                            position: "fixed",
                            top: "50%",
                            left: "50%"
                        })
                }
                $(".ic_close,.pop_qiandao_bg").click(function() {
                    $(".pop_qiandao_Group").remove(),
                        $(".pop_qiandao_bg").remove(),
                        background().hide()
                })
            } else
                ;
        },
        dynamicLoadCss: function(t) {
            var a = document.getElementsByTagName("head")[0]
                , d = document.createElement("link");
            d.type = "text/css",
                d.rel = "stylesheet",
                d.href = t,
                a.appendChild(d)
        }
    };
    var t = new t({
        rPath: "//pic.youzu.com/youzu/web/common/radio/",
        ajaxSet: {
            url: "//home.youzu.com/jsonp/msg/new",
            dataType: "jsonp",
            onRequestSuccess: function(t) {},
            onRequestFail: function(t) {}
        },
        ajaxSetData: {
            url: "//www.youzu.com/api/get-broadcast.html",
            type: "GET",
            data: {
                page: 1,
                "int": 1
            },
            dataType: "jsonp",
            onRequestSuccess: function(t) {},
            onRequestFail: function(t) {}
        },
        ajaxSetSign: {
            url: "//home.youzu.com/jsonp/sign?callback=?"
        },
        ajaxSetSignInfo: {
            url: "//home.youzu.com/jsonp/sign/date?callback=?"
        }
    })
});
