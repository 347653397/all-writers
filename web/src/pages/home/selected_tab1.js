
import React from 'react';
import { connect } from 'dva';
import { routerRedux } from 'dva/router';
import { Carousel } from 'antd-mobile';
import TabList from './components/list'

import '../../utils/';
import './style.less'

/**
*--精选模块（选项卡1）
*--2018.07.13 anaoei@qq.com
* @method SelectedTab1 文件名
* @param { ReactElement } SelectedTab1 组件名
* @param { String } home model名称
*/

function mapStateToProps(props) {
    return {
        home: props.home,
        loading: props.loading,
    };
}
class SelectedTab1 extends React.Component {
    componentDidMount() {
        this.props.dispatch({ type: 'home/getBannerList' })
    }

    // data.jump_url
    render() {
        let { homeItem, bannerData, homeLoading, refreshLoading } = this.props.home;
        let { loading } = this.props;
        let Barlist = bannerData.length != 0 && bannerData.map((data, index) => {
            return (
                <div className="imgSlider" key={index} onClick={() => {
                    window.location.href = data.jump_url;
                    // window.location.reload();
                }}>
                    <img src={data.banner_pic} />
                    {/* <div className={styles.BannerTitle}>
                    <h2>{data.title}</h2>
                    </div> */}
                </div>
            )
        });
        return (
            <div className={`tabsBox ${!homeLoading ? 'fadeIn animated' : ''}`}>
                <div style={{ height: '4.2rem', overflow: 'hidden' }}>
                    {
                        !loading.effects['users/toRewardDetails'] && !refreshLoading ?
                            <Carousel
                                className="myCarousel"
                                dots={false}
                                swipeSpeed={22}
                                autoplay={bannerData.length > 1}
                                infinite={bannerData.length > 1}
                                dots={bannerData.length > 1}
                            >
                                {Barlist ? Barlist : <div style={{ height: '4.2rem' }}></div>}
                            </Carousel> : null
                    }

                </div>
                <div className="listBox listTab2" style={{ marginTop: '0.2rem' }}>
                    <TabList item={homeItem} routerName="/detai" type="推荐" />
                </div>
                {/* <QueueAnim type="bottom" component='ul'>
                        {
                            homeItem && homeItem.map((d, i) => {
                                return (
                                    <li key={i} onClick={() => this.pageChangeHandler(d.up_type == 2 ? d.course_id : d.item_id, d.up_type == 2 ? '/detai/list' : '/detai')}>
                                        <div className="left">
                                            <img src={d.course_pic} />
                                        </div>
                                        <div className="right">
                                            <h3>{d.up_type == 2 ? <span className="color1">连载</span> : <span className="color2">单集</span>}{d.course_title}</h3>
                                            <p className="textOversTab2">{d.course_brief || '暂无介绍'}</p>
                                            <div className="tags">
                                                <span>{d.play_num > 10000 ? ((d.play_num - d.play_num % 1000) / 10000 + 'W') : d.play_num || 0}</span>
                                                <span>{d.comment_count > 10000 ? ((d.comment_count - d.comment_count % 1000) / 10000 + 'W') : d.comment_count}</span>
                                                <span>{new Date(Number(d.create_time * 1000)).Format("MM-dd hh:mm")}</span>
                                            </div>
                                        </div>
                                    </li>
                                )
                            })
                        }
                   </QueueAnim>
                    {
                        homeItem && homeItem.length == 0 && !homeLoading?
                            <div className="lengthIcon"></div>
                            : null
                    } */}
            </div>
        )
    }
}

export default connect(mapStateToProps, )(SelectedTab1)