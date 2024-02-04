import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
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
  categories: Category[] = [];
  languages: string[] = [];
  releaseDate: number[] = [
    1900, 1950, 1960, 1970, 1980, 1990, 2000, 2010, 2020,
  ];
  availability: string | null = null;

  filteredBooks: Book[] = [];
  visibleBooks: Book[] = [];
  currentPage = 1;
  MAX_BOOKS_PER_PAGE = 20;
  maxPage = 1;
  pageArray = [1];

  glassIcon = faMagnifyingGlass;

  filterForm: FormGroup;

  constructor(private fb: FormBuilder) {
    this.filterForm = this.fb.group({
      categories: [''],
      languages: [''],
      minPages: [''],
      maxPages: [''],
      minYear: [''],
      maxYear: [''],
      available: [''],
      title: [''],
      authors: [''],
    });
  }

  async ngOnInit(): Promise<void> {
    const session = await Session.getInstance();
    this.isConnected = session.isOpen;

    let res = await axios.get(API_URL('/books'));
    this.books = res.data;

    this.filteredBooks = this.books;

    for (const book of this.filteredBooks) {
      if (!this.languages.includes(book.language)) {
        this.languages.push(book.language);
      }
    }
    this.visibleBooks = this.filteredBooks.slice(0, this.MAX_BOOKS_PER_PAGE);
    this.maxPage = Math.ceil(
      this.filteredBooks.length / this.MAX_BOOKS_PER_PAGE
    );
    this.pageArray = Array(this.maxPage)
      .fill(0)
      .map((_, i) => i + 1);

    res = await axios.get(API_URL('/categories'));
    this.categories = res.data;

    console.log(this.books);
  }

  changeAvailabilityCheck(selectedOption: string): void {
    this.availability = this.filterForm.get('available')?.value;
    if (this.availability === selectedOption) {
      // Décocher la case si elle est déjà cochée
      this.availability = null;
    } else {
      this.availability = selectedOption;
    }

    this.filterForm.patchValue({
      available: this.availability,
    });
  }

  async changePage(page: number): Promise<void> {
    if (page < 1) page = this.pageArray.length;
    if (page > this.pageArray.length) page = 1;
    this.currentPage = page;
    this.visibleBooks = this.filteredBooks.slice(
      (page - 1) * this.MAX_BOOKS_PER_PAGE,
      page * this.MAX_BOOKS_PER_PAGE
    );
  }

  private isNullOrEmpty(str: string | null | number): boolean {
    return str === null || str === '';
  }

  async onSubmit(): Promise<void> {
    this.filteredBooks = this.books;

    let title = this.filterForm.get('title')?.value;
    let authors = this.filterForm.get('authors')?.value;
    if (title) title = title.toLowerCase().trim();
    if (authors) authors = authors.toLowerCase().trim();

    const categories = this.filterForm.get('categories')?.value as string;
    const languages = this.filterForm.get('languages')?.value;
    const minPages = this.filterForm.get('minPages')?.value as number | '';
    const maxPages = this.filterForm.get('maxPages')?.value as number | '';
    const minYear = this.filterForm.get('minYear')?.value as number | '';
    const maxYear = this.filterForm.get('maxYear')?.value as number | '';
    const available = this.filterForm.get('available')?.value;

    if (!this.isNullOrEmpty(title)) {
      this.filteredBooks = this.books.filter((book) =>
        book.title.toLowerCase().includes(title.toLowerCase())
      );
    }

    if (!this.isNullOrEmpty(authors)) {
      this.filteredBooks = this.filteredBooks.filter((book) =>
        book.authors
          .flatMap(
            (author) =>
              author.firstname.toLocaleLowerCase() +
              ' ' +
              author.lastname.toLocaleLowerCase()
          )
          .join(' ')
          .includes(authors)
      );
    }

    if (
      !this.isNullOrEmpty(categories) &&
      categories != 'Toutes les catégories'
    ) {
      this.filteredBooks = this.filteredBooks.filter((book) =>
        book.categories.some((category) => category.name === categories)
      );
    }

    if (!this.isNullOrEmpty(languages) && languages != 'Toutes les langues') {
      this.filteredBooks = this.filteredBooks.filter((book) =>
        book.language.toLowerCase().includes(languages.toLowerCase())
      );
    }

    if (!this.isNullOrEmpty(minPages) && typeof minPages == 'number') {
      this.filteredBooks = this.filteredBooks.filter(
        (book) => book.pageNumber >= minPages
      );
    }

    if (!this.isNullOrEmpty(maxPages) && typeof maxPages == 'number') {
      this.filteredBooks = this.filteredBooks.filter(
        (book) => book.pageNumber <= maxPages
      );
    }

    if (!this.isNullOrEmpty(minYear) && typeof minYear == 'number') {
      this.filteredBooks = this.filteredBooks.filter(
        (book) => parseInt(book.releaseDate.split('-')[0]) >= minYear
      );
    }

    if (!this.isNullOrEmpty(maxYear) && typeof maxYear == 'number') {
      this.filteredBooks = this.filteredBooks.filter(
        (book) => parseInt(book.releaseDate.split('-')[0]) <= maxYear
      );
    }

    if (!this.isNullOrEmpty(available)) {
      console.log(available);

      if (available === 'available') {
        this.filteredBooks = this.filteredBooks.filter(
          (book) => book.loans.length === 0 && book.reservation === null
        );
      } else if (available === 'unavailable') {
        this.filteredBooks = this.filteredBooks.filter(
          (book) => book.loans.length > 0
        );
      } else {
        this.filteredBooks = this.filteredBooks.filter(
          (book) => book.reservation != null && book.loans.length === 0
        );
      }
    }

    this.currentPage = 1;
    this.maxPage = Math.ceil(
      this.filteredBooks.length / this.MAX_BOOKS_PER_PAGE
    );
    this.pageArray = Array(this.maxPage)
      .fill(0)
      .map((_, i) => i + 1);
    this.visibleBooks = this.filteredBooks.slice(0, this.MAX_BOOKS_PER_PAGE);
  }

  async onReset(): Promise<void> {
    this.filterForm.reset();
    this.filterForm.patchValue({
      categories: 'Toutes les catégories',
      languages: 'Toutes les langues',
      minYear: '',
      maxYear: '',
      available: '',
    });
    this.filteredBooks = this.books;
    this.currentPage = 1;
    this.maxPage = Math.ceil(
      this.filteredBooks.length / this.MAX_BOOKS_PER_PAGE
    );
    this.pageArray = Array(this.maxPage)
      .fill(0)
      .map((_, i) => i + 1);
    this.visibleBooks = this.filteredBooks.slice(0, this.MAX_BOOKS_PER_PAGE);
  }
}
