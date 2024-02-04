import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import axios from 'axios';
import emailjs, { EmailJSResponseStatus } from '@emailjs/browser';

const PUBLIC_KEY = 'eoPJ5KwYUNr5YwcQL';
const SERVICE_ID = 'service_gmqxltg';
const TEMPLATE_ID = 'template_clt36qe';

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
  book: Book = { loans: [{}] } as Book;
  isFlipped = true;
  starCount = [0];
  noStarCount = [0];
  reviews = ReviewGenerator.generateRandomReviews(2);
  putOnHoldPopUp = false;
  myReservations: { id: string; dateResa: string; book: Book }[] = [];
  success = false;

  flipBook() {
    this.isFlipped = !this.isFlipped;
  }

  constructor(private route: ActivatedRoute) {}

  async ngOnInit(): Promise<void> {
    this.session = await Session.getInstance();
    this.isConnected = this.session.isOpen;
    this.init();
  }

  async init() {
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
      this.myReservations = res.data;
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

  async submitReservation() {
    if (this.myReservations.length >= 3) return;
    const userId = this.session.user?.id;
    if (!userId) return;
    const bookId = this.book.id;
    try {
      emailjs.send(
        SERVICE_ID,
        TEMPLATE_ID,
        {
          name: this.session.user?.firstname,
          book: this.book.title,
          email: this.session.user?.email,
        },
        {
          publicKey: PUBLIC_KEY,
        }
      );

      const res = await axios.post(
        API_URL('/reservations/create'),
        {
          userId: userId,
          bookId: bookId,
        },
        {
          headers: {
            Authorization: `Bearer ${this.session.token}`,
          },
        }
      );
      if (res.status === 201) {
        this.init();
        this.success = true;
      } else {
      }

      this.putOnHoldPopUp = false;
    } catch (error) {}
  }
}
