
function ovhServerTypeFormatter(ovhServerType) {
    switch (ovhServerType) {
        case 'Vps':
            return 'blue';
        case 'Dedicated':
            return 'red';

    }
    return 'green';
}