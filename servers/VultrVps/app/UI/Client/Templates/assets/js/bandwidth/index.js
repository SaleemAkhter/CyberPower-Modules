function mgBytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes <= 1) {
        if (bytes !== 0) {
            var bytes = Number(bytes).toFixed(1);
        }
        return bytes + ' Byte';
    }
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    var total = bytes / Math.pow(1024, i) ;
    if(sizes[i] == 'GB' || sizes[i] == 'TB' ){
        return total.toFixed(2)+ ' ' + sizes[i];
    }
    return Math.round( total, 2) + ' ' + sizes[i];
}

function mgTooltipCallbackForNet(tooltipItem, data) {
    return mgBytesToSize(tooltipItem.yLabel) + "/s";
}