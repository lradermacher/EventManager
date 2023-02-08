const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

const appendAlert = (message, type) => {
    const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('');

    alertPlaceholder.append(wrapper);
}

const appendAlertForEachError = (response) => {
    const errorChildren = response?.responseJSON?.errors?.children;
    const errorKeys = Object.keys(errorChildren);
    errorKeys.forEach((errorKey) => {
        const errors = errorChildren?.[errorKey].errors;
        if (errors && errors.length > 0) {
            errors.forEach((error) => {
                appendAlert(`${errorKey}: ${error}`, 'danger');
            });
        }
    });
}