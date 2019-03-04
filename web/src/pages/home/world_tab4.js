
import React from 'react';
import { connect } from 'dva';
import TabList from './components/list'
import './style.less'
/**
*--世间听（选项卡4）
*--2018.07.13 anaoei@qq.com
* @method WorldTab4 文件名
* @param { ReactElement } WorldTab4 组件名
* @param { String } home model名称
*/

function mapStateToProps(props) {
    return {
        home: props.home,
    };
}
class WorldTab4 extends React.Component {
    render() {
        let { homeItem,homeLoading } = this.props.home
        return (
            <div className={`tabsBox ${!homeLoading?'fadeIn animated':''}`}>
                <div className="listBox listTab2">
                    <TabList item={homeItem} routerName="/detai" type="世间听" />
                </div>
            </div>
        )
    }
}

export default connect(mapStateToProps, )(WorldTab4)