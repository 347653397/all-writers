import React, {Component} from 'react';
import Cropper from 'react-cropper';
import { Modal } from 'antd-mobile';
import 'cropperjs/dist/cropper.css'

import './index.less'
// import 'cropperjs/dist/cropper.css'; // see installation section above for
// versions of NPM older than 3.0.0 If you choose not to use import, you need to
// assign Cropper to default var Cropper = require('react-cropper').default

class CropperSub extends Component {
    constructor(props) {
        super(props);
        this.cropImage = this
            .cropImage
            .bind(this);
    }
    cropImage = () => {
        if (typeof this.cropper.getCroppedCanvas() === 'undefined') {
            return;
        }
        this.props.getCroppedCanvasReader(this.cropper.getCroppedCanvas().toDataURL('image/jpeg',0.5))
        // this.props.getCroppedCanvasSubmit()
    }

    render() {
        const {fileSrc, ReaderVisible, onClose,cropFosize,ratio,msg} = this.props
        return (
            <Modal
               popup
                animationType="slide-up"
               closable={true}
                visible={ReaderVisible}
                onClose={onClose}
                className="cropperModal">
                <Cropper ref='cropper' src={fileSrc} style={{
                    height: '100%',
                    width: '100%'
                }} // Cropper.js options
                    aspectRatio={ratio} 
                    viewMode={2}
                    guides={false} 
                    center={false}
                    background={false}
                    dragMode={'move'}
                    ref={cropper => {
                    this.cropper = cropper;
                }}/>
                <div className="popupCotentFooter">
                <p style={{marginTop:0,color:'#fff'}}>{msg}</p>
                 <div className="popupitemBtn">
                 <div className="bottomBox" onClick={cropFosize}>换一张</div>
                 <div className="bottomBox" onClick={this.cropImage}>确认</div>
                 </div>
                </div>
            </Modal>

        );
    }
}
export default CropperSub