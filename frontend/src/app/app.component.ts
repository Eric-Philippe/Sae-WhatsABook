import { Component } from '@angular/core';

const RANDOM_BOOKS = [
  'Le Seigneur des Anneaux',
  'La dernière Erdane',
  'La guerre des mondes',
  'Le meilleur des mondes',
];

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  title = 'frontend';
  randomBook = RANDOM_BOOKS[Math.floor(Math.random() * RANDOM_BOOKS.length)];
}
