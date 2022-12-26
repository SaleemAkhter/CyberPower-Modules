function mgBytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes <= 1) {
        if (bytes !== 0) {
            var bytes = Number(bytes).toFixed(1);
        }
        return bytes + ' Byte';
    }
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1000)));
    return Math.round(bytes / Math.pow(1000, i), 2) + ' ' + sizes[i];
}

function mgTooltipCpu(tooltipItem, data) {
    var used = Number(tooltipItem.yLabel).toFixed(2);
    return used + "%";
}

function mgTooltipServerLoad(tooltipItem, data) {
    var used = Number(tooltipItem.yLabel).toFixed(2);
    return used;
}

function mgTooltipCallbackForMemory(tooltipItem, data) {
    return mgBytesToSize(tooltipItem.yLabel) + "/s";
}

function mgTooltipCallbackForNet(tooltipItem, data) {
    return mgBytesToSize(tooltipItem.yLabel) + "/s";
}

function mgTooltipCallbackForDisk(tooltipItem, data) {
    return mgBytesToSize(tooltipItem.yLabel) + "/s";
}

function mgLocationReload(data) {
    window.location.reload();
}
