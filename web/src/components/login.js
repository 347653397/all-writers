import { List, Toast, InputItem } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import { phoneTest, onMax } from '../utils';
import './login.less';
const Item = List.Item;

const login = ({ loginData, time, onProps, onCode, onSubmit, dispatch, routerName, drawLoad, code_img, loading }) => {
    let { phone, graph_code, note_code, name } = loginData;
    const code = () => {
        phone ?
            phoneTest.test(phone) ?
                graph_code ?
                    code_img.join("") == graph_code ?
                        onCode()
                        : Toast.info('图形码错误', 1)
                    : Toast.info('请输入图形码', 1)
                : Toast.info('手机号格式有误', 1)
            : Toast.info('请输入手机号', 1);
        //验证码错误时重新刷新
        //   code_img.join("")==graph_code?
        //     onCode()
        //     :Toast.info('图形码错误',1)
    }
    const sumit = () => {
        name ?
            phone ?
                phoneTest.test(phone) ?
                    graph_code ?
                        note_code ?
                            onSubmit()
                            : Toast.info('请输入短信验证码', 1)
                        : Toast.info('请输入图形码', 1)
                    : Toast.info('手机号格式有误', 1)
                : Toast.info('请输入手机号', 1)
            : Toast.info('请输入姓名', 1)
    }
    return (
        <div className="loginBox">
            <List className="list">
                <InputItem value={name} onChange={e => onProps(e, 'name')} onInput={e => onMax(e, 6)} placeholder="输入姓名" type="text">姓名</InputItem>
                <InputItem value={phone} onChange={e => onProps(e, 'phone')} onInput={e => onMax(e, 11)} placeholder="输入需要绑定的手机号" type="tel">手机号</InputItem>
                <InputItem value={graph_code} onChange={e => onProps(e, 'graph_code')} onInput={e => onMax(e, 6)} extra={<div className="btn"><canvas id="canvas" onClick={() => drawLoad()} style={{ width: '2.5rem', height: '1rem' }}></canvas></div>} placeholder="请输入图形码" type="text">图形码</InputItem>
                <InputItem value={note_code} onChange={e => onProps(e, 'note_code')} onInput={e => onMax(e, 6)} extra={<div className={`btn ${time == 60 ? 'active' : null}`} onClick={time == 60 ? code : null}>{time == 60 ? '获取验证码' : `已发送${time}秒`}</div>} placeholder="请输入验证码" type="number">验证码</InputItem>
            </List>
            <div className="commentSubmit" onClick={sumit}>{loading ? '正在绑定' : '绑定手机'}</div>
            <div className="commentSubmit" onClick={() => {
                window.history.go(-1);
                //     dispatch(routerRedux.push({
                //         pathname: routerName,
                //    }))
            }} style={{ background: "#fff",color:'#E2B979' }}>返&nbsp;回</div>
        </div>
    )
}

export default login