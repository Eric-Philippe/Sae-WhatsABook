import { Component, OnInit } from '@angular/core';
import axios from 'axios';
import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';
import { Book } from 'src/app/models/Book';
import { Category } from 'src/app/models/Category';

@Component({
  selector: 'app-consult',
  templateUrl: './consult.page.html',
  styleUrls: ['./consult.page.css'],
})
export class ConsultPage implements OnInit {
  /** API Reachable content */
  isConnected = false;
  books: Book[] = [];

  /** Filters Fileds */
  bookCategories: Category[] = [];
  languages: string[] = [];
  releaseDate: number[] = [
    1900, 1950, 1960, 1970, 1980, 1990, 2000, 2010, 2020,
  ];
  ableToLoan = false;
  ableToReserve = false;

  visibleBooks: Book[] = [];
  currentPage = 1;
  MAX_BOOKS_PER_PAGE = 25;
  maxPage = 1;
  pageArray = [1];

  constructor() {}

  async ngOnInit(): Promise<void> {
    const session = await Session.getInstance();
    this.isConnected = session.isOpen;

    let res = await axios.get(API_URL('/books'));
    this.books = res.data;
    for (const book of this.books) {
      if (!this.languages.includes(book.language)) {
        this.languages.push(book.language);
      }
    }
    this.visibleBooks = this.books.slice(0, this.MAX_BOOKS_PER_PAGE);
    this.maxPage = Math.ceil(this.books.length / this.MAX_BOOKS_PER_PAGE);
    this.pageArray = Array(this.maxPage)
      .fill(0)
      .map((_, i) => i + 1);

    res = await axios.get(API_URL('/categories'));
    this.bookCategories = res.data;
  }

  async changePage(page: number): Promise<void> {
    if (page < 1) page = this.pageArray.length;
    if (page > this.pageArray.length) page = 1;
    this.currentPage = page;
    this.visibleBooks = this.books.slice(
      (page - 1) * this.MAX_BOOKS_PER_PAGE,
      page * this.MAX_BOOKS_PER_PAGE
    );
  }
}
