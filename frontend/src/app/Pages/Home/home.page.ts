import { Component, OnInit } from '@angular/core';

import Session from '../../Middlewares/Session';

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
}
