import { Component, OnInit } from '@angular/core';
import * as L from 'leaflet';

const RANDOM_BOOKS = [
  'Le Seigneur des Anneaux',
  'La dernière Erdane',
  'La guerre des mondes',
  'Le meilleur des mondes',
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
}
