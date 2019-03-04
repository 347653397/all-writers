import { connect } from 'dva';
import { List, Toast, InputItem, TextareaItem,ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import Cropper from "../../components/cropper";
import { onMax,getBlobBydataURI } from '../../utils';
import './index.less';
const Item = List.Item;
/**
*--文章模块
*--2018.08.23 anaoei@qq.com
* @method Index 文件名
* @param { ReactElement } Release 组件名
* @param { String } release model名称
*/
function mapStateToProps(props) {
  return {
    release: props.release,
    loading: props.loading,
  };
}

/*七牛配置*/
// 初始化签名服务
var cos = new COS({
  getAuthorization: function (options, callback) {
    var method = (options.Method || 'get').toLowerCase();
    var key = options.Key || '';
    var query = options.Query || {};
    var headers = options.Headers || {};
    var pathname = key.indexOf('/') === 0 ? key : '/' + key;
    var url = 'http://test.startechsoft.cn/getUploadSign?test';
     // +'?method=' + method +'&pathname=' + encodeURIComponent('/')
    var xhr = new XMLHttpRequest();
    var data = {
      method: method,
      pathname: pathname,
      query: query,
      headers: headers,
    };
    xhr.open('POST', url, true);
    xhr.setRequestHeader('content-type', 'application/json');
    xhr.onload = function (e) {
      let {data} = JSON.parse(e.target.responseText);
      console.log(data)
      callback({
        Authorization: data.Authorization,
        // XCosSecurityToken: AuthData.XCosSecurityToken,
      });
    };
    // JSON.stringify(data)
    xhr.send(JSON.stringify(data));

    // var authorization = COS.getAuthorization({
    //   SecretId: 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
    //   SecretKey: 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
    //   Method: options.Method,
    //   Key: options.Key,
    //   Query: options.Query,
    //   Headers: options.Headers,
    //   Expires: 60,
    // });
    // console.log(authorization)
    // callback(authorization);
  }
});
var cosUp = new COS({
  SecretId: 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
  SecretKey: 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
});
class Release extends React.Component {
  constructor(props) {
    super(props)
    //这里提前绑定了this,所以不需要再去绑定什么
    this.onFiles = this.onFiles.bind(this)
    this.autoSubmit = this.autoSubmit.bind(this)
  }
  state={
    uploadLoading:false
  }
  componentWillUnmount() {
      const { dispatch } = this.props;
      dispatch({
        type: "release/save",
        payload: {
          ReaderURL: "",
          ReaderVisible: false,
          title:'',
          author:'',
          audio_pic:'',
          audio_brief:'',
          is_original:2,
          price:'',
          content:'',
          item_id:'',
          audioData:{},
          inType:'',
          localData:''
        }
      });
  }
  //重置
  onChangeReset() {
    document.forms[0].reset();
    const { dispatch } = this.props;
    dispatch({
      type: "release/save",
      payload: {
        ReaderURL: "",
        ReaderVisible: false,

      }
    });
  };
  //上传音频
  autoSubmit = e => {
    e.preventDefault();
    let file = this.fileInput.files[0];
    // if (!/\.(mp3|ogg|waw)$/.test(file.name)) {
    //   alert("请上传音频文件");
    //   return false;
    // }
    this.sliceUploadFile(file, file.name)
  };
  //触发上传
  onFiles() {
    this.fileInput.click()
    document.getElementById("onFile").click()
  }
  //上传七牛
  sliceUploadFile(file) {
    console.log('上传',this)
    this.setState({uploadLoading:true})
    /*来识别地区信息*/
    var Bucket = 'renrenbianju-1256016893';//
    var Region = 'ap-shanghai';
    // 分片上传文件
    let getBolb = getBlobBydataURI(file, "image/jpeg");
    var keyString=Date.now()+Math.floor(Math.random()*10+2);
    cosUp.putObject({
      Bucket: Bucket,
      Region: Region,
      Key: keyString+'.jpg',
      Body: getBolb,
      onHashProgress: function (progressData) {
        console.log('校验中', JSON.stringify(progressData));
      },
      onProgress: (progressData)=>{
        console.log('上传中', JSON.stringify(progressData));

      },
    }, (err, data)=>{
      console.log(err, data,this);
      this.props.dispatch({
        // ,audioImg:data.Location
        type:'release/save',payload:{audio_pic:keyString+'.jpg',localData: file, ReaderVisible: false,}
      })
       this.setState({uploadLoading:false})
    });
    //删除文件
    // cos.deleteObject({
    //     Bucket: 'test-1250000000', /* 必须 */
    //     Region: 'ap-guangzhou',    /* 必须 */
    //     Key: '1.jpg'                            /* 必须 */
    // }, function(err, data) {
    //     console.log(err || data);
    // });
  }
  
  render() {
    const { ReaderURL, inType,ReaderVisible,item_id, dispatch, localData,title,author,audio_pic,audio_brief,is_original,price,content } = this.props.release;
    // const onProps = () => { }
    const onSubmitUp=()=>{
      title?
       author?
       audio_brief?
       content?
       is_original==1 || price?
        dispatch({type:'pushArticle',payload:{
          title,
          author,
          audio_pic,
          audio_brief,
          content,
          is_original,
          price:is_original==2?price:0,
          item_id
        }})
       :Toast.info('请输入价格',1)
       :Toast.info('请输入正文',1)
       :Toast.info('请输入内容简介',1)
       :Toast.info('请输入作者',1)
       :Toast.info('请输入标题',1)
    }
    const onChangeVal=(e,name)=>{
        console.log(e,name)
        dispatch({
          type:'save',
          payload:{
            [name]:e
          }
        })
    }
    // dispatch({ type: 'chooseImage' })
    return (
      <div className="releaseBox">
        <form ref="form">
          <input
            id="onFile"
            type="file"
            // accept="audio/*"
            className="formOpcity"
            ref={input => this.fileInput = input}
            onChange={this.autoSubmit}
          />
        </form>
        
        <ActivityIndicator
              toast
              text="正在上传"
              animating={this.state.uploadLoading}
          />
        <Cropper
          ratio={1 / 0.86}
          ReaderVisible={ReaderVisible}
          fileSrc={ReaderURL}
          msg='请选择适合音频海报的尺寸大小'
          onClose={() => this.onChangeReset()}
          cropFosize={() => dispatch({ type: 'chooseImage' })}
          getCroppedCanvasReader={result => this.sliceUploadFile(result)}
        />
        <div className="top">
          <List className="list">
            <InputItem value={title} onInput={e => onMax(e, 25)} onChange={e=>onChangeVal(e,'title')} placeholder="输入标题" type="text"></InputItem>
            <InputItem value={author} onInput={e => onMax(e, 6)} onChange={e=>onChangeVal(e,'author')} placeholder="输入作者" type="text"></InputItem>
            <TextareaItem
              className="commentText"
              rows={4}
              value={audio_brief}
              onChange={e=>onChangeVal(e,'audio_brief')}
              count={100}
              placeholder="请输入内容简介"
            />
          </List>
          <div className="upload">
            <div onClick={() =>dispatch({ type: 'chooseImage' })}>{localData ? <img src={localData} /> : <span><font></font>缩略图</span>}</div>
            {/*<div onClick={() => this.onFiles()}><span><font></font>音频</span></div>*/}
          </div>
        </div>
        <div className="center">
          <TextareaItem
            className="commentText"
            rows={6}
            count={5000}
            value={content}
            onChange={e=>onChangeVal(e,'content')}
            placeholder="请输入正文…"
          />
          <ul className="checkout">
            <li onClick={()=>dispatch({type:'save',payload:{is_original:1}})}><span className={is_original==1?"active":""} ></span>非原创</li>
            <li onClick={()=>dispatch({type:'save',payload:{is_original:2}})}><span className={is_original==2?"active":""} ></span>原创</li>
          </ul>
        </div>
        {is_original==2?<div className="bottom">
          <List className="list">
            <InputItem value={price} onInput={e => onMax(e, 6)} onChange={e=>onChangeVal(e,'price')} placeholder="输入价格" type="text">价格</InputItem>
          </List>
        </div>
        :null}
        {
           inType==2?
           <div className="commentSubmit" onClick={onSubmitUp}>提交修改</div>
           :
            <div className="commentSubmit" onClick={onSubmitUp}>提交发布</div>
        }
       
        {/* <div className="commentSubmit" onClick={() => {
                window.history.go(-1);
                //     dispatch(routerRedux.push({
                //         pathname: routerName,
                //    }))
            }} style={{ background: "#fff",color:'#E2B979' }}>返&nbsp;回</div> */}
        
      </div>
    )
  }

}

export default connect(mapStateToProps)(Release)
