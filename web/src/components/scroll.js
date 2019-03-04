import {Component} from 'react';
import { ActivityIndicator } from 'antd-mobile';
import './scroll.less';
class scrollSub extends Component{
  componentDidMount(){
    this.componentMescroll();
    document.getElementsByClassName('scrollOverflow')[0].scrollTop=0;
  }
  //初始化上拉加载效果
  componentMescroll(){
        this.mescroll = new MeScroll("tabContentHeight", { //第一个参数"mescroll"对应上面布局结构div的id
          //如果您的下拉刷新是重置列表数据,那么down完全可以不用配置,具体用法参考第一个基础案例
            //解析: down.callback默认调用mescroll.resetUpScroll(),而resetUpScroll会将page.num=1,再触发up.callback
        down:{
          use:false,
        },
        up: {
          autoShowLoading:this.props.loading,
          callback:(page,mescroll)=>{
             let {datalist,loading}=this.props;
             if(!loading && datalist){
              this.props.callback()
             }
             this.mescroll.endSuccess(10,datalist)
          },
          htmlNodata:'没有更多课程了',
          isBounce: false //如果您的项目是在iOS的微信,QQ,Safari等浏览器访问的,建议配置此项.解析(必读)
        }
      });
      this.mescroll.endUpScroll(false)
  }

  componentWillUnmount() {
     this.mescroll.setBounce(true)
     this.mescroll.destroy();
     this.props.componentReset()
  }
  render() {
    return(
     <div>
      <ActivityIndicator
          toast
          text="加载数据"
          animating={this.props.loading}
      />
        <div className="scrollOverflow mescroll" id="tabContentHeight" style={this.props.style}>
         {this.props.children}
        </div>
    </div>
    )
  }
};

export default scrollSub