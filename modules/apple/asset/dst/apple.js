$(document).ready(function () {
    bindActions()
})

function bindActions() {
    const $container = $('main')
    const $eatModal = $('#eatApplesModal')

    $container.on('change', 'select[name="state"]', function () {
        const val = $(this).val()
        updateListByState(val)
    })

    $container.on('click', 'button.drop-it', function () {
        const id = $(this).data('id');
        dropApple(id, reloadTableData)
    })

    $eatModal.on('submit', '#eatForm', function (e) {
        e.preventDefault();
        appleEatFormSubmitted($eatModal, $(this))
    })

    $eatModal.on('keydown', 'input', function (e) {
        if(e.keyCode === 13){
            e.preventDefault();
            appleEatFormSubmitted($eatModal, $(this).closest('#eatForm'))
        }
    })

    $eatModal.on('show.bs.modal', function (event) {
        $eatModal.find('input[name="id"]').val($(event.relatedTarget).data('id'));
    })

    $eatModal.on('hidden.bs.modal', function (event) {
        $eatModal.find('input').val('');
        $eatModal.find('#appleNotifications').html('');
    })
}

function appleEatFormSubmitted($modal, $form){
    const data = $form.serializeArray().reduce((o,kv) => ({...o, [kv.name]: kv.value}), {})
    eatApple(
        data,
        successData => {
            onFormSuccess($form.id, successData);
            $modal.modal('hide');
        },
        errData => onFormError($form, errData));
}

function onFormError($form, errors){
    const $alertBlock = $form.find('#appleNotifications');
    const errorsList = errors || {}

    $alertBlock.html('');

    for (let [key, val] of Object.entries(errorsList)) {
        const list = errorsList[key] || []
        list.forEach((val) => $alertBlock.append(`<span class="text-danger"/>${val}</span><br/>`));
    }
}

function onFormSuccess(form, data){
    if(data.state === 'dead'){
        alertify.success("Яблоко съедено!")
    } else {
        alertify.success(`Яблоко надкушено осталось ${data.health}%`)
    }

    reloadTableData()
}

function updateListByState(state) {
    let params = new URLSearchParams(window.location.search);
    params.set('state', state);

    window.location.search = params.toString();
}

function refreshPage() {
    window.location.reload();
}

function eatApple(data, successCb, errorCb) {
    $.ajax({
        url: "/apples/" + data.id + "/eat",
        type: "POST",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify({
            healthCount: data.healthCount,
        })
    }).done(data => {
        if (typeof successCb === 'function') {
            successCb(data)
        }
    }).fail(xhr => {
        if (typeof errorCb === 'function' && xhr.responseJSON.errors) {
            errorCb(xhr.responseJSON.errors)
            return;
        }

        alertify.error('При запросе произошла ошибка:' + xhr.statusCode);
    })
}

function dropApple(id, cb) {
    $.ajax({
        url: "/apples/" + id + "/drop",
        type: "POST",
        complete: function (xhr, txt_status) {
            if (xhr.status !== 204) {
                alertify.error('При запросе произошла ошибка:' + txt_status);
            }

            alertify.success('Яблоко упало на землю');

            if (typeof cb === 'function') {
                cb()
            }
        }
    }).catch(error => {
        alertify.error('При запросе произошла ошибка:' + error);
    })
}

function reloadTableData() {
    $.ajax({
        url: '/apples' + window.location.search,
        type: "GET",
    }).then(data => {
        $('#listContent').replaceWith(data)
    }).catch(error => {
        alertify.error('При запросе произошла ошибка:' + error);
    })
}