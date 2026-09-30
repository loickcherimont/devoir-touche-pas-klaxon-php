/**
 * Controls the modal that updates the name of a selected agency.
 */
class AgencyModal {
	constructor(modalElement) {
		this.modalElement = modalElement;
		this.updateAgencyIdElement = document.getElementById('updateAgencyId');
		this.updateAgencyNameElement = document.getElementById('updateAgencyName');
	}

	/** Registers the Bootstrap event emitted just before the modal opens. */
	listen() {
		this.modalElement.addEventListener('show.bs.modal', (event) => this.loadAgency(event));
	}

	/** Fetches the agency selected by the clicked button and fills the form. */
	async loadAgency(event) {
		const button = event.relatedTarget;

		if (!(button instanceof HTMLElement) || button.dataset.agencyId === undefined) {
			return;
		}

		try {
			const response = await fetch(`/api/agencies/${button.dataset.agencyId}`);

			if (!response.ok) {
				throw new Error("Impossible de charger les informations de l'agence.");
			}

			this.displayAgency(await response.json());
		} catch (error) {
			console.error(error);
			this.displayError();
		}
	}

	/** Fills the form with the API response. */
	displayAgency(agency) {
		this.updateAgencyIdElement.value = agency.id;
		this.updateAgencyNameElement.value = agency.nom;
	}

	/** Clears the form when the API request fails. */
	displayError() {
		this.updateAgencyIdElement.value = '';
		this.updateAgencyNameElement.value = '';
	}
}

const agencyModalElement = document.getElementById('agencyUpdateModal');

if (agencyModalElement !== null) {
    new AgencyModal(agencyModalElement).listen();
}