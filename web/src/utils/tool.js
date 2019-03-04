/*城市格式化*/
const cities = require("./cascader-address-options");
const authaddress = data => {
  let addr = [];
  if (data != undefined) {
    cities.map((s, i) => {
      if (data[0] == s.value) {
        addr.push(s.label);
        s.children.map((k, i) => {
          if (data[1] == k.value) {
            addr.push(k.label);
            k.children.map((t, i) => {
              if (data[2] == t.value) {
                addr.push(t.label);
              }
            });
          }
        });
      }
    });
  }
  return {
    name: addr
  };
};

//判断访问终端
const browser = {
  versions: (function() {
    var u = navigator.userAgent,
      app = navigator.appVersion;
    return {
      trident: u.indexOf("Trident") > -1, //IE内核
      presto: u.indexOf("Presto") > -1, //opera内核
      webKit: u.indexOf("AppleWebKit") > -1, //苹果、谷歌内核
      gecko: u.indexOf("Gecko") > -1 && u.indexOf("KHTML") == -1, //火狐内核
      mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
      ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
      android: u.indexOf("Android") > -1 || u.indexOf("Adr") > -1, //android终端
      iPhone: u.indexOf("iPhone") > -1, //是否为iPhone或者QQHD浏览器
      iPad: u.indexOf("iPad") > -1, //是否iPad
      webApp: u.indexOf("Safari") == -1, //是否web应该程序，没有头部与底部
      weixin: u.indexOf("MicroMessenger") > -1, //是否微信 （2015-01-22新增）
      qq: u.match(/\sQQ/i) == " qq" //是否QQ
    };
  })(),
  language: (navigator.browserLanguage || navigator.language).toLowerCase()
};

const is_weixin = () => {
  var ua = window.navigator.userAgent.toLowerCase();
  if (ua.match(/MicroMessenger/i) == "micromessenger") {
    return true;
  } else {
    return false;
  }
};

/*获取url某个参数值*/
const getQueryString = name => {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
  var r = window.location.search.substr(1).match(reg);
  if (r != null) return unescape(r[2]);
  return null;
};

/*input定位*/
const onFocusInput = e => {
  let _view = e.target;
  if (typeof browser != "undefined" && browser.versions.ios) {
    //判断IOS设备
    var u = navigator.userAgent;
    var ver = u.match(/OS (\d+)_(\d+)_?(\d+)?/);
    ver = parseInt(ver[1], 10);
    if (ver > 10) {
      //ios11 不需要做任何的处理
      setTimeout(function() {
        _view.scrollIntoView(true);
      }, 200);
    } else {
      setTimeout(function() {
        _view.scrollIntoViewIfNeeded(true);
        document.activeElement.scrollIntoViewIfNeeded();
      }, 200);
    }
  }
  if (typeof browser != "undefined" && browser.versions.android) {
    //判断IOS设备
    _view.scrollIntoView(true);
  }
};

/**
 * 根据base64 内容 取得 bolb
 *
 * @param dataURI
 * @returns Blob
 */

const getBlobBydataURI = (dataURI, type) => {
  var binary = atob(dataURI.split(",")[1]);
  var array = [];
  for (var i = 0; i < binary.length; i++) {
    array.push(binary.charCodeAt(i));
  }
  return new Blob([new Uint8Array(array)], { type: type });
};

//php函数urlencode的js实现方法
var URLEncode = function(clearString) {
  var output = "";
  var x = 0;
  clearString = clearString.toString();
  var regex = /(^[a-zA-Z0-9-_.]*)/;
  while (x < clearString.length) {
    var match = regex.exec(clearString.substr(x));
    if (match != null && match.length > 1 && match[1] != "") {
      output += match[1];
      x += match[1].length;
    } else {
      if (clearString.substr(x, 1) == " ") {
        //原文在此用 clearString[x] == ' ' 做判断, 但ie不支持把字符串当作数组来访问,
        //修改后两种浏览器都可兼容
        output += "+";
      } else {
        var charCode = clearString.charCodeAt(x);
        var hexVal = charCode.toString(16);
        output += "%" + (hexVal.length < 2 ? "0" : "") + hexVal.toUpperCase();
      }
      x++;
    }
  }
  return output;
};

/**
 * param 将要转为URL参数字符串的对象
 * key URL参数字符串的前缀
 * encode true/false 是否进行URL编码,默认为true
 *
 * return URL参数字符串
 */

var jsonToURL = function(param, key, encode) {
  if (param == null) return "";

  var paramStr = "";
  var t = typeof param;
  if (t == "string" || t == "number" || t == "boolean") {
    paramStr +=
      "&" + key + "=" + (encode == null || encode ? URLEncode(param) : param);
  } else {
    for (var i in param) {
      var k =
        key == null
          ? i
          : key + (param instanceof Array ? "[" + i + "]" : "." + i);
      paramStr += jsonToURL(param[i], k, encode);
    }
  }

  return paramStr;
};

var objKeySort = function(arys) {
  //先用Object内置类的keys方法获取要排序对象的属性名，再利用Array原型上的sort方法对获取的属性名进行排序，newkey是一个数组
  var newkey = Object.keys(arys).sort();
  //console.log('newkey='+newkey);
  var newObj = {}; //创建一个新的对象，用于存放排好序的键值对
  for (var i = 0; i < newkey.length; i++) {
    //遍历newkey数组
    newObj[newkey[i]] = arys[newkey[i]];
    //向新创建的对象中按照排好的顺序依次增加键值对
  }
  return newObj; //返回排好序的新对象
};

export {
  authaddress,
  browser,
  is_weixin,
  getQueryString,
  onFocusInput,
  getBlobBydataURI,
  objKeySort,
  jsonToURL
};
