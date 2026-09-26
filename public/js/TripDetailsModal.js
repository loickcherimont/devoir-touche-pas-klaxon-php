/**
 * Controls the modal that displays the details of a selected trip.
 */
class TripDetailsModal {
    constructor(modalElement) {
        this.modalElement = modalElement;
        this.authorElement = document.getElementById('tripAuthor');
        this.phoneElement = document.getElementById('tripPhone');
        this.emailElement = document.getElementById('tripEmail');
        this.availableSeatsElement = document.getElementById('tripAvailableSeats');
    }

    /** Registers the Bootstrap event emitted just before the modal opens. */
    listen() {
        this.modalElement.addEventListener('show.bs.modal', (event) => this.loadTrip(event));
    }

    /** Fetches and displays the trip selected by the clicked button. */
    async loadTrip(event) {
        const button = event.relatedTarget;

        if (!(button instanceof HTMLElement) || button.dataset.tripId === undefined) {
            return;
        }

        try {
            const response = await fetch(`/api/trips/${button.dataset.tripId}`);

            if (!response.ok) {
                throw new Error('Impossible de charger les informations du trajet.');
            }

            this.displayTrip(await response.json());
        } catch (error) {
            console.error(error);
            this.displayError();
        }
    }

    /** Updates the modal with the API response. */
    displayTrip(trip) {
        this.authorElement.textContent = `${trip.authorFirstName} ${trip.authorLastName}`;
        this.phoneElement.textContent = trip.phone;
        this.emailElement.textContent = trip.email;
        this.availableSeatsElement.textContent = trip.availableSeats;
    }

    /** Shows a clear message when the API request fails. */
    displayError() {
        this.authorElement.textContent = 'Informations indisponibles.';
        this.phoneElement.textContent = 'Informations indisponibles.';
        this.emailElement.textContent = 'Informations indisponibles.';
        this.availableSeatsElement.textContent = 'Informations indisponibles.';
    }
}

const modalElement = document.getElementById('tripDetailsModal');

if (modalElement !== null) {
    new TripDetailsModal(modalElement).listen();
}
