import { Component } from 'react';
import { connect } from 'dva';
import { ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import '../../utils';
import './index.less';
/**
*--我的评论
*--2018.07.13 anaoei@qq.com
* @method Comment 文件名
* @param { ReactElement } Comment 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
        loading: props.loading
    };
}

class Comment extends Component {
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

        document.getElementById('refreshComment').style.height = document.documentElement.clientHeight + 'px';
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
        this.iScrollInstance = new IScroll(document.getElementById('refreshComment'), options);
        this.iScrollInstance.on('scrollEnd', this.onScrollEnd);
        dispatch({ type: 'users/save', payload: { refreshComment: () => this.iScrollInstance.refresh() } });
        dispatch({ type: 'users/myComment' });
        // setTimeout(() => {

        // }, 1000)

    }
    onScrollEnd() {
        // 滑动结束后，停在加载区域
        const { commentLength } = this.props.users;
        const { dispatch } = this.props;
        if (this.iScrollInstance.y <= this.iScrollInstance.maxScrollY && commentLength) {
            dispatch({ type: 'users/myComment' })
        }
    }
    componentWillUnmount() {
        window.document.ontouchmove = this.move;
        document.body.removeEventListener('touchmove', this.onTouchmove, { passive: false });
        this.iScrollInstance.destroy();
    }
    render() {
        const { users, dispatch, location, loading } = this.props;
        let moeny = location.query.money;
        const routerPush = (routerName, query) => {
            dispatch(routerRedux.push({
                pathname: routerName,
                query
            }));
        }
        const { commentData, commentLength } = users;
        return (
            <div className="userDetai">
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={loading.effects['users/myComment']}
                />
                <div className="moenyList" id="refreshComment">
                    <div>
                        <ul>

                            {
                                commentData && commentData.map((d, i) => {
                                    return (
                                        <li key={i}>
                                            <h3 className="active">{d.content}</h3>
                                            <p>{new Date(Number(d.created_at * 1000)).Format("yyyy-MM-dd hh:mm")}&nbsp;{d.title}</p>
                                        </li>
                                    )
                                })
                            }
                            <div className="moreStyle">{commentLength ? loading.effects['users/myComment'] ? '正在加载' : '加载更多' : '没有更多评论了'}</div>
                        </ul>
                        {
                            commentData && commentData.length == 0 ?
                                <div >
                                    <div className="lengthIcon"></div>
                                    <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无评论</p>
                                    <br />
                                </div>
                                : null
                        }
                    </div>
                </div>
            </div>
        )
    }
}
export default connect(mapStateToProps)(Comment)
