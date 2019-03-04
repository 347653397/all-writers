#!/bin/sh

#远程部署机webhook

#拉取最新代码
echo "同步"
git fetch
echo "自动化开始，拉取最新代码--------------"
git pull origin develop
echo "拉取最新完成！-------------------"
#开始构建
# echo "检测node版本-------------------"
# node -v
# echo "安装项目配置文件-------------------"
# npm install
echo "打包-------------------"
npm run build
echo "打包完成"
#-A 用来被替换的文件信息、被修改的文件以及新增的文件到暂存区
git add -A
echo "git add中"
git status
git commit -m "$1"
echo "git commit中"
git push origin develop
echo '提交成功！'
#部署正式目录
# echo '清空dist目录'
# rm -f ./dist
# echo "正在复制-------------------"
# cp -r dist/. ./dist
# echo "部署成功"

#清空dist目录
#清空dist目录
# emptyRemoteDist(){
#     if [$env == 'prod']
#     then
#       echo "生产环境 清空dist"
#       ssh root@xiaofei "rm -rf /dist/*"
#     else
#       echo "开发环境 清空dist"
# }

#发送文件到正式服务器
# transferFileToProSever(){
#     echo "部署文件到正式服务器"
# }

#发送文件到测试服务器
# transferFileToTestSever(){
#     echo "部署文件到测试服务器"
# }

# if [ $handle == "build" ]
# then
#     if [ $env == "prod" ]
#     then
#         env='prod'
#         echo "[exec]build ==> build production"
#         npm run build
#         emptyRemoteDist
#         transferFileToProSever
#     else
#         env='dev'
#         echo "[exec]build ==> build development"
#         npm run build
#         emptyRemoteDist
#         transferFileToTestSever
#     fi
# fi