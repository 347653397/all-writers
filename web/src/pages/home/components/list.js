import { connect } from 'dva';
import { routerRedux } from 'dva/router';
// import QueueAnim from "rc-queue-anim";

function mapStateToProps(props) {
    return {
        home: props.home,
    };
}
const listTab = ({ item, type, dispatch, home }) => {
    const pageChangeHandler = (id, routerName) => {
        dispatch(routerRedux.push({
            pathname: routerName,
            query: { id,in_type:1}
        }));
    }
    return (
        <div>
            <ul style={{ background: item && item.length == 0 ? 'none' : '#fff' }}>
                {
                    item && item.map((d, i) => {
                        return (
                            <li key={i} onClick={() => pageChangeHandler(d.up_type == 2 ? d.course_id : d.item_id, d.up_type == 2 ? '/detai/list' : '/detai')}>
                                <div className="left">
                                    <img src={d.course_pic} />
                                </div>
                                <div className="right">
                                    <h3 className="textOversTab2">{d.up_type == 2 ? <span className="color1">连载</span> : <span className="color2">单集</span>}{d.course_title}</h3>
                                    <p className="textOversTab2">{d.course_brief || '暂无介绍'}</p>
                                    <p><span className="price" style={{ float: 'left', fontSize: '0.35rem' }}>{d.author}</span>

                                        {/* {Number(d.original_price) ? <font className="priceDel" style={{ position: 'relative', bottom: '-0.2rem' }}>￥{d.original_price}</font> : null} */}

                                    </p>
                                    <div className="tags">
                                        <span>{d.play_num > 10000 ? ((d.play_num - d.play_num % 1000) / 10000 + 'W') : d.play_num || 0}</span>
                                        <span>{d.comment_count > 10000 ? ((d.comment_count - d.comment_count % 1000) / 10000 + 'W') : d.comment_count}</span>
                                        {/* <span>{d.total_people > 10000 ? ((d.total_people - d.total_people % 1000) / 10000 + 'W') : d.total_people}人</span> */}
                                        {/* <span>{d.total_duration}</span> */}
                                        <span className="price">{Number(d.total_amount) ? '￥' + d.total_amount || 0 : null}</span>
                                    </div>
                                </div>
                            </li>
                        )
                    })
                }
                {
                    item && item.length == 0 && !home.homeLoading ?
                        <div className="lengthIcon"></div>
                        : null
                }
            </ul>
            <div className="moreStyle">{item.length != 0 ? home.homeLength ? home.refreshLoading ? '正在加载' : '加载更多' : '没有更多了' : ''}</div>
        </div>
    )
}

export default connect(mapStateToProps)(listTab)