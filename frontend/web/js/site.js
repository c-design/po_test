$(document).ready(function () {
    initAlertify()
})

function initAlertify(){
    alertify.defaults = {
        pinnable: false,
        pinned:  false,
        notifier: {
            position: 'top-right',
            delay: 4,
            closeButton: true,
        }
    }
}