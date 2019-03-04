import { Component } from 'react';
import { connect } from 'dva';
import { ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import './list.less';

/**
*--评论回复列表
*--2018.09.02 anaoei@qq.com
* @method Comment_list 文件名
* @param { ReactElement } CommentList 组件名
* @param { String } comment model名称
*/
function mapStateToProps(props) {
    return {
        comment: props.comment,
    };
}

class CommentList extends Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
        };
        this.onScrollEnd = this.onScrollEnd.bind(this);
    }
    componentDidMount() {
        const { dispatch } = this.props;
        this.move = window.document.ontouchmove;//保存默认触摸事件
        //禁止触摸
        window.document.ontouchmove = function (e) {
            e.preventDefault();
        };
        //阻止上下拉滚动效果
        this.onTouchmove = function (e) { e.preventDefault(); }
        document.body.addEventListener('touchmove', this.onTouchmove, { passive: false });

        document.getElementById('moenyList').style.height = document.documentElement.clientHeight + 'px';
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
        this.iScrollInstance = new IScroll(document.getElementById('moenyList'), options);
        this.iScrollInstance.on('scrollEnd', this.onScrollEnd);
        dispatch({ type: this.props.actionType, payload: { refreshLoading: () => this.iScrollInstance.refresh() } });
        // this.props.refresh(this.iScrollInstance.refresh())
        // dispatch({ type: 'comment/toRewardDetails' });
        // setTimeout(() => {

        // }, 1000)
    }
    onScrollEnd() {
        // 滑动结束后，停在加载区域
        const { listLength } = this.props;
        if (this.iScrollInstance.y <= this.iScrollInstance.maxScrollY && listLength) {
           this.props.onChangeGet()
        }
    }
    componentWillUnmount() {
        window.document.ontouchmove = this.move;
        document.body.removeEventListener('touchmove', this.onTouchmove, { passive: false });
        this.iScrollInstance.destroy();
    }
    render() {
        return (
            <div className="userDetai">
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={this.props.loading}
                />
                <div id="moenyList">
                    {this.props.children}
                </div>
            </div>
        )
    }
}
export default connect(mapStateToProps)(CommentList)
