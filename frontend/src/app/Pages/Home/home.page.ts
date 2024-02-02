import { Component, OnInit } from '@angular/core';
import { faArrowLeft, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import * as L from 'leaflet';

const RANDOM_BOOKS = [
  'Le Seigneur des Anneaux',
  'La dernière Erdane',
  'La guerre des mondes',
  'Le meilleur des mondes',
];

const INSTA_POSTS = [
  {
    text: "Nous sommes ouverts! Nouveaux horaires d'ouverture à partir du 2 janvier 2024. Venez nombreux! #jaimelire #booklovers #livres #lecture #bibliothèque",
    likes: 128,
    image: 'assets/Library.jpg',
  },
  {
    text: "Samedi 23 mars  : journées portes ouvertes dans votre bibliothèque! Nous vous attendons jusqu'à 21h pour vous faire découvrir les secrets derrière nos étagères #bibliothèque #bibliothécaire #jpo",
    likes: 256,
    image: 'assets/po-sign.jpg',
  },
  {
    text: "Chez nous, il y en a pour tous les goûts, mais en ce mois de février, place à la romance! Des histoires d'amour partout, nouvelles, BD, manga... Venez découvrir les coups de ❤️ de notre équipe sur notre stand Saint Valentin #loveisintheair #lovebooks #saintvalentin #romance #livres #bilbiothèque",
    likes: 324,
    image: 'assets/book-glasses.jpg',
  },
];

@Component({
  selector: 'app-root',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.css'],
})
export class HomePage {
  title = 'frontend';
  randomBook = RANDOM_BOOKS[Math.floor(Math.random() * RANDOM_BOOKS.length)];
  displayBanner = true;
  isLibrairyOpen = false;
  map: L.Map | undefined;
  private readonly defaultLat = 43.648852;
  private readonly defaultLng = 1.374464;
  private readonly defaultZoom = 16;
  index = 0;
  instaPosts = INSTA_POSTS;
  faArrowBack = faArrowLeft;
  faArrowNext = faArrowRight;

  ngOnInit() {
    this.initMap();
  }

  constructor() {
    // La bibliothèque est ouverte du mardi au vendredi de 10h à 12h / 14h à 18h et le samedi de 10h à 12h / 14h à 17h
    const date = new Date();
    const day = date.getDay();
    const hour = date.getHours();
    const minute = date.getMinutes();
    if (day > 1 && day < 6) {
      if (hour >= 10 && hour < 12) {
        this.isLibrairyOpen = true;
      } else if (hour >= 14 && hour < 18) {
        this.isLibrairyOpen = true;
      } else if (hour === 12 && minute === 0) {
        this.isLibrairyOpen = true;
      } else if (hour === 18 && minute === 0) {
        this.isLibrairyOpen = true;
      }
    } else if (day === 6) {
      if (hour >= 10 && hour < 12) {
        this.isLibrairyOpen = true;
      } else if (hour >= 14 && hour < 17) {
        this.isLibrairyOpen = true;
      } else if (hour === 12 && minute === 0) {
        this.isLibrairyOpen = true;
      } else if (hour === 17 && minute === 0) {
        this.isLibrairyOpen = true;
      }
    }
  }

  private initMap(): void {
    this.map = L.map('leafletMap').setView(
      [this.defaultLat, this.defaultLng],
      this.defaultZoom
    );

    const tileLayer = L.tileLayer(
      'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      {
        attribution: '© OpenStreetMap contributors',
      }
    );

    tileLayer.addTo(this.map);

    this.map.on('click', (e) => {
      alert(e.latlng);
    });

    const customIcon = L.icon({
      iconUrl: 'assets/pin.png',
      iconSize: [25, 41], // ajustez la taille de l'icône selon vos besoins
      iconAnchor: [12, 41], // point d'ancrage de l'icône, peut également être ajusté
      popupAnchor: [1, -34], // point d'ancrage du popup par rapport à l'icône
    });

    // Ajoutez un marqueur pour l'emplacement spécifique
    L.marker([this.defaultLat, this.defaultLng], { icon: customIcon })
      .addTo(this.map)
      .bindPopup("What's a Book | Bibliothèque");
  }

  nextPost() {
    if (this.index < this.instaPosts.length - 1) {
      this.index++;
    }
  }

  previousPost() {
    if (this.index > 0) {
      this.index--;
    }
  }
}
