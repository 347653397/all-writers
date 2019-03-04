
import React from 'react';
import { connect } from 'dva';
import TabList from './components/list'
import './style.less'

/**
*--小牛参谋长（选项卡3）
*--2018.07.13 anaoei@qq.com
* @method AdviserTab3 文件名
* @param { ReactElement } AdviserTab3 组件名
* @param { String } home model名称
*/

function mapStateToProps(props) {
    return {
        home: props.home,
    };
}
class AdviserTab3 extends React.Component {
    render() {
        let { homeItem,homeLoading } = this.props.home
        return (
            <div className={`tabsBox ${!homeLoading?'fadeIn animated':''}`}>
                <div className="listBox listTab2">
                    <TabList item={homeItem} routerName="/detai" type="小牛参谋长" />
                </div>
            </div>
        )
    }
}

export default connect(mapStateToProps, )(AdviserTab3)