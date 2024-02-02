import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import axios from 'axios';

import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';
import { Author } from 'src/app/models/Author';
import { Book } from 'src/app/models/Book';
import ReviewGenerator from 'src/app/utils/ReviewGenerator';

@Component({
  selector: 'app-consultbook',
  templateUrl: './consultbook.page.html',
  styleUrls: ['./consultbook.page.css'],
})
export class ConsultBookPage implements OnInit {
  session!: Session;
  isConnected = false;
  book: Book = {} as Book;
  isFlipped = true;
  starCount = [0];
  noStarCount = [0];
  reviews = ReviewGenerator.generateRandomReviews(2);
  putOnHoldPopUp = false;

  flipBook() {
    this.isFlipped = !this.isFlipped;
  }

  constructor(private route: ActivatedRoute) {}

  async ngOnInit(): Promise<void> {
    this.session = await Session.getInstance();
    this.isConnected = this.session.isOpen;

    // Get the id from http://localhost:4200/consult/00c081e5-46c8-4507-8982-197dd634755d
    const id = this.route.snapshot.paramMap.get('id');

    let res = await axios.get(API_URL('/books/' + id));

    this.book = res.data;
    this.starCount = new Array(this.book.summary.length % 5).fill(0);
    this.noStarCount = new Array(5 - (this.book.summary.length % 5)).fill(0);

    if (this.session.user) {
      res = await axios.get(
        API_URL('/reservations/me/' + this.session.user.id),
        {
          headers: {
            Authorization: `Bearer ${this.session.token}`,
          },
        }
      );
      console.log(res.data);
    }
  }

  getAuthorsNames(): string {
    if (!this.book.authors) return '';
    return this.book.authors
      .map((author: Author) => author.firstname + ' ' + author.lastname)
      .join(', ');
  }

  getStrDate(): string {
    const date = new Date(this.book.releaseDate);
    return date.toLocaleDateString();
  }
}
