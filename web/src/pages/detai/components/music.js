
import React from 'react';
import '../../../utils/';
import ProgressBar from './ProgressBar'
class MusicVideo extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            showLyric: false,
            lines: [],
            currentLine: 0
        }
    }
    componentWillUnmount() {
        this.lyric = null
    }
    percentageChangeFunc(percentage) {
        this.props.percentageChangeFunc(percentage)

        let currentTime = this.props.duration * percentage

        // 点击或拖动滚动条时，currentTime 如果小于第一条歌词的播放时间
        // lyric实例因为没有匹配的歌词，导致currentLine停留在上一次
        if (this.lyric && this.state.lines.length && currentTime < this.state.lines[0].time) {
            this.setState({
                currentLine: 0
            })
        }

        this.lyricPlay(currentTime)
    }
    lyricPlay(startTime = 0) {
        this.lyric && this.lyric.play(startTime)
    }
    render() {
        let { togglePlay, audoMsg, img, prevMusic, title, nextMusic, percentage, currentTime, duration, toggleMode, playStatus } = this.props;
        // &nbsp;{audoMsg}
        return (
            <div className="musiceBox">
                <div className="top"><img src={img} /></div>
                {/* 播放进度 */}
                <div className="line">
                    {/* percentageChangeFunc={(percentage) => this.percentageChangeFunc(percentage)}拖动进度 */}
                    <ProgressBar percentage={percentage} percentageChangeFunc={(percentage) => this.percentageChangeFunc(percentage)} />
                </div>
                {/* 播放时间 */}
                <div className="date">
                    <span>{new Date(currentTime).Format("mm:ss")}&nbsp;{audoMsg}</span>
                    <span>{new Date(duration).Format("mm:ss")}</span>
                </div>
                {/* 标题 */}
                <div className="title">
                    {title}
                </div>
                {/* 播放器组件 */}
                <div className="moves">
                    {/* <div></div> */}
                    <div onClick={() => prevMusic()}></div>
                    <div className={playStatus ? "active" : ''} onClick={() => togglePlay()}></div>
                    <div onClick={() => nextMusic()}></div>
                    {/* <div onClick={() => toggleMode()}></div> */}
                </div>
            </div>
        )
    }
}



export default MusicVideo