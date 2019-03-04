export default {
  plugins: ['umi-plugin-dva',
    [
      'umi-plugin-routes',
      {
        exclude: [
          /model\.(j|t)sx?$/,
          /service\.(j|t)sx?$/,
          /models\//,
          /components\//,
          /services\//
        ],
      },
    ],
  ],
  outputPath: '../dist',
  hashHistory: true,//路由刷新404，关闭后需服务器配置代理强制访问index.html
  exportStatic: false//静态化
}


