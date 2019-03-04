
export function getTimeStamp() {
    return (new Date()).getTime();
}

export function getTimeCountDown(val) {
    setInterval((val) => {
        return val - 1
    }, 1000)
}

