$(document).ready(() => {
    observCreateTicketForm();
    observeUpdateTicketForm();
});

function observCreateTicketForm() {
    $('#createTicketForm').on('submit', (e) => {
        e.preventDefault();
        createTicket();
    });
}

function observeUpdateTicketForm() {
    $('#updateTicketForm').on('submit', (e) => {
        e.preventDefault();
        updateTicket();
    });
}

function createTicket() {
    const $form = $('#createTicketForm');

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: $form.serialize(),

        success: (data) => {
            observCreateTicketForm();
            setTimeout(() => {
                location.reload();
            }, 0001);
        },
        error: (data) => {
            appendAlertForEachError(data);
        }
    });
}

function updateTicket() {
    const $form = $('#updateTicketForm');

    $.ajax({
        url: $form.attr('action'),
        type: 'PATCH',
        data: $form.serialize(),

        success: (data) => {
            observeUpdateTicketForm();
            setTimeout(() => {
                location.reload();
            }, 0001);
        },
        error: (data) => {
            appendAlertForEachError(data);
        }
    });
}

function deleteTicket(ticketId) {
    $.ajax({
        url: `/api/v1/tickets/${ticketId}`,
        type: 'DELETE',

        success: (data) => {
            setTimeout(() => {
                location.reload();
            }, 0001);
        },
        error: (data) => {
            appendAlertForEachError(data);
        }
    });
}

function editTicket(eventId, ticketId) {
    location.href = `/events/${eventId}/tickets/${ticketId}`;
}