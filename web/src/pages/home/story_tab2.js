
import React from 'react';
import { connect } from 'dva';
import './style.less';
import TabList from './components/list'

/**
*--听故事（选项卡2）
*--2018.07.13 anaoei@qq.com
* @method storyTab2 文件名
* @param { ReactElement } storyTab2 组件名
* @param { String } home model名称
*/

function mapStateToProps(props) {
    return {
        home: props.home,
    };
}
class storyTab2 extends React.Component {
    componentDidMount() {

    }
    render() {
        let { homeItem, homeLoading } = this.props.home
        return (
            <div className={`tabsBox ${!homeLoading ? 'fadeIn animated' : ''}`}>
                <div className="listBox listTab2">
                    <TabList item={homeItem} routerName="/detai" type="听故事" />
                </div>
            </div>
        )
    }
}

export default connect(mapStateToProps, )(storyTab2)