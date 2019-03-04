import qs from "qs";
let ZYJAPI = "https://sit.sopay.org/candy";
const reqConfig = {
  baseURL:ZYJAPI,
  headers:{
    'Content-Type':'application/json'
  },
  transformRequest: [
    function(data) {
      // 对 data 进行任意转换处理
      data = qs.stringify(data);
      return data;
    }
  ]
};
export default {
  ZYJAPI,
  reqConfig
};
