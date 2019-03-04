import { connect } from 'dva';
import { Component } from 'react';
import { List, Toast, InputItem, Result, Icon } from 'antd-mobile';
import { phoneTest, onMax, normalizeCurrency } from '../../utils';
import { createForm } from 'rc-form';

import './index.less';

/**
*--提现模块
*--2018.07.13 anaoei@qq.com
* @method Forward 文件名
* @param { ReactElement } Forward 组件名
* @param { String } users model名称
*/
const Item = List.Item;

// 通过自定义 moneyKeyboardWrapProps 修复虚拟键盘滚动穿透问题
// https://github.com/ant-design/ant-design-mobile/issues/307
// https://github.com/ant-design/ant-design-mobile/issues/163
const isIPhone = new RegExp('\\biPhone\\b|\\biPod\\b', 'i').test(window.navigator.userAgent);
let moneyKeyboardWrapProps;
if (isIPhone) {
    moneyKeyboardWrapProps = {
        onTouchStart: e => e.preventDefault(),
    };
}
class Forward extends Component {
    componentDidMount() {
        this.inputRef.focus()
    }
    render() {
        const { cash_balance, userData, forwardStatus } = this.props.users;
        const { dispatch, form, loading } = this.props;
        const onChangeVal = (e, name) => {
            if (Number(normalizeCurrency(e)) <= Number(userData.cash_balance)) {
                dispatch({ type: 'users/save', payload: { [name]: e } })
                console.log(e, userData.cash_balance)
            } else {
                Toast.info('已超出余额')
            }
        }
        const onSubmit = () => {
            Number(cash_balance) ?
                dispatch({
                    type: 'users/applyWithdraw',
                    payload: {
                        money: Number(cash_balance || 0)
                    }
                })
                : Toast.info('请输入提现金额', 1)
        }
        return (
            <div className="forwardBox">
                {
                    forwardStatus ?
                        <div >
                            <Result
                                img={<Icon type="check-circle" className="spe" style={{ fill: '#09BB07', width: '1.5rem', height: '1.5rem' }} />}
                                title="提交成功"
                                message="提现申请已提交 请耐心等待~"
                            />
                            <div className="commentSubmit" onClick={() => window.history.go(-1)}>确定</div>
                        </div>
                        :
                        <div>
                            <List className="list">
                                {/* pattern="[0-9]*"  */}
                                <InputItem
                                    {...form.getFieldProps('money', {
                                        normalize: (v, prev) => {

                                            if (v && !/^(([1-9]\d*)|0)(\.\d{0,2}?)?$/.test(v)) {
                                                if (v === '.') {
                                                    return '0.';
                                                }
                                                return prev;

                                            }
                                            if (Number(v) <= Number(userData.cash_balance)) {
                                                dispatch({ type: 'users/save', payload: { cash_balance: Number(v) } })
                                                // console.log(e, userData.cash_balance)
                                            } else {
                                                Toast.info('已超出余额', 1)
                                                return prev
                                            }
                                            return v;
                                        },
                                    }) }
                                    moneyKeyboardAlign="left"
                                    type="money"
                                    placeholder="输入提现金额"
                                    ref={el => this.inputRef = el}
                                    onVirtualKeyboardConfirm={v => console.log('onVirtualKeyboardConfirm:', v)}
                                    extra={<div>￥</div>}
                                    clear
                                    moneyKeyboardWrapProps={moneyKeyboardWrapProps}
                                >提现金额</InputItem>
                            </List>
                            <p className="text">提现剩余余额&nbsp;<strong style={{ color: 'red' }}>{(userData.cash_balance - cash_balance).toFixed(2)}￥</strong></p>
                            <p className="text">注：提现金额整数最少10元起<br />请输入真实提现金额范围，如金额错误将导致提现失败。</p>
                            <div className="commentSubmit" onClick={onSubmit}>{loading.effects['users/applyWithdraw'] ? '提交中' : '提交申请'}</div>
                        </div>
                }
            </div>
        )
    }

}
function mapStateToProps(props) {
    return {
        users: props.users,
        loading: props.loading
    };
}
export default connect(mapStateToProps)(createForm()(Forward))