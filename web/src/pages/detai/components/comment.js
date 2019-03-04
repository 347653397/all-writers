import { connect } from 'dva';
import { Modal, Toast } from 'antd-mobile';
import Comment from '../../../components/comment';

const commentSub = ({ dispatch, detai, popup, loading, commentSubmit }) => {
    let { commentVisible, contentItems, contentItemShow, commentText } = detai;
    return (
        <Modal
            popup={popup}
            visible={commentVisible}
            onClose={() => dispatch({ type: 'detai/save', payload: { commentVisible: false } })}
            animationType="slide-up"
            className="commentModal"
        >
            <Comment commentText={commentText}
                contentItems={contentItems}
                loading={loading.effects['detai/comment']}
                onPropsVal={(name, val) => {
                    if (name == 'commentText') {
                        dispatch({ type: 'detai/save', payload: { commentText: val } })
                    } else {
                        contentItems[name].value = val;
                        dispatch({ type: 'detai/save', payload: { contentItems } })
                    }
                }}
                onClose={() => dispatch({ type: 'detai/save', payload: { commentVisible: false } })}
                commentSubmit={() => {
                    if(commentText){
                        let roteVal = [];
                        contentItems.map((d, i) => {
                            roteVal[i] = d.value
                        });
                        commentSubmit(commentText, roteVal)
                    }else{
                        Toast.info('请先输入内容噢！',1)
                    }
                }}
            />
        </Modal>
    )
}
function mapStateToProps(props) {
    return {
        detai: props.detai,
        loading: props.loading
    };
}
export default connect(mapStateToProps)(commentSub)