class ContactPage {
    constructor(jsonFile) {
      this.jsonFile = jsonFile;
      this.data = {};
    }
  
    // Methode om de JSON data op te halen
    fetchData() {
      fetch(this.jsonFile)
        .then(response => response.json())
        .then(data => {
          this.data = data;
          this.renderPage();
        })
        .catch(error => console.error('Error fetching data:', error));
    }
  
    // Methode om de data te renderen op de pagina
    renderPage() {
      document.title = this.data.pageTitle;
  
      // Contact Info
      const contactInfoSection = document.querySelector('.contact-info');
      contactInfoSection.querySelector('h2').textContent = this.data.contactInfo.title;
      contactInfoSection.querySelector('p').textContent = this.data.contactInfo.description;
      contactInfoSection.querySelectorAll('li')[0].textContent = `Telefoonnummer: 📞 ${this.data.contactInfo.phone}`;
      contactInfoSection.querySelector('a').textContent = this.data.contactInfo.email;
      contactInfoSection.querySelector('a').href = `mailto:${this.data.contactInfo.email}`;
  
      // Locations
      const locationsContainer = document.querySelector('.locations');
      this.data.locations.forEach(location => {
        const locationElement = document.createElement('div');
        locationElement.classList.add('address');
        locationElement.innerHTML = `
          <h3>${location.name}</h3>
          <p>Bezoekadres: ${location.visitAddress}</p>
          <p>Postadres: ${location.postAddress}</p>
          <p>Factuuradres: ${location.invoiceAddress}</p>
          <p>Tel.: ${location.phone}</p>
          <p>Fax: ${location.fax}</p>
          <p>KvK: ${location.kvk}</p>
          <p>BTW nr: ${location.vat}</p>
        `;
        locationsContainer.appendChild(locationElement);
      });
    }
  }
  
  // Initialiseren van de ContactPage class en de data ophalen
  const contactPage = new ContactPage('contact.json');
  contactPage.fetchData();
