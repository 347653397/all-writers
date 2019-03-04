import PxToRem from "postcss-pxtorem";

export default {
  "publicPath": "http://test.startechsoft.cn/dist/static/",
  "theme": "./theme.config.js",
  "extraPostCSSPlugins": [
    PxToRem({
      rootValue: 41,
      propWhiteList: []
    })
  ],
  "proxy": {
    "/api": {
      "target": "http://test.startechsoft.cn",
      "changeOrigin": true,
      "pathRewrite": { "^/api": "" }
    }
  },
}
