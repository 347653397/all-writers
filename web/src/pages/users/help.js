import { connect } from 'dva';
import { Icon } from 'antd-mobile';
import './index.less';
/**
*--帮助中心
*--2018.07.13 anaoei@qq.com
* @method Help 文件名
* @param { ReactElement } Help 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
    };
}
const Help = ({ users, dispatch }) => {
    const { helpData } = users;
    const onTabs = (i, d) => {
        helpData[i].display = !d;
        dispatch({ type: 'users/save', payload: { helpData: helpData } })
    };
    return (
        <div className="helpBox">
            <ul className="tabs">
                {
                    helpData && helpData.map((d, i) => {
                        return (
                            <li key={i}>
                                <h3 onClick={() => onTabs(i, d.display)}>{i + 1}.{d.title}<Icon className="icon" type={d.display ? 'down' : 'right'} size='md' /></h3>
                                {d.display ? <div className="nr">提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户</div> : null}
                            </li>
                        )
                    })
                }

            </ul>
        </div>
    )
}

export default connect(mapStateToProps)(Help)