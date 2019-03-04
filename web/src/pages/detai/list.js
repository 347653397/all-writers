
import React from 'react';
import { connect } from 'dva';
import { routerRedux } from 'dva/router';
import { ActivityIndicator } from 'antd-mobile';

function mapStateToProps(props) {
    return {
        detai: props.detai,
        loading: props.loading
    };
}

class Listsub extends React.Component {
    state = {
        moreStatus: false
    }
    //打包购买
    onClickBuy = () => {
        const { courseInfo, courseItem } = this.props.detai;
        const { dispatch } = this.props;
        dispatch({
            type: 'detai/save',
            payload: {
                rewardType:3,
            }
        })
        dispatch({
            type: 'detai/buyAudio',
            payload: {
                buy_type: 2,
                courseItemsIds: courseInfo.itemIds
            },
            dispatch
            // playLoading: () => dispatch({ type: 'detai/getCourseInfo', dispatch })
        })
    }
    pageChangeHandler = (id, routerName) => {
        // if (routerName == '/detai') {
        //     document.getElementById('audioPlayIcon').style.display = 'none';//隐藏小图标
        //     let audio = document.getElementById('audioElement');
        //     audio.load();
        //     //重新播放当前课程;
        //     this.props.dispatch({
        //         type: 'detai/save',
        //         payload: {
        //             playTestingStatus: false,//不管是否正在播放的课程，强制播放当前课程
        //             detaiLocationSearch: '',//清除记录的课程id地址
        //             currentTime: 0,
        //             audoMsg: '',
        //             playStatus: false,
        //             audioData: {}
        //         }
        //     })
        // }
        // , cid: home.currentKey, update_status, type: encodeURI(type) 
        this.props.dispatch(routerRedux.push({
            pathname: routerName,
            query: { id,in_type:1}
        }));
    }
    render() {
        const { courseInfo, courseItem } = this.props.detai;
        const { moreStatus } = this.state;
        return (
            <div className="listDetaiBox" style={{ paddingBottom: "1.6rem" }}>
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={this.props.loading.effects['detai/getCourseInfo']}
                />
                <div className={`detaiBoxOut ${!this.props.loading.effects['detai/getCourseInfo'] ? 'fadeIn animated' : ''}`}>
                    <div className="top">
                        <img src={courseInfo.big_pic} />
                    </div>
                    <div className="contentTopLine">
                        <div className="title">
                            <h2>故事简介</h2>
                        </div>
                        {
                            courseInfo.course_brief ?
                                <div className="nr" >
                                    <div dangerouslySetInnerHTML={moreStatus ? { __html: courseInfo.course_brief } : { __html: courseInfo.course_brief.length <= 100 ? courseInfo.course_brief : courseInfo.course_brief.substring(0, 100) + ` ...` }}>
                                    </div>
                                    {
                                        courseInfo.course_brief.length > 100 ?
                                            <span style={{ display: 'block', textAlign: 'right' }} onClick={() => this.setState({ moreStatus: !moreStatus })}>{moreStatus ? '隐藏' : '更多'}</span>
                                            : null
                                    }
                                </div>
                                :
                                <div className="nr">
                                    <p>暂无简介</p>
                                </div>
                        }

                    </div>
                    <div className="contentTopLine">
                        <div className="title">
                            <h2>专辑里的声音</h2>
                        </div>
                        <div className="listBox listDetai">
                            <ul>
                                {
                                    courseItem && courseItem.map((d, i) => {
                                        return (
                                            <li key={i} onClick={() => this.pageChangeHandler(d.item_id, '/detai')}>
                                                <div className="left">
                                                    <img src={d.course_pic} />
                                                </div>
                                                <div className="right">
                                                    <h3>{d.title}{d.type == 1 ? d.is_buy == 1 ? <span className="color3">{d.price}</span> : <span className="color4">已购买</span> : <span className="color4">免费</span>}</h3>
                                                    <p>{d.audio_brief && d.
                                                        audio_brief.length >= 40 ? d.audio_brief.slice(0, 40) + '...' : d.audio_brief || '暂无介绍'}</p>
                                                </div>
                                            </li>
                                        )
                                    })
                                }
                            </ul>
                            {
                                courseItem && courseItem.length == 0 ?
                                    <div >
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无专辑</p>
                                        <br />
                                    </div>
                                    : null
                            }
                        </div>
                    </div>
                    {
                        courseInfo.total_amount != 0 ?
                            <div className="listBtnPlay" onClick={() => this.onClickBuy(false)}>
                                <div className="btn">
                                    点击购买&nbsp;￥{courseInfo.total_amount || 0}元
                                 </div>
                            </div>
                            :null
                    }
                </div>
            </div>
        )
    }
}

export default connect(mapStateToProps, )(Listsub)