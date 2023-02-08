$(document).ready(() => {
    observCreateEventForm();
    observeUpdateEventForm();
});

function observCreateEventForm() {
    $('#createEventForm').on('submit', (e) => {
        e.preventDefault();
        createEvent();
    });
}

function observeUpdateEventForm() {
    $('#updateEventForm').on('submit', (e) => {
        e.preventDefault();
        updateEvent();
    });
}

function createEvent() {
    const $form = $('#createEventForm');

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: $form.serialize(),

        success: (data) => {
            observCreateEventForm();
            setTimeout(() => {
                location.reload();
            }, 0001);
        },
        error: (data) => {
            appendAlertForEachError(data);
        }
    });
}

function updateEvent() {
    const $form = $('#updateEventForm');

    $.ajax({
        url: $form.attr('action'),
        type: 'PATCH',
        data: $form.serialize(),

        success: (data) => {
            observeUpdateEventForm();
            setTimeout(() => {
                location.reload();
            }, 0001);
        },
        error: (data) => {
            appendAlertForEachError(data);
        }
    });
}

function deleteEvent(eventId) {
    $.ajax({
        url: `/api/v1/events/${eventId}`,
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

function editEvent(eventId) {
    location.href = `/events/${eventId}`;
}

function getEventTickets(eventId) {
    location.href = `/events/${eventId}/tickets`;
}