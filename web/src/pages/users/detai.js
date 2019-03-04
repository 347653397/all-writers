import { Component } from 'react';
import { connect } from 'dva';
import { ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import '../../utils';
import './index.less';
/**
*--钱包明细
*--2018.07.13 anaoei@qq.com
* @method Detai 文件名
* @param { ReactElement } Detai 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
        loading: props.loading
    };
}

class Detai extends Component {
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
        dispatch({ type: 'users/save', payload: { refreshDetai: () => this.iScrollInstance.refresh() } });
        dispatch({ type: 'users/myWallet' });
        // setTimeout(() => {

        // }, 1000)
    }
    onScrollEnd() {
        // 滑动结束后，停在加载区域
        const { moenyLength } = this.props.users;
        const { dispatch } = this.props;
        if (this.iScrollInstance.y <= this.iScrollInstance.maxScrollY && moenyLength) {
            dispatch({ type: 'users/myWallet' })
        }
    }
    componentWillUnmount() {
        window.document.ontouchmove = this.move;
        document.body.removeEventListener('touchmove', this.onTouchmove, { passive: false });
        this.iScrollInstance.destroy();
    }
    render() {
        const { users, dispatch, location, loading } = this.props;
        // let moeny = location.query.money;
        const routerPush = (routerName, query) => {
            dispatch(routerRedux.push({
                pathname: routerName,
                query
            }));
        }
        const { toReward, moenyLength, cashBalance } = users;
        return (
            <div className="userDetai">
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={loading.effects['users/myWallet']}
                />
                <div id="moenyList">
                    <div>
                        <div className="moeny">
                            <p>可提现金额</p>
                            <h2>￥{cashBalance}</h2>
                            <div className="btn" onClick={() => routerPush('/users/forward')}>提现</div>
                        </div>
                        <div className="moenyList">
                            <ul>
                                {
                                    toReward && toReward.map((d, i) => {
                                        return (
                                            <li key={i}>
                                                <h3>{(()=>{
                                                    switch(d.trade_type){
                                                        case '3':return `评论被${d.nickname}打赏了`;
                                                        break;
                                                        case '7':return `投稿被${d.nickname}购买了`;
                                                        break;
                                                        case '8':return `投稿被${d.nickname}打赏了`;
                                                        break;
                                                    }
                                                })()}</h3>
                                                <p style={{marginBottom:'0.1rem'}}>来源：{d.title}</p>
                                                <p>{new Date(Number(d.created_at * 1000)).Format("yyyy-MM-dd hh:mm")}</p>
                                                <span>{d.money}</span>
                                            </li>
                                        )
                                    })
                                }
                                {
                                    toReward.length != 0 ?
                                        <div className="moreStyle">{moenyLength ? loading.effects['users/myWallet'] ? '正在加载' : '加载更多' : '没有更多明细了'}</div>
                                        : null
                                }

                            </ul>
                            {
                                toReward && toReward.length == 0 ?
                                    <div >
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无明细记录</p>
                                        <br />
                                    </div>
                                    : null
                            }
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}
export default connect(mapStateToProps)(Detai)
