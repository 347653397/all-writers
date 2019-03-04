import './rate.less';
import Rate from 'rc-rate';

const rote = ({ rote, onClick, contentItems }) => {
    return (
        <div className="scoreBox">
            <ul>
                {
                    contentItems && contentItems.map((d, i) => <li className='text' key={i}><span>{d.name}</span><Rate
                        defaultValue={Number(d.value)}
                        onChange={e => onClick && onClick(i, e)}
                        allowHalf
                        style={{ fontSize: '0.6rem', pointerEvents: onClick == undefined ? 'none' : '' }}
                        character={<i className="anticon anticon-star" />}
                    /><span>{d.value}分</span></li>)
                }
            </ul>
            <ul>
                {/*
                    contentItems && contentItems.map((d, i) => {
                        return (
                            <li key={i}><span>{d.name}</span>
                                {rote.map(f => <span key={f} onClick={() => onClick && onClick(i, f)} className={f <= d.value ? 'active' : null}></span>)}
                                <span>{d.value}分</span>
                            </li>
                        )
                    })
                */}

                {/* <li><span>人物</span>
                {rote.map(d=><span  key={d} onClick={()=>onClick(d,'character')} className={d<=character?'active':null}></span>)}
                <span>{character}分</span>
                </li>
                <li><span>剧情</span>
                {rote.map(d=><span  key={d} onClick={()=>onClick(d,'plot')} className={d<=plot?'active':null}></span>)}
                <span>{plot}分</span>
                </li> */}
            </ul>
        </div>
    )
}
export default rote;