webpackJsonp([3,9,14,15,16],{"04qB":function(e,t,n){"use strict";function i(e){return{home:e.home,detai:e.detai,loading:e.loading}}Object.defineProperty(t,"__esModule",{value:!0});var o=(n("ybmL"),n("IyT7")),a=n("+lp1"),r=n.n(a),s=n("ng98"),l=n.n(s),c=n("6osJ"),u=n.n(c),h=n("iups"),d=n.n(h),p=n("/O8+"),f=n.n(p),m=n("NX1q"),g=n.n(m),v=n("dN2m"),y=n.n(v),S=n("XWAK"),b=(n.n(S),n("hLrO")),T=(n.n(b),n("ktQX")),w=n("gPBw"),E=n("UfEG"),k=n("HFrE"),C=n("KmIg"),x=(n.n(C),y.a.Component,function(e){function t(e,n){var i;return l()(this,t),i=f()(this,g()(t).call(this,e,n)),i.callback=function(e){var t=i.props.home,n=t.refreshLoading,o=t.currentKey;n||o==e||i.isTouching||setTimeout(function(){i.componentReset(),i.props.dispatch(C.routerRedux.push({pathname:"/",query:{tab:e}})),i.props.dispatch({type:"home/save",payload:{homeLoading:!0,homeItem:[]}}),document.getElementsByClassName("tabContent")[0].scrollTop=0,i.iScrollScrren.scrollTo(0,0)},50)},i.state={},i.onScroll=i.onScroll.bind(r()(r()(i))),i.onScrollEnd=i.onScrollEnd.bind(r()(r()(i))),i.onTouchStart=i.onTouchStart.bind(r()(r()(i))),i.onTouchEnd=i.onTouchEnd.bind(r()(r()(i))),i.isTouching=!1,i}return d()(t,[{key:"componentDidMount",value:function(){var e=this,t=this.props.dispatch;t({type:"home/save",payload:{homeLoading:!0}});var n=document.documentElement.clientHeight-(document.getElementsByClassName("contaionBar")[0].offsetHeight+document.getElementsByClassName("tabsList")[0].offsetHeight)+"px";document.getElementById("tabContentHeight").style.height=n;location.hash&&location.hash.split("=")[1];this.move=window.document.ontouchmove,window.document.ontouchmove=function(e){e.preventDefault()},this.onTouchmove=function(e){e.preventDefault()},document.body.addEventListener("touchmove",this.onTouchmove,{passive:!1}),t({type:"home/save",payload:{homeLoading:!0}}),t({type:"detai/weixinShareConfig",payload:{url:location.href.split("#")[0]}});var i={preventDefault:!1,zoom:!1,mouseWheel:!0,probeType:3,bounce:!0,killFlicker:!0};this.iScrollScrren=new IScroll(document.getElementById("tabContentHeight"),i),this.iScrollScrren.on("scrollEnd",this.onScrollEnd),this.iScrollScrren.on("scroll",this.onScroll),t({type:"home/save",payload:{homeRefresh:function(){return e.iScrollScrren.refresh()}}})}},{key:"onScroll",value:function(){var e=this.props.home,t=e.homeLength;e.refreshLoading;this.iScrollScrren.y&&this.isTouching&&this.iScrollScrren.y<=this.iScrollScrren.maxScrollY+60&&t&&this.props.dispatch({type:"home/save",payload:{refreshLoading:!0}}),this.iScrollScrren.y&&this.iScrollScrren.maxScrollY-this.iScrollScrren.y>=10&&t&&this.props.dispatch({type:"home/save",payload:{refreshLoading:!0}})}},{key:"onScrollEnd",value:function(){var e=this.props.home.homeLength,t=this.props.dispatch;this.iScrollScrren.y&&this.iScrollScrren.y<=this.iScrollScrren.maxScrollY+60&&e&&!this.isTouching?this.onGetlist():t({type:"home/save",payload:{refreshLoading:!1}})}},{key:"onGetlist",value:function(){var e=this.props.home.currentKey;(0,this.props.dispatch)({type:"home/getCourseList",payload:{currentKey:e}})}},{key:"onTouchStart",value:function(e){this.isTouching=!0}},{key:"onTouchEnd",value:function(e){this.isTouching=!1}},{key:"componentWillUnmount",value:function(){window.document.ontouchmove=this.move,document.body.removeEventListener("touchmove",this.onTouchmove,{passive:!1}),void 0!=this.iScrollScrren&&this.iScrollScrren.destroy(),this.componentReset()}},{key:"componentWillReceiveProps",value:function(e){var t=e.detai.audoPusenStatus;e.home.refreshLoading||t||setTimeout(this.iScrollScrren.refresh(),500)}},{key:"componentReset",value:function(){this.props.dispatch({type:"home/save",payload:{homeItem:[],pageNum:1,homeLength:!0}})}},{key:"render",value:function(){var e=this,t=this.props.home,n=t.currentKey,i=(t.homeLength,t.homeRefresh,t.refreshLoading),a=(t.bannerData,t.categoryData);t.homeLoading;return y.a.createElement("div",{className:"homeScreen"},y.a.createElement(o.a,{toast:!0,text:"\u52a0\u8f7d\u6570\u636e",animating:i}),y.a.createElement("ul",{className:"tabsList"},0!=a.length&&a.map(function(t,i){return y.a.createElement("li",{key:i,className:n==Number(t.cid)?"active":null,onClick:function(){return e.callback(Number(t.cid))}},t.cat_name)})),y.a.createElement("div",{className:"am-tabs-pane-wrap-active",id:"tabContentHeight",onTouchStart:this.onTouchStart,onTouchEnd:this.onTouchEnd},y.a.createElement("div",{className:"tabContent"},function(){switch(n){case 1:return y.a.createElement(T.default,null);case 2:return y.a.createElement(w.default,null);case 3:return y.a.createElement(E.default,null);case 4:return y.a.createElement(k.default,null)}}())))}}]),u()(t,e),t}(v.Component));t.default=Object(S.connect)(i)(x)},HFrE:function(e,t,n){"use strict";function i(e){return{home:e.home}}Object.defineProperty(t,"__esModule",{value:!0});var o=n("ng98"),a=n.n(o),r=n("6osJ"),s=n.n(r),l=n("iups"),c=n.n(l),u=n("/O8+"),h=n.n(u),d=n("NX1q"),p=n.n(d),f=n("dN2m"),m=n.n(f),g=n("XWAK"),v=(n.n(g),n("N158")),y=n("UnHS"),S=(n.n(y),function(e){function t(){return a()(this,t),h()(this,p()(t).apply(this,arguments))}return c()(t,[{key:"render",value:function(){var e=this.props.home,t=e.homeItem,n=e.homeLoading;return m.a.createElement("div",{className:"tabsBox ".concat(n?"":"fadeIn animated")},m.a.createElement("div",{className:"listBox listTab2"},m.a.createElement(v.a,{item:t,routerName:"/detai",type:"\u4e16\u95f4\u542c"})))}}]),s()(t,e),t}(m.a.Component));t.default=Object(g.connect)(i)(S)},N158:function(e,t,n){"use strict";function i(e){return{home:e.home}}var o=n("dN2m"),a=n.n(o),r=n("XWAK"),s=(n.n(r),n("KmIg")),l=(n.n(s),function(e){var t=e.item,n=(e.type,e.dispatch),i=e.home,o=function(e,t){n(s.routerRedux.push({pathname:t,query:{id:e,in_type:1}}))};return a.a.createElement("div",null,a.a.createElement("ul",{style:{background:t&&0==t.length?"none":"#fff"}},t&&t.map(function(e,t){return a.a.createElement("li",{key:t,onClick:function(){return o(2==e.up_type?e.course_id:e.item_id,2==e.up_type?"/detai/list":"/detai")}},a.a.createElement("div",{className:"left"},a.a.createElement("img",{src:e.course_pic})),a.a.createElement("div",{className:"right"},a.a.createElement("h3",{className:"textOversTab2"},2==e.up_type?a.a.createElement("span",{className:"color1"},"\u8fde\u8f7d"):a.a.createElement("span",{className:"color2"},"\u5355\u96c6"),e.course_title),a.a.createElement("p",{className:"textOversTab2"},e.course_brief||"\u6682\u65e0\u4ecb\u7ecd"),a.a.createElement("p",null,a.a.createElement("span",{className:"price",style:{float:"left",fontSize:"0.35rem"}},e.author)),a.a.createElement("div",{className:"tags"},a.a.createElement("span",null,e.play_num>1e4?(e.play_num-e.play_num%1e3)/1e4+"W":e.play_num||0),a.a.createElement("span",null,e.comment_count>1e4?(e.comment_count-e.comment_count%1e3)/1e4+"W":e.comment_count),a.a.createElement("span",{className:"price"},Number(e.total_amount)?"\uffe5"+e.total_amount||0:null))))}),t&&0==t.length&&!i.homeLoading?a.a.createElement("div",{className:"lengthIcon"}):null),a.a.createElement("div",{className:"moreStyle"},0!=t.length?i.homeLength?i.refreshLoading?"\u6b63\u5728\u52a0\u8f7d":"\u52a0\u8f7d\u66f4\u591a":"\u6ca1\u6709\u66f4\u591a\u4e86":""))});t.a=Object(r.connect)(i)(l)},NAMZ:function(e,t){},UfEG:function(e,t,n){"use strict";function i(e){return{home:e.home}}Object.defineProperty(t,"__esModule",{value:!0});var o=n("ng98"),a=n.n(o),r=n("6osJ"),s=n.n(r),l=n("iups"),c=n.n(l),u=n("/O8+"),h=n.n(u),d=n("NX1q"),p=n.n(d),f=n("dN2m"),m=n.n(f),g=n("XWAK"),v=(n.n(g),n("N158")),y=n("UnHS"),S=(n.n(y),function(e){function t(){return a()(this,t),h()(this,p()(t).apply(this,arguments))}return c()(t,[{key:"render",value:function(){var e=this.props.home,t=e.homeItem,n=e.homeLoading;return m.a.createElement("div",{className:"tabsBox ".concat(n?"":"fadeIn animated")},m.a.createElement("div",{className:"listBox listTab2"},m.a.createElement(v.a,{item:t,routerName:"/detai",type:"\u5c0f\u725b\u53c2\u8c0b\u957f"})))}}]),s()(t,e),t}(m.a.Component));t.default=Object(g.connect)(i)(S)},UnHS:function(e,t){},XbKq:function(e,t,n){var i;!function(){"use strict";var o=!("undefined"==typeof window||!window.document||!window.document.createElement),a={canUseDOM:o,canUseWorkers:"undefined"!=typeof Worker,canUseEventListeners:o&&!(!window.addEventListener&&!window.attachEvent),canUseViewport:o&&!!window.screen};void 0!==(i=function(){return a}.call(t,n,t,e))&&(e.exports=i)}()},YAjU:function(e,t,n){(function(t){(function(){var n,i,o,a,r,s;"undefined"!=typeof performance&&null!==performance&&performance.now?e.exports=function(){return performance.now()}:void 0!==t&&null!==t&&t.hrtime?(e.exports=function(){return(n()-r)/1e6},i=t.hrtime,n=function(){var e;return e=i(),1e9*e[0]+e[1]},a=n(),s=1e9*t.uptime(),r=a-s):Date.now?(e.exports=function(){return Date.now()-o},o=Date.now()):(e.exports=function(){return(new Date).getTime()-o},o=(new Date).getTime())}).call(this)}).call(t,n("Wo/5"))},amyL:function(e,t,n){(function(t){for(var i=n("YAjU"),o="undefined"==typeof window?t:window,a=["moz","webkit"],r="AnimationFrame",s=o["request"+r],l=o["cancel"+r]||o["cancelRequest"+r],c=0;!s&&c<a.length;c++)s=o[a[c]+"Request"+r],l=o[a[c]+"Cancel"+r]||o[a[c]+"CancelRequest"+r];if(!s||!l){var u=0,h=0,d=[];s=function(e){if(0===d.length){var t=i(),n=Math.max(0,1e3/60-(t-u));u=n+t,setTimeout(function(){var e=d.slice(0);d.length=0;for(var t=0;t<e.length;t++)if(!e[t].cancelled)try{e[t].callback(u)}catch(e){setTimeout(function(){throw e},0)}},Math.round(n))}return d.push({handle:++h,callback:e,cancelled:!1}),h},l=function(e){for(var t=0;t<d.length;t++)d[t].handle===e&&(d[t].cancelled=!0)}}e.exports=function(e){return s.call(o,e)},e.exports.cancel=function(){l.apply(o,arguments)},e.exports.polyfill=function(e){e||(e=o),e.requestAnimationFrame=s,e.cancelAnimationFrame=l}}).call(t,n("u28U"))},gPBw:function(e,t,n){"use strict";function i(e){return{home:e.home}}Object.defineProperty(t,"__esModule",{value:!0});var o=n("ng98"),a=n.n(o),r=n("6osJ"),s=n.n(r),l=n("iups"),c=n.n(l),u=n("/O8+"),h=n.n(u),d=n("NX1q"),p=n.n(d),f=n("dN2m"),m=n.n(f),g=n("XWAK"),v=(n.n(g),n("UnHS")),y=(n.n(v),n("N158")),S=function(e){function t(){return a()(this,t),h()(this,p()(t).apply(this,arguments))}return c()(t,[{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=this.props.home,t=e.homeItem,n=e.homeLoading;return m.a.createElement("div",{className:"tabsBox ".concat(n?"":"fadeIn animated")},m.a.createElement("div",{className:"listBox listTab2"},m.a.createElement(y.a,{item:t,routerName:"/detai",type:"\u542c\u6545\u4e8b"})))}}]),s()(t,e),t}(m.a.Component);t.default=Object(g.connect)(i)(S)},hLrO:function(e,t){},ktQX:function(e,t,n){"use strict";function i(e,t,n,i){return(n-t)*Math.sqrt(1-(e=e/i-1)*e)+t}function o(e,t,n,i){return(n-t)*e/i+t}function a(e){return{home:e.home,loading:e.loading}}Object.defineProperty(t,"__esModule",{value:!0});var r=(n("99xQ"),n("NAMZ"),n("WxFH")),s=n.n(r),l=n("34v0"),c=n.n(l),u=n("dbJu"),h=n.n(u),d=n("HFN4"),p=n.n(d),f=n("Lxvp"),m=n.n(f),g=n("i9+B"),v=n.n(g),y=n("lIaQ"),S=n.n(y),b=n("dN2m"),T=n.n(b),w=[{component:function(e){function t(){h()(this,t);var e=m()(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return e.handleClick=function(t){t.preventDefault(),e.props.previousSlide()},e}return v()(t,e),p()(t,[{key:"render",value:function(){return T.a.createElement("button",{style:this.getButtonStyles(0===this.props.currentSlide&&!this.props.wrapAround),onClick:this.handleClick},"PREV")}},{key:"getButtonStyles",value:function(e){return{border:0,background:"rgba(0,0,0,0.4)",color:"white",padding:10,outline:0,opacity:e?.3:1,cursor:"pointer"}}}]),t}(T.a.Component),position:"CenterLeft"},{component:function(e){function t(){h()(this,t);var e=m()(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return e.handleClick=function(t){t.preventDefault(),e.props.nextSlide&&e.props.nextSlide()},e}return v()(t,e),p()(t,[{key:"render",value:function(){return T.a.createElement("button",{style:this.getButtonStyles(this.props.currentSlide+this.props.slidesToScroll>=this.props.slideCount&&!this.props.wrapAround),onClick:this.handleClick},"NEXT")}},{key:"getButtonStyles",value:function(e){return{border:0,background:"rgba(0,0,0,0.4)",color:"white",padding:10,outline:0,opacity:e?.3:1,cursor:"pointer"}}}]),t}(T.a.Component),position:"CenterRight"},{component:function(e){function t(){return h()(this,t),m()(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments))}return v()(t,e),p()(t,[{key:"render",value:function(){var e=this,t=this.getIndexes(this.props.slideCount,this.props.slidesToScroll);return T.a.createElement("ul",{style:this.getListStyles()},t.map(function(t){return T.a.createElement("li",{style:e.getListItemStyles(),key:t},T.a.createElement("button",{style:e.getButtonStyles(e.props.currentSlide===t),onClick:e.props.goToSlide&&e.props.goToSlide.bind(null,t)},"\u2022"))}))}},{key:"getIndexes",value:function(e,t){for(var n=[],i=0;i<e;i+=t)n.push(i);return n}},{key:"getListStyles",value:function(){return{position:"relative",margin:0,top:-10,padding:0}}},{key:"getListItemStyles",value:function(){return{listStyleType:"none",display:"inline-block"}}},{key:"getButtonStyles",value:function(e){return{border:0,background:"transparent",color:"black",cursor:"pointer",padding:10,outline:0,fontSize:24,opacity:e?1:.5}}}]),t}(T.a.Component),position:"BottomCenter"}],E=w,k=n("XbKq"),C=n.n(k),x=n("amyL"),O=n.n(x),L={ADDITIVE:"ADDITIVE",DESTRUCTIVE:"DESTRUCTIVE"},N=function(e,t,n){null!==e&&void 0!==e&&(e.addEventListener?e.addEventListener(t,n,!1):e.attachEvent?e.attachEvent("on"+t,n):e["on"+t]=n)},W=function(e,t,n){null!==e&&void 0!==e&&(e.removeEventListener?e.removeEventListener(t,n,!1):e.detachEvent?e.detachEvent("on"+t,n):e["on"+t]=null)},M=function(e){function t(e){h()(this,t);var n=m()(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n._rafCb=function(){var e=n.state;if(0!==e.tweenQueue.length){for(var t=Date.now(),i=[],o=0;o<e.tweenQueue.length;o++){var a=e.tweenQueue[o],r=a.initTime,s=a.config;t-r<s.duration?i.push(a):s.onEnd&&s.onEnd()}-1!==n._rafID&&(n.setState({tweenQueue:i}),n._rafID=O()(n._rafCb))}},n.handleClick=function(e){!0===n.clickSafe&&(e.preventDefault(),e.stopPropagation(),e.nativeEvent&&e.nativeEvent.stopPropagation())},n.autoplayIterator=function(){if(n.props.wrapAround)return n.nextSlide();n.state.currentSlide!==n.state.slideCount-n.state.slidesToShow?n.nextSlide():n.stopAutoplay()},n.goToSlide=function(e){var t=n.props,i=t.beforeSlide,o=t.afterSlide;if(e>=T.a.Children.count(n.props.children)||e<0){if(!n.props.wrapAround)return;if(e>=T.a.Children.count(n.props.children))return i(n.state.currentSlide,0),n.setState({currentSlide:0},function(){n.animateSlide(null,null,n.getTargetLeft(null,e),function(){n.animateSlide(null,.01),o(0),n.resetAutoplay(),n.setExternalData()})});var a=T.a.Children.count(n.props.children)-n.state.slidesToScroll;return i(n.state.currentSlide,a),n.setState({currentSlide:a},function(){n.animateSlide(null,null,n.getTargetLeft(null,e),function(){n.animateSlide(null,.01),o(a),n.resetAutoplay(),n.setExternalData()})})}i(n.state.currentSlide,e),n.setState({currentSlide:e},function(){n.animateSlide(),n.props.afterSlide(e),n.resetAutoplay(),n.setExternalData()})},n.nextSlide=function(){var e=T.a.Children.count(n.props.children),t=n.props.slidesToShow;if("auto"===n.props.slidesToScroll&&(t=n.state.slidesToScroll),!(n.state.currentSlide>=e-t)||n.props.wrapAround)if(n.props.wrapAround)n.goToSlide(n.state.currentSlide+n.state.slidesToScroll);else{if(1!==n.props.slideWidth)return n.goToSlide(n.state.currentSlide+n.state.slidesToScroll);n.goToSlide(Math.min(n.state.currentSlide+n.state.slidesToScroll,e-t))}},n.previousSlide=function(){n.state.currentSlide<=0&&!n.props.wrapAround||(n.props.wrapAround?n.goToSlide(n.state.currentSlide-n.state.slidesToScroll):n.goToSlide(Math.max(0,n.state.currentSlide-n.state.slidesToScroll)))},n.onResize=function(){n.setDimensions()},n.onReadyStateChange=function(){n.setDimensions()},n.state={currentSlide:n.props.slideIndex,dragging:!1,frameWidth:0,left:0,slideCount:0,slidesToScroll:n.props.slidesToScroll,slideWidth:0,top:0,tweenQueue:[]},n.touchObject={},n.clickSafe=!0,n}return v()(t,e),p()(t,[{key:"componentWillMount",value:function(){this.setInitialDimensions()}},{key:"componentDidMount",value:function(){this.setDimensions(),this.bindEvents(),this.setExternalData(),this.props.autoplay&&this.startAutoplay()}},{key:"componentWillReceiveProps",value:function(e){this.setState({slideCount:e.children.length}),this.setDimensions(e),this.props.slideIndex!==e.slideIndex&&e.slideIndex!==this.state.currentSlide&&this.goToSlide(e.slideIndex),this.props.autoplay!==e.autoplay&&(e.autoplay?this.startAutoplay():this.stopAutoplay())}},{key:"componentWillUnmount",value:function(){this.unbindEvents(),this.stopAutoplay(),O.a.cancel(this._rafID),this._rafID=-1}},{key:"tweenState",value:function(e,t){var n=this,i=t.easing,o=t.duration,a=t.delay,r=t.beginValue,s=t.endValue,l=t.onEnd,c=t.stackBehavior;this.setState(function(t){var u=t,h=void 0,d=void 0;if("string"==typeof e)h=e,d=e;else{for(var p=0;p<e.length-1;p++)u=u[e[p]];h=e[e.length-1],d=e.join("|")}var f={easing:i,duration:null==o?300:o,delay:null==a?0:a,beginValue:null==r?u[h]:r,endValue:s,onEnd:l,stackBehavior:c||"ADDITIVE"},m=t.tweenQueue;return f.stackBehavior===L.DESTRUCTIVE&&(m=t.tweenQueue.filter(function(e){return e.pathHash!==d})),m.push({pathHash:d,config:f,initTime:Date.now()+f.delay}),u[h]=f.endValue,1===m.length&&(n._rafID=O()(n._rafCb)),{tweenQueue:m}})}},{key:"getTweeningValue",value:function(e){var t=this.state,n=void 0,i=void 0;if("string"==typeof e)n=t[e],i=e;else{n=t;for(var o=0;o<e.length;o++)n=n[e[o]];i=e.join("|")}for(var a=Date.now(),r=0;r<t.tweenQueue.length;r++){var s=t.tweenQueue[r],l=s.pathHash,c=s.initTime,u=s.config;if(l===i){var h=a-c>u.duration?u.duration:Math.max(0,a-c);n+=(0===u.duration?u.endValue:u.easing(h,u.beginValue,u.endValue,u.duration))-u.endValue}}return n}},{key:"render",value:function(){var e=this,t=T.a.Children.count(this.props.children)>1?this.formatChildren(this.props.children):this.props.children;return T.a.createElement("div",{className:["slider",this.props.className||""].join(" "),ref:"slider",style:c()({},this.getSliderStyles(),this.props.style)},T.a.createElement("div",c()({className:"slider-frame",ref:"frame",style:this.getFrameStyles()},this.getTouchEvents(),this.getMouseEvents(),{onClick:this.handleClick}),T.a.createElement("ul",{className:"slider-list",ref:"list",style:this.getListStyles()},t)),this.props.decorators?this.props.decorators.map(function(t,n){return T.a.createElement("div",{style:c()({},e.getDecoratorStyles(t.position),t.style||{}),className:"slider-decorator-"+n,key:n},T.a.createElement(t.component,{currentSlide:e.state.currentSlide,slideCount:e.state.slideCount,frameWidth:e.state.frameWidth,slideWidth:e.state.slideWidth,slidesToScroll:e.state.slidesToScroll,cellSpacing:e.props.cellSpacing,slidesToShow:e.props.slidesToShow,wrapAround:e.props.wrapAround,nextSlide:e.nextSlide,previousSlide:e.previousSlide,goToSlide:e.goToSlide}))}):null,T.a.createElement("style",{type:"text/css",dangerouslySetInnerHTML:{__html:this.getStyleTagStyles()}}))}},{key:"getTouchEvents",value:function(){var e=this;return!1===this.props.swiping?null:{onTouchStart:function(t){e.touchObject={startX:t.touches[0].pageX,startY:t.touches[0].pageY},e.handleMouseOver()},onTouchMove:function(t){var n=e.swipeDirection(e.touchObject.startX,t.touches[0].pageX,e.touchObject.startY,t.touches[0].pageY);0!==n&&t.preventDefault();var i=e.props.vertical?Math.round(Math.sqrt(Math.pow(t.touches[0].pageY-e.touchObject.startY,2))):Math.round(Math.sqrt(Math.pow(t.touches[0].pageX-e.touchObject.startX,2)));e.touchObject={startX:e.touchObject.startX,startY:e.touchObject.startY,endX:t.touches[0].pageX,endY:t.touches[0].pageY,length:i,direction:n},e.setState({left:e.props.vertical?0:e.getTargetLeft(e.touchObject.length*e.touchObject.direction),top:e.props.vertical?e.getTargetLeft(e.touchObject.length*e.touchObject.direction):0})},onTouchEnd:function(t){e.handleSwipe(t),e.handleMouseOut()},onTouchCancel:function(t){e.handleSwipe(t)}}}},{key:"getMouseEvents",value:function(){var e=this;return!1===this.props.dragging?null:{onMouseOver:function(){e.handleMouseOver()},onMouseOut:function(){e.handleMouseOut()},onMouseDown:function(t){e.touchObject={startX:t.clientX,startY:t.clientY},e.setState({dragging:!0})},onMouseMove:function(t){if(e.state.dragging){var n=e.swipeDirection(e.touchObject.startX,t.clientX,e.touchObject.startY,t.clientY);0!==n&&t.preventDefault();var i=e.props.vertical?Math.round(Math.sqrt(Math.pow(t.clientY-e.touchObject.startY,2))):Math.round(Math.sqrt(Math.pow(t.clientX-e.touchObject.startX,2)));e.touchObject={startX:e.touchObject.startX,startY:e.touchObject.startY,endX:t.clientX,endY:t.clientY,length:i,direction:n},e.setState({left:e.props.vertical?0:e.getTargetLeft(e.touchObject.length*e.touchObject.direction),top:e.props.vertical?e.getTargetLeft(e.touchObject.length*e.touchObject.direction):0})}},onMouseUp:function(t){e.state.dragging&&e.handleSwipe(t)},onMouseLeave:function(t){e.state.dragging&&e.handleSwipe(t)}}}},{key:"handleMouseOver",value:function(){this.props.autoplay&&(this.autoplayPaused=!0,this.stopAutoplay())}},{key:"handleMouseOut",value:function(){this.props.autoplay&&this.autoplayPaused&&(this.startAutoplay(),this.autoplayPaused=null)}},{key:"handleSwipe",value:function(e){void 0!==this.touchObject.length&&this.touchObject.length>44?this.clickSafe=!0:this.clickSafe=!1;var t=this.props,n=t.slidesToShow,i=t.slidesToScroll,o=t.swipeSpeed;"auto"===i&&(n=this.state.slidesToScroll),T.a.Children.count(this.props.children)>1&&this.touchObject.length>this.state.slideWidth/n/o?1===this.touchObject.direction?this.state.currentSlide>=T.a.Children.count(this.props.children)-n&&!this.props.wrapAround?this.animateSlide(this.props.edgeEasing):this.nextSlide():-1===this.touchObject.direction&&(this.state.currentSlide<=0&&!this.props.wrapAround?this.animateSlide(this.props.edgeEasing):this.previousSlide()):this.goToSlide(this.state.currentSlide),this.touchObject={},this.setState({dragging:!1})}},{key:"swipeDirection",value:function(e,t,n,i){var o=e-t,a=n-i,r=Math.atan2(a,o),s=Math.round(180*r/Math.PI);return s<0&&(s=360-Math.abs(s)),s<=45&&s>=0?1:s<=360&&s>=315?1:s>=135&&s<=225?-1:!0===this.props.vertical?s>=35&&s<=135?1:-1:0}},{key:"startAutoplay",value:function(){T.a.Children.count(this.props.children)<=1||(this.autoplayID=setInterval(this.autoplayIterator,this.props.autoplayInterval))}},{key:"resetAutoplay",value:function(){this.props.resetAutoplay&&this.props.autoplay&&!this.autoplayPaused&&(this.stopAutoplay(),this.startAutoplay())}},{key:"stopAutoplay",value:function(){this.autoplayID&&clearInterval(this.autoplayID)}},{key:"animateSlide",value:function(e,t,n,i){this.tweenState(this.props.vertical?"top":"left",{easing:e||this.props.easing,duration:t||this.props.speed,endValue:n||this.getTargetLeft(),delay:null,beginValue:null,onEnd:i||null,stackBehavior:L})}},{key:"getTargetLeft",value:function(e,t){var n=void 0,i=t||this.state.currentSlide,o=this.props.cellSpacing;switch(this.props.cellAlign){case"left":n=0,n-=o*i;break;case"center":n=(this.state.frameWidth-this.state.slideWidth)/2,n-=o*i;break;case"right":n=this.state.frameWidth-this.state.slideWidth,n-=o*i}var a=this.state.slideWidth*i;return this.state.currentSlide>0&&i+this.state.slidesToScroll>=this.state.slideCount&&1!==this.props.slideWidth&&!this.props.wrapAround&&"auto"===this.props.slidesToScroll&&(a=this.state.slideWidth*this.state.slideCount-this.state.frameWidth,n=0,n-=o*(this.state.slideCount-1)),n-=e||0,-1*(a-n)}},{key:"bindEvents",value:function(){C.a.canUseDOM&&(N(window,"resize",this.onResize),N(document,"readystatechange",this.onReadyStateChange))}},{key:"unbindEvents",value:function(){C.a.canUseDOM&&(W(window,"resize",this.onResize),W(document,"readystatechange",this.onReadyStateChange))}},{key:"formatChildren",value:function(e){var t=this,n=this.props.vertical?this.getTweeningValue("top"):this.getTweeningValue("left");return T.a.Children.map(e,function(e,i){return T.a.createElement("li",{className:"slider-slide",style:t.getSlideStyles(i,n),key:i},e)})}},{key:"setInitialDimensions",value:function(){var e=this,t=this.props,n=t.vertical,i=t.initialSlideHeight,o=t.initialSlideWidth,a=t.slidesToShow,r=t.cellSpacing,s=t.children,l=n?i||0:o||0,c=i?i*a:0,u=c+r*(a-1);this.setState({slideHeight:c,frameWidth:n?u:"100%",slideCount:T.a.Children.count(s),slideWidth:l},function(){e.setLeft(),e.setExternalData()})}},{key:"setDimensions",value:function(e){var t=this;e=e||this.props;var n=void 0,i=void 0,o=void 0,a=void 0,r=e.slidesToScroll,s=this.refs.frame,l=s.childNodes[0].childNodes[0];l?(l.style.height="auto",o=this.props.vertical?l.offsetHeight*e.slidesToShow:l.offsetHeight):o=100,a="number"!=typeof e.slideWidth?parseInt(e.slideWidth,10):e.vertical?o/e.slidesToShow*e.slideWidth:s.offsetWidth/e.slidesToShow*e.slideWidth,e.vertical||(a-=e.cellSpacing*((100-100/e.slidesToShow)/100)),i=o+e.cellSpacing*(e.slidesToShow-1),n=e.vertical?i:s.offsetWidth,"auto"===e.slidesToScroll&&(r=Math.floor(n/(a+e.cellSpacing))),this.setState({slideHeight:o,frameWidth:n,slideWidth:a,slidesToScroll:r,left:e.vertical?0:this.getTargetLeft(),top:e.vertical?this.getTargetLeft():0},function(){t.setLeft()})}},{key:"setLeft",value:function(){this.setState({left:this.props.vertical?0:this.getTargetLeft(),top:this.props.vertical?this.getTargetLeft():0})}},{key:"setExternalData",value:function(){this.props.data&&this.props.data()}},{key:"getListStyles",value:function(){var e=this.state.slideWidth*T.a.Children.count(this.props.children),t=this.props.cellSpacing,n=t*T.a.Children.count(this.props.children),i="translate3d("+this.getTweeningValue("left")+"px, "+this.getTweeningValue("top")+"px, 0)";return{transform:i,WebkitTransform:i,msTransform:"translate("+this.getTweeningValue("left")+"px, "+this.getTweeningValue("top")+"px)",position:"relative",display:"block",margin:this.props.vertical?t/2*-1+"px 0px":"0px "+t/2*-1+"px",padding:0,height:this.props.vertical?e+n:this.state.slideHeight,width:this.props.vertical?"auto":e+n,cursor:!0===this.state.dragging?"pointer":"inherit",boxSizing:"border-box",MozBoxSizing:"border-box"}}},{key:"getFrameStyles",value:function(){return{position:"relative",display:"block",overflow:this.props.frameOverflow,height:this.props.vertical?this.state.frameWidth||"initial":"auto",margin:this.props.framePadding,padding:0,transform:"translate3d(0, 0, 0)",WebkitTransform:"translate3d(0, 0, 0)",msTransform:"translate(0, 0)",boxSizing:"border-box",MozBoxSizing:"border-box"}}},{key:"getSlideStyles",value:function(e,t){var n=this.getSlideTargetPosition(e,t),i=this.props.cellSpacing;return{position:"absolute",left:this.props.vertical?0:n,top:this.props.vertical?n:0,display:this.props.vertical?"block":"inline-block",listStyleType:"none",verticalAlign:"top",width:this.props.vertical?"100%":this.state.slideWidth,height:"auto",boxSizing:"border-box",MozBoxSizing:"border-box",marginLeft:this.props.vertical?"auto":i/2,marginRight:this.props.vertical?"auto":i/2,marginTop:this.props.vertical?i/2:"auto",marginBottom:this.props.vertical?i/2:"auto"}}},{key:"getSlideTargetPosition",value:function(e,t){var n=this.state.frameWidth/this.state.slideWidth,i=(this.state.slideWidth+this.props.cellSpacing)*e,o=(this.state.slideWidth+this.props.cellSpacing)*n*-1;if(this.props.wrapAround){var a=Math.ceil(t/this.state.slideWidth);if(this.state.slideCount-a<=e)return(this.state.slideWidth+this.props.cellSpacing)*(this.state.slideCount-e)*-1;var r=Math.ceil((Math.abs(t)-Math.abs(o))/this.state.slideWidth);if(1!==this.state.slideWidth&&(r=Math.ceil((Math.abs(t)-this.state.slideWidth)/this.state.slideWidth)),e<=r-1)return(this.state.slideWidth+this.props.cellSpacing)*(this.state.slideCount+e)}return i}},{key:"getSliderStyles",value:function(){return{position:"relative",display:"block",width:this.props.width,height:"auto",boxSizing:"border-box",MozBoxSizing:"border-box",visibility:this.state.slideWidth?"visible":"hidden"}}},{key:"getStyleTagStyles",value:function(){return".slider-slide > img {width: 100%; display: block;}"}},{key:"getDecoratorStyles",value:function(e){switch(e){case"TopLeft":return{position:"absolute",top:0,left:0};case"TopCenter":return{position:"absolute",top:0,left:"50%",transform:"translateX(-50%)",WebkitTransform:"translateX(-50%)",msTransform:"translateX(-50%)"};case"TopRight":return{position:"absolute",top:0,right:0};case"CenterLeft":return{position:"absolute",top:"50%",left:0,transform:"translateY(-50%)",WebkitTransform:"translateY(-50%)",msTransform:"translateY(-50%)"};case"CenterCenter":return{position:"absolute",top:"50%",left:"50%",transform:"translate(-50%,-50%)",WebkitTransform:"translate(-50%, -50%)",msTransform:"translate(-50%, -50%)"};case"CenterRight":return{position:"absolute",top:"50%",right:0,transform:"translateY(-50%)",WebkitTransform:"translateY(-50%)",msTransform:"translateY(-50%)"};case"BottomLeft":return{position:"absolute",bottom:0,left:0};case"BottomCenter":return{position:"absolute",bottom:0,width:"100%",textAlign:"center"};case"BottomRight":return{position:"absolute",bottom:0,right:0};default:return{position:"absolute",top:0,left:0}}}}]),t}(T.a.Component);M.defaultProps={afterSlide:function(){},autoplay:!1,resetAutoplay:!0,swipeSpeed:12,autoplayInterval:3e3,beforeSlide:function(){},cellAlign:"left",cellSpacing:0,data:function(){},decorators:E,dragging:!0,easing:i,edgeEasing:o,framePadding:"0px",frameOverflow:"hidden",slideIndex:0,slidesToScroll:1,slidesToShow:1,slideWidth:1,speed:500,swiping:!0,vertical:!1,width:"100%",wrapAround:!1,style:{}};var D=M,I=this&&this.__rest||function(e,t){var n={};for(var i in e)Object.prototype.hasOwnProperty.call(e,i)&&t.indexOf(i)<0&&(n[i]=e[i]);if(null!=e&&"function"==typeof Object.getOwnPropertySymbols)for(var o=0,i=Object.getOwnPropertySymbols(e);o<i.length;o++)t.indexOf(i[o])<0&&(n[i[o]]=e[i[o]]);return n},_=function(e){function t(e){h()(this,t);var n=m()(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return n.onChange=function(e){n.setState({selectedIndex:e},function(){n.props.afterChange&&n.props.afterChange(e)})},n.state={selectedIndex:n.props.selectedIndex},n}return v()(t,e),p()(t,[{key:"render",value:function(){var e=this.props,t=e.infinite,n=e.selectedIndex,i=e.beforeChange,o=(e.afterChange,e.dots),a=I(e,["infinite","selectedIndex","beforeChange","afterChange","dots"]),r=a.prefixCls,l=a.dotActiveStyle,u=a.dotStyle,h=a.className,d=a.vertical,p=c()({},a,{wrapAround:t,slideIndex:n,beforeSlide:i}),f=[];o&&(f=[{component:function(e){for(var t=e.slideCount,n=e.slidesToScroll,i=e.currentSlide,o=[],a=0;a<t;a+=n)o.push(a);var c=o.map(function(e){var t=S()(r+"-wrap-dot",s()({},r+"-wrap-dot-active",e===i)),n=e===i?l:u;return T.a.createElement("div",{className:t,key:e},T.a.createElement("span",{style:n}))});return T.a.createElement("div",{className:r+"-wrap"},c)},position:"BottomCenter"}]);var m=S()(r,h,s()({},r+"-vertical",d));return T.a.createElement(D,c()({},p,{className:m,decorators:f,afterSlide:this.onChange}))}}]),t}(T.a.Component),j=_;_.defaultProps={prefixCls:"am-carousel",dots:!0,arrows:!1,autoplay:!1,infinite:!1,cellAlign:"center",selectedIndex:0,dotStyle:{},dotActiveStyle:{}};var A=n("ng98"),B=n.n(A),X=n("6osJ"),P=n.n(X),Y=n("iups"),R=n.n(Y),V=n("/O8+"),H=n.n(V),U=n("NX1q"),z=n.n(U),q=n("XWAK"),K=(n("KmIg"),n("N158")),Q=(n("0xDb"),n("UnHS"),function(e){function t(){return B()(this,t),H()(this,z()(t).apply(this,arguments))}return R()(t,[{key:"componentDidMount",value:function(){this.props.dispatch({type:"home/getBannerList"})}},{key:"render",value:function(){var e=this.props.home,t=e.homeItem,n=e.bannerData,i=e.homeLoading,o=e.refreshLoading,a=this.props.loading,r=0!=n.length&&n.map(function(e,t){return T.a.createElement("div",{className:"imgSlider",key:t,onClick:function(){window.location.href=e.jump_url}},T.a.createElement("img",{src:e.banner_pic}))});return T.a.createElement("div",{className:"tabsBox ".concat(i?"":"fadeIn animated")},T.a.createElement("div",{style:{height:"4.2rem",overflow:"hidden"}},a.effects["users/toRewardDetails"]||o?null:T.a.createElement(j,{className:"myCarousel",dots:!1,swipeSpeed:22,autoplay:n.length>1,infinite:n.length>1,dots:n.length>1},r||T.a.createElement("div",{style:{height:"4.2rem"}}))),T.a.createElement("div",{className:"listBox listTab2",style:{marginTop:"0.2rem"}},T.a.createElement(K.a,{item:t,routerName:"/detai",type:"\u63a8\u8350"})))}}]),P()(t,e),t}(T.a.Component));t.default=Object(q.connect)(a)(Q)}});