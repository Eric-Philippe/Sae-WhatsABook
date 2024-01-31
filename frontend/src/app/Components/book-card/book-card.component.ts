import { Component, Input, OnInit } from '@angular/core';
import { Author } from 'src/app/models/Author';
import { Book } from 'src/app/models/Book';

@Component({
  selector: 'app-book-card',
  templateUrl: './book-card.component.html',
  styleUrls: ['./book-card.component.css'],
})
export class BookCardComponent implements OnInit {
  @Input() book: Book = {} as Book;

  authors: string = '';

  ngOnInit() {
    if (!this.book) return;
    if (!this.book.authors) return;
    this.authors = this.book.authors
      .map((auteur) => auteur.firstname + ' ' + auteur.lastname)
      .join(', ');
  }
}
