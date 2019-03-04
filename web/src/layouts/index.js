import { Component } from 'react';
import { Button, TabBar } from 'antd-mobile';
import { routerRedux, withRouter } from 'dva/router';
import { connect } from 'dva';
import Icon1 from '../assets/bar_shouye_off.png'
import IconCurrent1 from '../assets/bar_shouye_on.png'
import Icon2 from '../assets/bar_wode_off.png'
import IconCurrent2 from '../assets/bar_wode_on.png'
import './index.less';
// import { PAGE_SIZE } from '../constants';

class TabBarExample extends Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedTab: 'blueTab',
      hidden: false,
      fullScreen: true,
    };
  }
  pageChangeHandler = (router, query) => {
    this.props.dispatch(routerRedux.push({
      pathname: router,
      query
    }));
  }

  render() {
    const { location, children } = this.props;
    const { detaiLocationSearch } = this.props.detai;//audio图标跳转地址
    const menuItem = [{
      key: 1,
      name: '主页',
      icon: location.pathname != '/users' ? 'IconCurrent1' : 'Icon1',
      path: '/',
      onClick: () => this.pageChangeHandler('/')
    }, {
      key: 2,
      name: '我的',
      icon: location.pathname == '/users' ? 'IconCurrent2' : 'Icon2',
      onClick: () => this.pageChangeHandler('/users')
    }]
    return (
      <div >
        <div className="contaionSeccern">
          {children}
        </div>
        {
          location.pathname == '/login' || location.pathname == '/detai' || location.pathname == '/release' || location.pathname == '/detai/list' || location.pathname.indexOf('/users/') != -1 || location.pathname.indexOf('/comment/') != -1 ?
            null
            :
            <div className="contaionBar">
               <div className="navCenter" onClick={() => this.pageChangeHandler('/release')}></div>
              {menuItem.map((d, i) => {
                return (
                  <div className="navTag" onClick={d.onClick} key={i}>
                    <div className={`navicon ${d.icon}`}>
                    </div>
                    <p className="navicon-title" style={{ color: d.color }}>{d.name}</p>
                  </div>
                )
              })}
            </div>
        }
        {/* 播放最小化图标 */}
        <div className="audioPlayIcon" id="audioPlayIcon" style={{ display: 'none' }} onClick={() => this.pageChangeHandler('/detai', { id: detaiLocationSearch,in_type:1 })}></div>

        {/* <TabBar
          unselectedTintColor="#424242"
          tintColor="#E8D231"
          barTintColor="white"
          hidden={location.pathname === '/detai'?true:false}
          tabBarPosition="bottom"
        >
          <TabBar.Item
            title="主页"
            key="主页"
            icon={{ uri: Icon1 }}
            selectedIcon={{ uri: IconCurrent1 }}
            selected={location.pathname != '/users'}
            onPress={()=>this.pageChangeHandler('/')}
          >
            {location.pathname != '/users' && children}
          </TabBar.Item>
          <TabBar.Item
            icon={{ uri: Icon2 }}
            selectedIcon={{ uri: IconCurrent2 }}
            title="我的"
            key="我的"
            selected={location.pathname === '/users'}
            onPress={()=>this.pageChangeHandler('/users')}
          >
          {location.pathname === '/users' && children}
          </TabBar.Item>
        </TabBar> */}
      </div>
    );
  }
}
function mapStateToProps(props) {
  return {
    detai: props.detai,
  };
}
export default withRouter(connect(mapStateToProps)(TabBarExample));

