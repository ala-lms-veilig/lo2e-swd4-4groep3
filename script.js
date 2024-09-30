class Melding {
    constructor(categorie, beschrijving, url) {
        this.categorie = categorie;
        this.beschrijving = beschrijving;
        this.url = url;
    }

    generateCard() {
        const card = document.createElement('a');
        card.href = this.url;
        card.className = 'card';

        const title = document.createElement('h3');
        title.innerText = this.categorie;

        const description = document.createElement('p');
        description.innerText = this.beschrijving;

        card.appendChild(title);
        card.appendChild(description);
        
        return card;
    }
}

class MeldingList {
    constructor(jsonUrl, containerId) {
        this.jsonUrl = jsonUrl;
        this.containerId = containerId;
    }

    async fetchMeldingen() {
        try {
            const response = await fetch(this.jsonUrl);
            const data = await response.json();
            return data.meldingen;
        } catch (error) {
            console.error('Error fetching JSON:', error);
            return [];
        }
    }

    async displayMeldingen() {
        const meldingen = await this.fetchMeldingen();
        const container = document.getElementById(this.containerId);

        meldingen.forEach(meldingData => {
            const melding = new Melding(meldingData.categorie, meldingData.beschrijving, meldingData.url);
            const card = melding.generateCard();
            container.appendChild(card);
        });
    }
}

class Auth {
    static login(email) {
        localStorage.setItem('isLoggedIn', 'true');
        localStorage.setItem('userEmail', email);
        window.location.href = 'home.html'; // Redirect to home after login
    }

    static logout() {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userEmail');
        window.location.href = 'login.html'; // Redirect to login after logout
    }

    static checkLoginStatus() {
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        if (!isLoggedIn) {
            window.location.href = 'login.html'; // Redirect to login if not logged in
        }
    }

    static getUserEmail() {
        return localStorage.getItem('userEmail');
    }
}

// Wanneer de pagina geladen is, haal meldingen op
document.addEventListener('DOMContentLoaded', () => {
    Auth.checkLoginStatus(); // Check login status

    const meldingList = new MeldingList('meldingen.json', 'card-container');
    meldingList.displayMeldingen();
});
