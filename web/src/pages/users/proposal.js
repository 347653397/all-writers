import { connect } from 'dva';
import { TextareaItem, Toast } from 'antd-mobile';

import './index.less';
/**
*--意见建议
*--2018.07.13 anaoei@qq.com
* @method Proposal 文件名
* @param { ReactElement } Proposal 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
        loading: props.loading
    };
}
const Proposal = ({ users, dispatch, loading }) => {
    let { commentProposal } = users;
    return (
        <div className="proposalBox">
            <TextareaItem
                className="commentText"
                rows={5}
                count={100}
                value={commentProposal}
                placeholder="输入您的意见与建议..."
                onChange={(e) => dispatch({ type: 'users/save', payload: { commentProposal: e } })}
            />
            <div className="commentSubmit" onClick={() => {
                commentProposal ?
                    commentProposal.length > 6 ?
                        dispatch({
                            type: 'users/submitFeedback',
                            payload: {
                                content: commentProposal
                            }
                        })
                        : Toast.info('意见建议不能少于6个字', 1)
                    : Toast.info('意见建议不能为空', 1)
            }}>{loading.effects['users/submitFeedback'] ? '提交中' : '提交'}</div>
        </div>
    )
}

export default connect(mapStateToProps)(Proposal)