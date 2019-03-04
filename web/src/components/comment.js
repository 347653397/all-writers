import './comment.less';
// import Rote from './rate';
import { TextareaItem } from 'antd-mobile';

const comment = ({ commentText, loading, onClose, onPropsVal, commentSubmit, contentItems }) => {
  let roteMap = [1, 2, 3, 4, 5];
  return (
    <div className="commentBox">
      <h3>评论一下<span style={{ fontSize: '0.4rem' }} onClick={onClose}>取消</span></h3>
      <TextareaItem
        className="commentText"
        rows={5}
        count={100}
        placeholder="分享心得体会..."
        onChange={(val) => onPropsVal('commentText', val)}
        value={commentText}
      />
      {/* {
        contentItems.length != 0 ?
          <div>
            <h3>内容评分<span>满意请给五星喔！</span></h3>
            <Rote rote={roteMap} contentItems={contentItems} onClick={(id, val) => onPropsVal(id, val)} />
          </div>
          : null
      } */}
      <div className="commentSubmit" onClick={!loading ? commentSubmit : null}>{loading ? '正在提交' : '提交'}</div>
    </div>
  );
};

export default comment;
