webpackJsonp([9],{N158:function(t,e,n){"use strict";function i(t){return{home:t.home}}var a=n("dN2m"),o=n.n(a),s=n("XWAK"),r=(n.n(s),n("KmIg")),l=(n.n(r),function(t){var e=t.item,n=(t.type,t.dispatch),i=t.home,a=function(t,e){n(r.routerRedux.push({pathname:e,query:{id:t,in_type:1}}))};return o.a.createElement("div",null,o.a.createElement("ul",{style:{background:e&&0==e.length?"none":"#fff"}},e&&e.map(function(t,e){return o.a.createElement("li",{key:e,onClick:function(){return a(2==t.up_type?t.course_id:t.item_id,2==t.up_type?"/detai/list":"/detai")}},o.a.createElement("div",{className:"left"},o.a.createElement("img",{src:t.course_pic})),o.a.createElement("div",{className:"right"},o.a.createElement("h3",{className:"textOversTab2"},2==t.up_type?o.a.createElement("span",{className:"color1"},"\u8fde\u8f7d"):o.a.createElement("span",{className:"color2"},"\u5355\u96c6"),t.course_title),o.a.createElement("p",{className:"textOversTab2"},t.course_brief||"\u6682\u65e0\u4ecb\u7ecd"),o.a.createElement("p",null,o.a.createElement("span",{className:"price",style:{float:"left",fontSize:"0.35rem"}},t.author)),o.a.createElement("div",{className:"tags"},o.a.createElement("span",null,t.play_num>1e4?(t.play_num-t.play_num%1e3)/1e4+"W":t.play_num||0),o.a.createElement("span",null,t.comment_count>1e4?(t.comment_count-t.comment_count%1e3)/1e4+"W":t.comment_count),o.a.createElement("span",{className:"price"},Number(t.total_amount)?"\uffe5"+t.total_amount||0:null))))}),e&&0==e.length&&!i.homeLoading?o.a.createElement("div",{className:"lengthIcon"}):null),o.a.createElement("div",{className:"moreStyle"},0!=e.length?i.homeLength?i.refreshLoading?"\u6b63\u5728\u52a0\u8f7d":"\u52a0\u8f7d\u66f4\u591a":"\u6ca1\u6709\u66f4\u591a\u4e86":""))});e.a=Object(s.connect)(i)(l)},NAMZ:function(t,e){},UnHS:function(t,e){},XbKq:function(t,e,n){var i;!function(){"use strict";var a=!("undefined"==typeof window||!window.document||!window.document.createElement),o={canUseDOM:a,canUseWorkers:"undefined"!=typeof Worker,canUseEventListeners:a&&!(!window.addEventListener&&!window.attachEvent),canUseViewport:a&&!!window.screen};void 0!==(i=function(){return o}.call(e,n,e,t))&&(t.exports=i)}()},YAjU:function(t,e,n){(function(e){(function(){var n,i,a,o,s,r;"undefined"!=typeof performance&&null!==performance&&performance.now?t.exports=function(){return performance.now()}:void 0!==e&&null!==e&&e.hrtime?(t.exports=function(){return(n()-s)/1e6},i=e.hrtime,n=function(){var t;return t=i(),1e9*t[0]+t[1]},o=n(),r=1e9*e.uptime(),s=o-r):Date.now?(t.exports=function(){return Date.now()-a},a=Date.now()):(t.exports=function(){return(new Date).getTime()-a},a=(new Date).getTime())}).call(this)}).call(e,n("Wo/5"))},amyL:function(t,e,n){(function(e){for(var i=n("YAjU"),a="undefined"==typeof window?e:window,o=["moz","webkit"],s="AnimationFrame",r=a["request"+s],l=a["cancel"+s]||a["cancelRequest"+s],c=0;!r&&c<o.length;c++)r=a[o[c]+"Request"+s],l=a[o[c]+"Cancel"+s]||a[o[c]+"CancelRequest"+s];if(!r||!l){var u=0,d=0,p=[];r=function(t){if(0===p.length){var e=i(),n=Math.max(0,1e3/60-(e-u));u=n+e,setTimeout(function(){var t=p.slice(0);p.length=0;for(var e=0;e<t.length;e++)if(!t[e].cancelled)try{t[e].callback(u)}catch(t){setTimeout(function(){throw t},0)}},Math.round(n))}return p.push({handle:++d,callback:t,cancelled:!1}),d},l=function(t){for(var e=0;e<p.length;e++)p[e].handle===t&&(p[e].cancelled=!0)}}t.exports=function(t){return r.call(a,t)},t.exports.cancel=function(){l.apply(a,arguments)},t.exports.polyfill=function(t){t||(t=a),t.requestAnimationFrame=r,t.cancelAnimationFrame=l}}).call(e,n("u28U"))},ktQX:function(t,e,n){"use strict";function i(t,e,n,i){return(n-e)*Math.sqrt(1-(t=t/i-1)*t)+e}function a(t,e,n,i){return(n-e)*t/i+e}function o(t){return{home:t.home,loading:t.loading}}Object.defineProperty(e,"__esModule",{value:!0});var s=(n("99xQ"),n("NAMZ"),n("WxFH")),r=n.n(s),l=n("34v0"),c=n.n(l),u=n("dbJu"),d=n.n(u),p=n("HFN4"),h=n.n(p),f=n("Lxvp"),g=n.n(f),m=n("i9+B"),v=n.n(m),S=n("lIaQ"),y=n.n(S),b=n("dN2m"),w=n.n(b),T=[{component:function(t){function e(){d()(this,e);var t=g()(this,(e.__proto__||Object.getPrototypeOf(e)).apply(this,arguments));return t.handleClick=function(e){e.preventDefault(),t.props.previousSlide()},t}return v()(e,t),h()(e,[{key:"render",value:function(){return w.a.createElement("button",{style:this.getButtonStyles(0===this.props.currentSlide&&!this.props.wrapAround),onClick:this.handleClick},"PREV")}},{key:"getButtonStyles",value:function(t){return{border:0,background:"rgba(0,0,0,0.4)",color:"white",padding:10,outline:0,opacity:t?.3:1,cursor:"pointer"}}}]),e}(w.a.Component),position:"CenterLeft"},{component:function(t){function e(){d()(this,e);var t=g()(this,(e.__proto__||Object.getPrototypeOf(e)).apply(this,arguments));return t.handleClick=function(e){e.preventDefault(),t.props.nextSlide&&t.props.nextSlide()},t}return v()(e,t),h()(e,[{key:"render",value:function(){return w.a.createElement("button",{style:this.getButtonStyles(this.props.currentSlide+this.props.slidesToScroll>=this.props.slideCount&&!this.props.wrapAround),onClick:this.handleClick},"NEXT")}},{key:"getButtonStyles",value:function(t){return{border:0,background:"rgba(0,0,0,0.4)",color:"white",padding:10,outline:0,opacity:t?.3:1,cursor:"pointer"}}}]),e}(w.a.Component),position:"CenterRight"},{component:function(t){function e(){return d()(this,e),g()(this,(e.__proto__||Object.getPrototypeOf(e)).apply(this,arguments))}return v()(e,t),h()(e,[{key:"render",value:function(){var t=this,e=this.getIndexes(this.props.slideCount,this.props.slidesToScroll);return w.a.createElement("ul",{style:this.getListStyles()},e.map(function(e){return w.a.createElement("li",{style:t.getListItemStyles(),key:e},w.a.createElement("button",{style:t.getButtonStyles(t.props.currentSlide===e),onClick:t.props.goToSlide&&t.props.goToSlide.bind(null,e)},"\u2022"))}))}},{key:"getIndexes",value:function(t,e){for(var n=[],i=0;i<t;i+=e)n.push(i);return n}},{key:"getListStyles",value:function(){return{position:"relative",margin:0,top:-10,padding:0}}},{key:"getListItemStyles",value:function(){return{listStyleType:"none",display:"inline-block"}}},{key:"getButtonStyles",value:function(t){return{border:0,background:"transparent",color:"black",cursor:"pointer",padding:10,outline:0,fontSize:24,opacity:t?1:.5}}}]),e}(w.a.Component),position:"BottomCenter"}],k=T,E=n("XbKq"),x=n.n(E),C=n("amyL"),O=n.n(C),W={ADDITIVE:"ADDITIVE",DESTRUCTIVE:"DESTRUCTIVE"},M=function(t,e,n){null!==t&&void 0!==t&&(t.addEventListener?t.addEventListener(e,n,!1):t.attachEvent?t.attachEvent("on"+e,n):t["on"+e]=n)},D=function(t,e,n){null!==t&&void 0!==t&&(t.removeEventListener?t.removeEventListener(e,n,!1):t.detachEvent?t.detachEvent("on"+e,n):t["on"+e]=null)},_=function(t){function e(t){d()(this,e);var n=g()(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t));return n._rafCb=function(){var t=n.state;if(0!==t.tweenQueue.length){for(var e=Date.now(),i=[],a=0;a<t.tweenQueue.length;a++){var o=t.tweenQueue[a],s=o.initTime,r=o.config;e-s<r.duration?i.push(o):r.onEnd&&r.onEnd()}-1!==n._rafID&&(n.setState({tweenQueue:i}),n._rafID=O()(n._rafCb))}},n.handleClick=function(t){!0===n.clickSafe&&(t.preventDefault(),t.stopPropagation(),t.nativeEvent&&t.nativeEvent.stopPropagation())},n.autoplayIterator=function(){if(n.props.wrapAround)return n.nextSlide();n.state.currentSlide!==n.state.slideCount-n.state.slidesToShow?n.nextSlide():n.stopAutoplay()},n.goToSlide=function(t){var e=n.props,i=e.beforeSlide,a=e.afterSlide;if(t>=w.a.Children.count(n.props.children)||t<0){if(!n.props.wrapAround)return;if(t>=w.a.Children.count(n.props.children))return i(n.state.currentSlide,0),n.setState({currentSlide:0},function(){n.animateSlide(null,null,n.getTargetLeft(null,t),function(){n.animateSlide(null,.01),a(0),n.resetAutoplay(),n.setExternalData()})});var o=w.a.Children.count(n.props.children)-n.state.slidesToScroll;return i(n.state.currentSlide,o),n.setState({currentSlide:o},function(){n.animateSlide(null,null,n.getTargetLeft(null,t),function(){n.animateSlide(null,.01),a(o),n.resetAutoplay(),n.setExternalData()})})}i(n.state.currentSlide,t),n.setState({currentSlide:t},function(){n.animateSlide(),n.props.afterSlide(t),n.resetAutoplay(),n.setExternalData()})},n.nextSlide=function(){var t=w.a.Children.count(n.props.children),e=n.props.slidesToShow;if("auto"===n.props.slidesToScroll&&(e=n.state.slidesToScroll),!(n.state.currentSlide>=t-e)||n.props.wrapAround)if(n.props.wrapAround)n.goToSlide(n.state.currentSlide+n.state.slidesToScroll);else{if(1!==n.props.slideWidth)return n.goToSlide(n.state.currentSlide+n.state.slidesToScroll);n.goToSlide(Math.min(n.state.currentSlide+n.state.slidesToScroll,t-e))}},n.previousSlide=function(){n.state.currentSlide<=0&&!n.props.wrapAround||(n.props.wrapAround?n.goToSlide(n.state.currentSlide-n.state.slidesToScroll):n.goToSlide(Math.max(0,n.state.currentSlide-n.state.slidesToScroll)))},n.onResize=function(){n.setDimensions()},n.onReadyStateChange=function(){n.setDimensions()},n.state={currentSlide:n.props.slideIndex,dragging:!1,frameWidth:0,left:0,slideCount:0,slidesToScroll:n.props.slidesToScroll,slideWidth:0,top:0,tweenQueue:[]},n.touchObject={},n.clickSafe=!0,n}return v()(e,t),h()(e,[{key:"componentWillMount",value:function(){this.setInitialDimensions()}},{key:"componentDidMount",value:function(){this.setDimensions(),this.bindEvents(),this.setExternalData(),this.props.autoplay&&this.startAutoplay()}},{key:"componentWillReceiveProps",value:function(t){this.setState({slideCount:t.children.length}),this.setDimensions(t),this.props.slideIndex!==t.slideIndex&&t.slideIndex!==this.state.currentSlide&&this.goToSlide(t.slideIndex),this.props.autoplay!==t.autoplay&&(t.autoplay?this.startAutoplay():this.stopAutoplay())}},{key:"componentWillUnmount",value:function(){this.unbindEvents(),this.stopAutoplay(),O.a.cancel(this._rafID),this._rafID=-1}},{key:"tweenState",value:function(t,e){var n=this,i=e.easing,a=e.duration,o=e.delay,s=e.beginValue,r=e.endValue,l=e.onEnd,c=e.stackBehavior;this.setState(function(e){var u=e,d=void 0,p=void 0;if("string"==typeof t)d=t,p=t;else{for(var h=0;h<t.length-1;h++)u=u[t[h]];d=t[t.length-1],p=t.join("|")}var f={easing:i,duration:null==a?300:a,delay:null==o?0:o,beginValue:null==s?u[d]:s,endValue:r,onEnd:l,stackBehavior:c||"ADDITIVE"},g=e.tweenQueue;return f.stackBehavior===W.DESTRUCTIVE&&(g=e.tweenQueue.filter(function(t){return t.pathHash!==p})),g.push({pathHash:p,config:f,initTime:Date.now()+f.delay}),u[d]=f.endValue,1===g.length&&(n._rafID=O()(n._rafCb)),{tweenQueue:g}})}},{key:"getTweeningValue",value:function(t){var e=this.state,n=void 0,i=void 0;if("string"==typeof t)n=e[t],i=t;else{n=e;for(var a=0;a<t.length;a++)n=n[t[a]];i=t.join("|")}for(var o=Date.now(),s=0;s<e.tweenQueue.length;s++){var r=e.tweenQueue[s],l=r.pathHash,c=r.initTime,u=r.config;if(l===i){var d=o-c>u.duration?u.duration:Math.max(0,o-c);n+=(0===u.duration?u.endValue:u.easing(d,u.beginValue,u.endValue,u.duration))-u.endValue}}return n}},{key:"render",value:function(){var t=this,e=w.a.Children.count(this.props.children)>1?this.formatChildren(this.props.children):this.props.children;return w.a.createElement("div",{className:["slider",this.props.className||""].join(" "),ref:"slider",style:c()({},this.getSliderStyles(),this.props.style)},w.a.createElement("div",c()({className:"slider-frame",ref:"frame",style:this.getFrameStyles()},this.getTouchEvents(),this.getMouseEvents(),{onClick:this.handleClick}),w.a.createElement("ul",{className:"slider-list",ref:"list",style:this.getListStyles()},e)),this.props.decorators?this.props.decorators.map(function(e,n){return w.a.createElement("div",{style:c()({},t.getDecoratorStyles(e.position),e.style||{}),className:"slider-decorator-"+n,key:n},w.a.createElement(e.component,{currentSlide:t.state.currentSlide,slideCount:t.state.slideCount,frameWidth:t.state.frameWidth,slideWidth:t.state.slideWidth,slidesToScroll:t.state.slidesToScroll,cellSpacing:t.props.cellSpacing,slidesToShow:t.props.slidesToShow,wrapAround:t.props.wrapAround,nextSlide:t.nextSlide,previousSlide:t.previousSlide,goToSlide:t.goToSlide}))}):null,w.a.createElement("style",{type:"text/css",dangerouslySetInnerHTML:{__html:this.getStyleTagStyles()}}))}},{key:"getTouchEvents",value:function(){var t=this;return!1===this.props.swiping?null:{onTouchStart:function(e){t.touchObject={startX:e.touches[0].pageX,startY:e.touches[0].pageY},t.handleMouseOver()},onTouchMove:function(e){var n=t.swipeDirection(t.touchObject.startX,e.touches[0].pageX,t.touchObject.startY,e.touches[0].pageY);0!==n&&e.preventDefault();var i=t.props.vertical?Math.round(Math.sqrt(Math.pow(e.touches[0].pageY-t.touchObject.startY,2))):Math.round(Math.sqrt(Math.pow(e.touches[0].pageX-t.touchObject.startX,2)));t.touchObject={startX:t.touchObject.startX,startY:t.touchObject.startY,endX:e.touches[0].pageX,endY:e.touches[0].pageY,length:i,direction:n},t.setState({left:t.props.vertical?0:t.getTargetLeft(t.touchObject.length*t.touchObject.direction),top:t.props.vertical?t.getTargetLeft(t.touchObject.length*t.touchObject.direction):0})},onTouchEnd:function(e){t.handleSwipe(e),t.handleMouseOut()},onTouchCancel:function(e){t.handleSwipe(e)}}}},{key:"getMouseEvents",value:function(){var t=this;return!1===this.props.dragging?null:{onMouseOver:function(){t.handleMouseOver()},onMouseOut:function(){t.handleMouseOut()},onMouseDown:function(e){t.touchObject={startX:e.clientX,startY:e.clientY},t.setState({dragging:!0})},onMouseMove:function(e){if(t.state.dragging){var n=t.swipeDirection(t.touchObject.startX,e.clientX,t.touchObject.startY,e.clientY);0!==n&&e.preventDefault();var i=t.props.vertical?Math.round(Math.sqrt(Math.pow(e.clientY-t.touchObject.startY,2))):Math.round(Math.sqrt(Math.pow(e.clientX-t.touchObject.startX,2)));t.touchObject={startX:t.touchObject.startX,startY:t.touchObject.startY,endX:e.clientX,endY:e.clientY,length:i,direction:n},t.setState({left:t.props.vertical?0:t.getTargetLeft(t.touchObject.length*t.touchObject.direction),top:t.props.vertical?t.getTargetLeft(t.touchObject.length*t.touchObject.direction):0})}},onMouseUp:function(e){t.state.dragging&&t.handleSwipe(e)},onMouseLeave:function(e){t.state.dragging&&t.handleSwipe(e)}}}},{key:"handleMouseOver",value:function(){this.props.autoplay&&(this.autoplayPaused=!0,this.stopAutoplay())}},{key:"handleMouseOut",value:function(){this.props.autoplay&&this.autoplayPaused&&(this.startAutoplay(),this.autoplayPaused=null)}},{key:"handleSwipe",value:function(t){void 0!==this.touchObject.length&&this.touchObject.length>44?this.clickSafe=!0:this.clickSafe=!1;var e=this.props,n=e.slidesToShow,i=e.slidesToScroll,a=e.swipeSpeed;"auto"===i&&(n=this.state.slidesToScroll),w.a.Children.count(this.props.children)>1&&this.touchObject.length>this.state.slideWidth/n/a?1===this.touchObject.direction?this.state.currentSlide>=w.a.Children.count(this.props.children)-n&&!this.props.wrapAround?this.animateSlide(this.props.edgeEasing):this.nextSlide():-1===this.touchObject.direction&&(this.state.currentSlide<=0&&!this.props.wrapAround?this.animateSlide(this.props.edgeEasing):this.previousSlide()):this.goToSlide(this.state.currentSlide),this.touchObject={},this.setState({dragging:!1})}},{key:"swipeDirection",value:function(t,e,n,i){var a=t-e,o=n-i,s=Math.atan2(o,a),r=Math.round(180*s/Math.PI);return r<0&&(r=360-Math.abs(r)),r<=45&&r>=0?1:r<=360&&r>=315?1:r>=135&&r<=225?-1:!0===this.props.vertical?r>=35&&r<=135?1:-1:0}},{key:"startAutoplay",value:function(){w.a.Children.count(this.props.children)<=1||(this.autoplayID=setInterval(this.autoplayIterator,this.props.autoplayInterval))}},{key:"resetAutoplay",value:function(){this.props.resetAutoplay&&this.props.autoplay&&!this.autoplayPaused&&(this.stopAutoplay(),this.startAutoplay())}},{key:"stopAutoplay",value:function(){this.autoplayID&&clearInterval(this.autoplayID)}},{key:"animateSlide",value:function(t,e,n,i){this.tweenState(this.props.vertical?"top":"left",{easing:t||this.props.easing,duration:e||this.props.speed,endValue:n||this.getTargetLeft(),delay:null,beginValue:null,onEnd:i||null,stackBehavior:W})}},{key:"getTargetLeft",value:function(t,e){var n=void 0,i=e||this.state.currentSlide,a=this.props.cellSpacing;switch(this.props.cellAlign){case"left":n=0,n-=a*i;break;case"center":n=(this.state.frameWidth-this.state.slideWidth)/2,n-=a*i;break;case"right":n=this.state.frameWidth-this.state.slideWidth,n-=a*i}var o=this.state.slideWidth*i;return this.state.currentSlide>0&&i+this.state.slidesToScroll>=this.state.slideCount&&1!==this.props.slideWidth&&!this.props.wrapAround&&"auto"===this.props.slidesToScroll&&(o=this.state.slideWidth*this.state.slideCount-this.state.frameWidth,n=0,n-=a*(this.state.slideCount-1)),n-=t||0,-1*(o-n)}},{key:"bindEvents",value:function(){x.a.canUseDOM&&(M(window,"resize",this.onResize),M(document,"readystatechange",this.onReadyStateChange))}},{key:"unbindEvents",value:function(){x.a.canUseDOM&&(D(window,"resize",this.onResize),D(document,"readystatechange",this.onReadyStateChange))}},{key:"formatChildren",value:function(t){var e=this,n=this.props.vertical?this.getTweeningValue("top"):this.getTweeningValue("left");return w.a.Children.map(t,function(t,i){return w.a.createElement("li",{className:"slider-slide",style:e.getSlideStyles(i,n),key:i},t)})}},{key:"setInitialDimensions",value:function(){var t=this,e=this.props,n=e.vertical,i=e.initialSlideHeight,a=e.initialSlideWidth,o=e.slidesToShow,s=e.cellSpacing,r=e.children,l=n?i||0:a||0,c=i?i*o:0,u=c+s*(o-1);this.setState({slideHeight:c,frameWidth:n?u:"100%",slideCount:w.a.Children.count(r),slideWidth:l},function(){t.setLeft(),t.setExternalData()})}},{key:"setDimensions",value:function(t){var e=this;t=t||this.props;var n=void 0,i=void 0,a=void 0,o=void 0,s=t.slidesToScroll,r=this.refs.frame,l=r.childNodes[0].childNodes[0];l?(l.style.height="auto",a=this.props.vertical?l.offsetHeight*t.slidesToShow:l.offsetHeight):a=100,o="number"!=typeof t.slideWidth?parseInt(t.slideWidth,10):t.vertical?a/t.slidesToShow*t.slideWidth:r.offsetWidth/t.slidesToShow*t.slideWidth,t.vertical||(o-=t.cellSpacing*((100-100/t.slidesToShow)/100)),i=a+t.cellSpacing*(t.slidesToShow-1),n=t.vertical?i:r.offsetWidth,"auto"===t.slidesToScroll&&(s=Math.floor(n/(o+t.cellSpacing))),this.setState({slideHeight:a,frameWidth:n,slideWidth:o,slidesToScroll:s,left:t.vertical?0:this.getTargetLeft(),top:t.vertical?this.getTargetLeft():0},function(){e.setLeft()})}},{key:"setLeft",value:function(){this.setState({left:this.props.vertical?0:this.getTargetLeft(),top:this.props.vertical?this.getTargetLeft():0})}},{key:"setExternalData",value:function(){this.props.data&&this.props.data()}},{key:"getListStyles",value:function(){var t=this.state.slideWidth*w.a.Children.count(this.props.children),e=this.props.cellSpacing,n=e*w.a.Children.count(this.props.children),i="translate3d("+this.getTweeningValue("left")+"px, "+this.getTweeningValue("top")+"px, 0)";return{transform:i,WebkitTransform:i,msTransform:"translate("+this.getTweeningValue("left")+"px, "+this.getTweeningValue("top")+"px)",position:"relative",display:"block",margin:this.props.vertical?e/2*-1+"px 0px":"0px "+e/2*-1+"px",padding:0,height:this.props.vertical?t+n:this.state.slideHeight,width:this.props.vertical?"auto":t+n,cursor:!0===this.state.dragging?"pointer":"inherit",boxSizing:"border-box",MozBoxSizing:"border-box"}}},{key:"getFrameStyles",value:function(){return{position:"relative",display:"block",overflow:this.props.frameOverflow,height:this.props.vertical?this.state.frameWidth||"initial":"auto",margin:this.props.framePadding,padding:0,transform:"translate3d(0, 0, 0)",WebkitTransform:"translate3d(0, 0, 0)",msTransform:"translate(0, 0)",boxSizing:"border-box",MozBoxSizing:"border-box"}}},{key:"getSlideStyles",value:function(t,e){var n=this.getSlideTargetPosition(t,e),i=this.props.cellSpacing;return{position:"absolute",left:this.props.vertical?0:n,top:this.props.vertical?n:0,display:this.props.vertical?"block":"inline-block",listStyleType:"none",verticalAlign:"top",width:this.props.vertical?"100%":this.state.slideWidth,height:"auto",boxSizing:"border-box",MozBoxSizing:"border-box",marginLeft:this.props.vertical?"auto":i/2,marginRight:this.props.vertical?"auto":i/2,marginTop:this.props.vertical?i/2:"auto",marginBottom:this.props.vertical?i/2:"auto"}}},{key:"getSlideTargetPosition",value:function(t,e){var n=this.state.frameWidth/this.state.slideWidth,i=(this.state.slideWidth+this.props.cellSpacing)*t,a=(this.state.slideWidth+this.props.cellSpacing)*n*-1;if(this.props.wrapAround){var o=Math.ceil(e/this.state.slideWidth);if(this.state.slideCount-o<=t)return(this.state.slideWidth+this.props.cellSpacing)*(this.state.slideCount-t)*-1;var s=Math.ceil((Math.abs(e)-Math.abs(a))/this.state.slideWidth);if(1!==this.state.slideWidth&&(s=Math.ceil((Math.abs(e)-this.state.slideWidth)/this.state.slideWidth)),t<=s-1)return(this.state.slideWidth+this.props.cellSpacing)*(this.state.slideCount+t)}return i}},{key:"getSliderStyles",value:function(){return{position:"relative",display:"block",width:this.props.width,height:"auto",boxSizing:"border-box",MozBoxSizing:"border-box",visibility:this.state.slideWidth?"visible":"hidden"}}},{key:"getStyleTagStyles",value:function(){return".slider-slide > img {width: 100%; display: block;}"}},{key:"getDecoratorStyles",value:function(t){switch(t){case"TopLeft":return{position:"absolute",top:0,left:0};case"TopCenter":return{position:"absolute",top:0,left:"50%",transform:"translateX(-50%)",WebkitTransform:"translateX(-50%)",msTransform:"translateX(-50%)"};case"TopRight":return{position:"absolute",top:0,right:0};case"CenterLeft":return{position:"absolute",top:"50%",left:0,transform:"translateY(-50%)",WebkitTransform:"translateY(-50%)",msTransform:"translateY(-50%)"};case"CenterCenter":return{position:"absolute",top:"50%",left:"50%",transform:"translate(-50%,-50%)",WebkitTransform:"translate(-50%, -50%)",msTransform:"translate(-50%, -50%)"};case"CenterRight":return{position:"absolute",top:"50%",right:0,transform:"translateY(-50%)",WebkitTransform:"translateY(-50%)",msTransform:"translateY(-50%)"};case"BottomLeft":return{position:"absolute",bottom:0,left:0};case"BottomCenter":return{position:"absolute",bottom:0,width:"100%",textAlign:"center"};case"BottomRight":return{position:"absolute",bottom:0,right:0};default:return{position:"absolute",top:0,left:0}}}}]),e}(w.a.Component);_.defaultProps={afterSlide:function(){},autoplay:!1,resetAutoplay:!0,swipeSpeed:12,autoplayInterval:3e3,beforeSlide:function(){},cellAlign:"left",cellSpacing:0,data:function(){},decorators:k,dragging:!0,easing:i,edgeEasing:a,framePadding:"0px",frameOverflow:"hidden",slideIndex:0,slidesToScroll:1,slidesToShow:1,slideWidth:1,speed:500,swiping:!0,vertical:!1,width:"100%",wrapAround:!1,style:{}};var A=_,I=this&&this.__rest||function(t,e){var n={};for(var i in t)Object.prototype.hasOwnProperty.call(t,i)&&e.indexOf(i)<0&&(n[i]=t[i]);if(null!=t&&"function"==typeof Object.getOwnPropertySymbols)for(var a=0,i=Object.getOwnPropertySymbols(t);a<i.length;a++)e.indexOf(i[a])<0&&(n[i[a]]=t[i[a]]);return n},j=function(t){function e(t){d()(this,e);var n=g()(this,(e.__proto__||Object.getPrototypeOf(e)).call(this,t));return n.onChange=function(t){n.setState({selectedIndex:t},function(){n.props.afterChange&&n.props.afterChange(t)})},n.state={selectedIndex:n.props.selectedIndex},n}return v()(e,t),h()(e,[{key:"render",value:function(){var t=this.props,e=t.infinite,n=t.selectedIndex,i=t.beforeChange,a=(t.afterChange,t.dots),o=I(t,["infinite","selectedIndex","beforeChange","afterChange","dots"]),s=o.prefixCls,l=o.dotActiveStyle,u=o.dotStyle,d=o.className,p=o.vertical,h=c()({},o,{wrapAround:e,slideIndex:n,beforeSlide:i}),f=[];a&&(f=[{component:function(t){for(var e=t.slideCount,n=t.slidesToScroll,i=t.currentSlide,a=[],o=0;o<e;o+=n)a.push(o);var c=a.map(function(t){var e=y()(s+"-wrap-dot",r()({},s+"-wrap-dot-active",t===i)),n=t===i?l:u;return w.a.createElement("div",{className:e,key:t},w.a.createElement("span",{style:n}))});return w.a.createElement("div",{className:s+"-wrap"},c)},position:"BottomCenter"}]);var g=y()(s,d,r()({},s+"-vertical",p));return w.a.createElement(A,c()({},h,{className:g,decorators:f,afterSlide:this.onChange}))}}]),e}(w.a.Component),L=j;j.defaultProps={prefixCls:"am-carousel",dots:!0,arrows:!1,autoplay:!1,infinite:!1,cellAlign:"center",selectedIndex:0,dotStyle:{},dotActiveStyle:{}};var N=n("ng98"),X=n.n(N),Y=n("6osJ"),V=n.n(Y),B=n("iups"),P=n.n(B),R=n("/O8+"),z=n.n(R),U=n("NX1q"),q=n.n(U),H=n("XWAK"),Q=(n("KmIg"),n("N158")),F=(n("0xDb"),n("UnHS"),function(t){function e(){return X()(this,e),z()(this,q()(e).apply(this,arguments))}return P()(e,[{key:"componentDidMount",value:function(){this.props.dispatch({type:"home/getBannerList"})}},{key:"render",value:function(){var t=this.props.home,e=t.homeItem,n=t.bannerData,i=t.homeLoading,a=t.refreshLoading,o=this.props.loading,s=0!=n.length&&n.map(function(t,e){return w.a.createElement("div",{className:"imgSlider",key:e,onClick:function(){window.location.href=t.jump_url}},w.a.createElement("img",{src:t.banner_pic}))});return w.a.createElement("div",{className:"tabsBox ".concat(i?"":"fadeIn animated")},w.a.createElement("div",{style:{height:"4.2rem",overflow:"hidden"}},o.effects["users/toRewardDetails"]||a?null:w.a.createElement(L,{className:"myCarousel",dots:!1,swipeSpeed:22,autoplay:n.length>1,infinite:n.length>1,dots:n.length>1},s||w.a.createElement("div",{style:{height:"4.2rem"}}))),w.a.createElement("div",{className:"listBox listTab2",style:{marginTop:"0.2rem"}},w.a.createElement(Q.a,{item:e,routerName:"/detai",type:"\u63a8\u8350"})))}}]),V()(e,t),e}(w.a.Component));e.default=Object(H.connect)(o)(F)}});