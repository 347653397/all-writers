webpackJsonp([20],{CRwn:function(e,t,n){"use strict";function a(e){return{users:e.users,loading:e.loading}}Object.defineProperty(t,"__esModule",{value:!0});var o=(n("ybmL"),n("IyT7")),l=n("ng98"),c=n.n(l),r=n("6osJ"),s=n.n(r),i=n("iups"),m=n.n(i),u=n("/O8+"),d=n.n(u),h=n("NX1q"),v=n.n(h),y=n("+lp1"),p=n.n(y),E=n("dN2m"),f=n.n(E),g=n("XWAK"),b=(n.n(g),n("KmIg")),w=(n.n(b),n("0xDb"),n("vvau")),I=(n.n(w),function(e){function t(e,n){var a;return c()(this,t),a=d()(this,v()(t).call(this,e,n)),a.state={},a.onScrollEnd=a.onScrollEnd.bind(p()(p()(a))),a}return m()(t,[{key:"componentDidMount",value:function(){var e=this,t=this.props.dispatch;this.move=window.document.ontouchmove,window.document.ontouchmove=function(e){e.preventDefault()},this.onTouchmove=function(e){e.preventDefault()},document.body.addEventListener("touchmove",this.onTouchmove,{passive:!1}),document.getElementById("moenyList").style.height=document.documentElement.clientHeight+"px";var n={preventDefault:!1,zoom:!1,mouseWheel:!0,probeType:3,bounce:!0,killFlicker:!0};this.iScrollInstance=new IScroll(document.getElementById("moenyList"),n),this.iScrollInstance.on("scrollEnd",this.onScrollEnd),t({type:"users/save",payload:{refreshDetai:function(){return e.iScrollInstance.refresh()}}}),t({type:"users/myWallet"})}},{key:"onScrollEnd",value:function(){var e=this.props.users.moenyLength,t=this.props.dispatch;this.iScrollInstance.y<=this.iScrollInstance.maxScrollY&&e&&t({type:"users/myWallet"})}},{key:"componentWillUnmount",value:function(){window.document.ontouchmove=this.move,document.body.removeEventListener("touchmove",this.onTouchmove,{passive:!1}),this.iScrollInstance.destroy()}},{key:"render",value:function(){var e=this.props,t=e.users,n=e.dispatch,a=(e.location,e.loading),l=function(e,t){n(b.routerRedux.push({pathname:e,query:t}))},c=t.toReward,r=t.moenyLength,s=t.cashBalance;return f.a.createElement("div",{className:"userDetai"},f.a.createElement(o.a,{toast:!0,text:"\u52a0\u8f7d\u6570\u636e",animating:a.effects["users/myWallet"]}),f.a.createElement("div",{id:"moenyList"},f.a.createElement("div",null,f.a.createElement("div",{className:"moeny"},f.a.createElement("p",null,"\u53ef\u63d0\u73b0\u91d1\u989d"),f.a.createElement("h2",null,"\uffe5",s),f.a.createElement("div",{className:"btn",onClick:function(){return l("/users/forward")}},"\u63d0\u73b0")),f.a.createElement("div",{className:"moenyList"},f.a.createElement("ul",null,c&&c.map(function(e,t){return f.a.createElement("li",{key:t},f.a.createElement("h3",null,function(){switch(e.trade_type){case"3":return"\u8bc4\u8bba\u88ab".concat(e.nickname,"\u6253\u8d4f\u4e86");case"7":return"\u6295\u7a3f\u88ab".concat(e.nickname,"\u8d2d\u4e70\u4e86");case"8":return"\u6295\u7a3f\u88ab".concat(e.nickname,"\u6253\u8d4f\u4e86")}}()),f.a.createElement("p",{style:{marginBottom:"0.1rem"}},"\u6765\u6e90\uff1a",e.title),f.a.createElement("p",null,new Date(Number(1e3*e.created_at)).Format("yyyy-MM-dd hh:mm")),f.a.createElement("span",null,e.money))}),0!=c.length?f.a.createElement("div",{className:"moreStyle"},r?a.effects["users/myWallet"]?"\u6b63\u5728\u52a0\u8f7d":"\u52a0\u8f7d\u66f4\u591a":"\u6ca1\u6709\u66f4\u591a\u660e\u7ec6\u4e86"):null),c&&0==c.length?f.a.createElement("div",null,f.a.createElement("div",{className:"lengthIcon"}),f.a.createElement("p",{className:"lengthIcontext",style:{marginBottom:"0.4rem"}},"\u6682\u65e0\u660e\u7ec6\u8bb0\u5f55"),f.a.createElement("br",null)):null))))}}]),s()(t,e),t}(E.Component));t.default=Object(g.connect)(a)(I)},vvau:function(e,t){}});