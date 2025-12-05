$(document).ready(function () {

    const stateSelect = $('select[name="state"]')

    stateSelect.on('change', function (){
        const val =  $(this).val()
        updateListByState(val)
    })

    function updateListByState(state) {
        let params = new URLSearchParams( window.location.search );
        params.set( 'state', state );

        window.location.search = params.toString();
    }

})