document.addEventListener('DOMContentLoaded', function() {
    // Afficher le formulaire de réservation
    document.getElementById('showBookingButton').addEventListener('click', function() {
        document.getElementById('booking').style.display = 'block';
        this.style.display = 'none';
    });

    // Gestion du formulaire de réservation
    const bookingForm = document.getElementById('booking-form');
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const serviceSelect = document.getElementById('service-select');

    // Mettre à jour les créneaux horaires disponibles quand la date change
    dateInput.addEventListener('change', function() {
        updateAvailableTimeSlots();
    });

    serviceSelect.addEventListener('change', function() {
        updateAvailableTimeSlots();
    });

    function updateAvailableTimeSlots() {
        const date = dateInput.value;
        const serviceId = serviceSelect.value;
        
        if (!date || !serviceId) return;

        fetch(`/api/timeslots?date=${date}&service=${serviceId}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">Choisissez une heure</option>';
                data.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.id;
                    option.textContent = slot.time;
                    timeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Gestion de la soumission du formulaire
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const message = document.getElementById('reservation-message');
            message.textContent = data.message;
            message.style.display = 'block';
            message.style.color = data.success ? 'green' : 'red';
            
            if (data.success) {
                bookingForm.reset();
                setTimeout(() => {
                    message.style.display = 'none';
                    document.getElementById('booking').style.display = 'none';
                    document.getElementById('showBookingButton').style.display = 'block';
                }, 3000);
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});
