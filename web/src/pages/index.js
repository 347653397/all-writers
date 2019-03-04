import { Component } from 'react';
import { ActivityIndicator, Carousel } from 'antd-mobile';
import { connect } from 'dva';
import './index.less';
import SelectedTab1 from './home/selected_tab1';
import StoryTab2 from './home/story_tab2';
import AdviserTab3 from './home/adviser_tab3';
import WorldTab4 from './home/world_tab4';
import { routerRedux } from 'dva/router';
// import QueueAnim from "rc-queue-anim";

/**
*--主页模块
*--2018.07.13 anaoei@qq.com
* @method Index 文件名
* @param { ReactElement } IndexPage 组件名
* @param { String } home model名称
*/
function mapStateToProps(props) {
  return {
    home: props.home,
    detai: props.detai,
    loading: props.loading
  };
}
//更新組件
class IscrollHome extends React.Component {
  componentDidMount() {
    setTimeout(() => {
      this.props.homeRefresh != undefined && this.props.homeRefresh()
    }, 300)
  }
  render() {
    const { children } = this.props;
    return (
      <div>
        {children}
      </div>
    )
  }
}
class IndexPage extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
    };
    this.onScroll = this.onScroll.bind(this);
    this.onScrollEnd = this.onScrollEnd.bind(this);
    this.onTouchStart = this.onTouchStart.bind(this);
    this.onTouchEnd = this.onTouchEnd.bind(this);
    this.isTouching = false;
  }
  componentDidMount() {
    const { dispatch } = this.props;
    dispatch({ type: 'home/save', payload: { homeLoading: true } });

    let heightView = (document.documentElement.clientHeight - (document.getElementsByClassName('contaionBar')[0].offsetHeight + document.getElementsByClassName('tabsList')[0].offsetHeight)) + 'px';
    document.getElementById('tabContentHeight').style.height = heightView;
    // document.getElementsByClassName('tabContent')[0].style.minHeight = document.documentElement.clientHeight + 'px';
    let tabKey = location.hash && location.hash.split('=')[1] || undefined;

    this.move = window.document.ontouchmove;//保存默认触摸事件
    //禁止触摸
    window.document.ontouchmove = function (e) {
      e.preventDefault();
    };
    //阻止上下拉滚动效果
    this.onTouchmove = function (e) { e.preventDefault(); }
    document.body.addEventListener('touchmove', this.onTouchmove, { passive: false });

    //先获取分类，再初始化数据
    dispatch({ type: 'home/save', payload: { homeLoading: true } });
    // dispatch({ type: 'home/getCategoryList', payload: { categoryKey: tabKey ? Number(tabKey) : false }})
    dispatch({ type: 'detai/weixinShareConfig', payload: { url: location.href.split('#')[0] } })

    const options = {
      // 默认iscroll会拦截元素的默认事件处理函数，我们需要响应onClick，因此要配置
      preventDefault: false,
      // 禁止缩放
      zoom: false,
      // 支持鼠标事件，因为我开发是PC鼠标模拟的
      mouseWheel: true,
      // 滚动事件的探测灵敏度，1-3，越高越灵敏，兼容性越好，性能越差
      probeType: 3,
      // 拖拽超过上下界后出现弹射动画效果，用于实现下拉/上拉刷新
      bounce: true,
      killFlicker: true,
    };
    // setTimeout(() => {
    this.iScrollScrren = new IScroll(document.getElementById('tabContentHeight'), options);
    this.iScrollScrren.on('scrollEnd', this.onScrollEnd);
    this.iScrollScrren.on('scroll', this.onScroll);
    dispatch({ type: 'home/save', payload: { homeRefresh: () => this.iScrollScrren.refresh() } });
    // }, 100)
  }
  onScroll() {
    const { homeLength, refreshLoading } = this.props.home;
    //轻慢滑动触发到底
    if (this.iScrollScrren.y && this.isTouching && this.iScrollScrren.y <= this.iScrollScrren.maxScrollY + 60) {
      homeLength && this.props.dispatch({ type: 'home/save', payload: { refreshLoading: true } })
    }
    //或者快速滑动触发到底
    if (this.iScrollScrren.y && (this.iScrollScrren.maxScrollY - this.iScrollScrren.y) >= 10) {
      homeLength && this.props.dispatch({ type: 'home/save', payload: { refreshLoading: true } })
    }
  }
  onScrollEnd() {
    // 滑动结束后，停在加载区域
    const { homeLength } = this.props.home;
    const { dispatch } = this.props;
    if (this.iScrollScrren.y && this.iScrollScrren.y <= this.iScrollScrren.maxScrollY + 60 && homeLength && !this.isTouching) {
      this.onGetlist()
    } else {
      dispatch({ type: 'home/save', payload: { refreshLoading: false } })
    }
  }
  onGetlist() {
    const { currentKey } = this.props.home;
    const { dispatch } = this.props;
    dispatch({ type: 'home/getCourseList', payload: { currentKey } })
  }
  onTouchStart(ev) {
    this.isTouching = true;
  }

  onTouchEnd(ev) {
    this.isTouching = false;
  }
  componentWillUnmount() {
    window.document.ontouchmove = this.move;
    document.body.removeEventListener('touchmove', this.onTouchmove, { passive: false });
    this.iScrollScrren != undefined && this.iScrollScrren.destroy();
    this.componentReset()
  }
  componentWillReceiveProps(nextProps) {
    let { audoPusenStatus } = nextProps.detai;
    let { refreshLoading } = nextProps.home;
    if (!refreshLoading && !audoPusenStatus) {
      setTimeout(this.iScrollScrren.refresh(), 500)
    }

  }
  //重置数据
  componentReset() {
    this.props.dispatch({
      type: 'home/save',
      payload: {
        homeItem: [],
        pageNum: 1,
        homeLength: true
      }
    })
  }
  callback = (key) => {
    const { refreshLoading, currentKey } = this.props.home;
    if (!refreshLoading && currentKey != key && !this.isTouching) {
      setTimeout(() => {
        this.componentReset()
        this.props.dispatch(routerRedux.push({
          pathname: '/',
          query: { tab: key }
        }));
        this.props.dispatch({ type: 'home/save', payload: { homeLoading: true, homeItem: [] } });
        document.getElementsByClassName('tabContent')[0].scrollTop = 0;
        this.iScrollScrren.scrollTo(0, 0);
      }, 50);
    }
  }
  render() {
    const { currentKey, homeLength, homeRefresh, refreshLoading, bannerData, categoryData, homeLoading } = this.props.home;

    return (
      <div className="homeScreen" >
        <ActivityIndicator
          toast
          text="加载数据"
          animating={refreshLoading}
        />
        <ul className="tabsList">
          {
            categoryData.length != 0 && categoryData.map((d, i) => {
              return <li key={i} className={currentKey == Number(d.cid) ? 'active' : null} onClick={() => this.callback(Number(d.cid))}>{d.cat_name}</li>
            })
          }
          {/* <li className={currentKey == 1 ? 'active' : null} onClick={() => this.callback(1)}>推荐</li>
          <li className={currentKey == 2 ? 'active' : null} onClick={() => this.callback(2)}>听剧本</li>
          <li className={currentKey == 3 ? 'active' : null} onClick={() => this.callback(3)}>小牛参谋</li>
          <li className={currentKey == 4 ? 'active' : null} onClick={() => this.callback(4)}>世间听</li> */}
          {/* <li className={`indexTopRight ${currentKey == 5 ? 'active' : ''}`} onClick={() => this.callback(5)}></li> */}
        </ul>
        <div className="am-tabs-pane-wrap-active" id="tabContentHeight" onTouchStart={this.onTouchStart} onTouchEnd={this.onTouchEnd}>
          <div className="tabContent">
            {(() => {
              switch (currentKey) {
                case 1: return <SelectedTab1 />
                  break;
                case 2: return <StoryTab2 />
                  break;
                case 3: return <AdviserTab3 />
                  break;
                case 4: return <WorldTab4 />
                  break;
                // case 5: return <div><SelectedTab1 type="5" /></div>
                // break;
                default:
                  break;
              }
            })()}
          </div>
        </div>
      </div>
    )
  }
};

export default connect(mapStateToProps, )(IndexPage)