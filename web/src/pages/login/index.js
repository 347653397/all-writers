import { connect } from 'dva';
import Login from '../../components/login';
/**
*--登录模块
*--2018.07.13 anaoei@qq.com
* @method Index 文件名
* @param { ReactElement } loginPage 组件名
* @param { String } login model名称
*/
function mapStateToProps(props) {
  return {
    login: props.login,
    loading: props.loading,
  };
}
class loginPage extends React.Component {
  componentDidMount() {
    //  var show_num = [];
    this.draw();
    this.props.dispatch({
      type: 'login/save',
      payload: { drawLoad: this.draw }
    })
  }
  draw = () => {
    const { code_img } = this.props.login;
    var canvas_width = $('#canvas').width();
    var canvas_height = $('#canvas').height();
    var canvas = document.getElementById("canvas");//获取到canvas的对象，演员
    var context = canvas.getContext("2d");//获取到canvas画图的环境，演员表演的舞台
    canvas.width = canvas_width;
    canvas.height = canvas_height;
    var sCode = "A,B,C,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,W,X,Y,Z,1,2,3,4,5,6,7,8,9,0";
    var aCode = sCode.split(",");
    var aLength = aCode.length;//获取到数组的长度

    for (var i = 0; i <= 3; i++) {
      var j = Math.floor(Math.random() * aLength);//获取到随机的索引值
      var deg = Math.random() * 30 * Math.PI / 180;//产生0~30之间的随机弧度
      var txt = aCode[j];//得到随机的一个内容
      code_img[i] = txt.toLowerCase();
      this.props.dispatch({ type: 'login/save', payload: { code_img } })
      var x = 10 + i * 20;//文字在canvas上的x坐标
      var y = 20 + Math.random() * 8;//文字在canvas上的y坐标
      context.font = "bold 23px 微软雅黑";

      context.translate(x, y);
      context.rotate(deg);

      context.fillStyle = this.randomColor();
      context.fillText(txt, 0, 0);

      context.rotate(-deg);
      context.translate(-x, -y);
    }
    for (var i = 0; i <= 5; i++) { //验证码上显示线条
      context.strokeStyle = this.randomColor();
      context.beginPath();
      context.moveTo(Math.random() * canvas_width, Math.random() * canvas_height);
      context.lineTo(Math.random() * canvas_width, Math.random() * canvas_height);
      context.stroke();
    }
    for (var i = 0; i <= 30; i++) { //验证码上显示小点
      context.strokeStyle = this.randomColor();
      context.beginPath();
      var x = Math.random() * canvas_width;
      var y = Math.random() * canvas_height;
      context.moveTo(x, y);
      context.lineTo(x + 1, y + 1);
      context.stroke();
    }
  }
  randomColor = () => {//得到随机的颜色值
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    return "rgb(" + r + "," + g + "," + b + ")";
  }
  componentWillMount() {

  }
  render() {
    const { dispatch } = this.props;
    const { loginData, note_time, code_img, drawLoad } = this.props.login;
    const loginSub = {
      loading: this.props.loading.effects['login/bindingMobile'],
      drawLoad,
      code_img,
      dispatch,
      loginData,
      routerName: '/',//记录要返回上一步的历史，方便登录成功后跳回原来的页面
      time: note_time,
      //发送验证码
      onCode: () => dispatch({ type: 'login/codeGet', payload: { mobile: loginData.phone } }),
      //用户数据
      onProps: (val, name) => dispatch({ type: 'login/save', payload: { loginData: { ...loginData, [name]: val } } }),
      //登录
      onSubmit: () => dispatch({ type: 'login/bindingMobile', payload: { mobile: loginData.phone, code: loginData.note_code, name: loginData.name } })
    }
    return (
      <div className="centerUserBox">
        <Login {...loginSub} />
      </div>
    )
  }

}

export default connect(mapStateToProps)(loginPage)
