import { Component } from '@angular/core';
import { Router } from '@angular/router';
import axios from 'axios';
import { ProtectedPage } from 'src/app/Middlewares/ProtectedPage';
import API_URL from 'src/app/URL';
import { Book } from 'src/app/models/Book';
import { Loan } from 'src/app/models/Loan';

type BookResa = {
  id: string;
  dateResa: string;
  book: Book;
};

@Component({
  selector: 'app-selection',
  templateUrl: './selection.page.html',
})
export class SelectionPage extends ProtectedPage {
  reservedBooks: { id: string; dateResa: string; book: Book }[] = [];
  fBook: BookResa | null = null;
  sBook: BookResa | null = null;
  tBook: BookResa | null = null;

  myLoans: Loan[] = [];

  constructor(router: Router) {
    super(router);
  }

  override async ngOnInit() {
    await super.ngOnInit();

    let res = await axios.get(
      API_URL('/reservations/me/' + this.session?.user.id),
      {
        headers: { Authorization: `Bearer ${this.session?.token}` },
      }
    );
    if (res.status === 200) {
      this.reservedBooks = res.data;
      this.fBook = this.reservedBooks[0];
      this.sBook = this.reservedBooks[1];
      this.tBook = this.reservedBooks[2];
    }

    res = await axios.get(
      API_URL('/loan/me/current/' + this.session?.user.id),
      {
        headers: { Authorization: `Bearer ${this.session?.token}` },
      }
    );

    console.log(res.data);

    if (res.status === 200) {
      this.myLoans = res.data;
    }
  }

  getDayLeft(date: string): number {
    const dateResa = new Date(date);
    const now = new Date();
    // The member has 7 days to get the book
    const futurDate = new Date(dateResa.setDate(dateResa.getDate() + 7));
    const diff = futurDate.getTime() - now.getTime();

    return Math.ceil(diff / (1000 * 3600 * 24));
  }

  getMaxDate(date: string): string {
    const dateResa = new Date(date);
    // The member has 7 days to get the book
    const futurDate = new Date(dateResa.setDate(dateResa.getDate() + 7));
    // In french
    return futurDate.toLocaleDateString('fr-FR');
  }

  getReservationDate(date: string): string {
    const dateResa = new Date(date);
    // In french
    return dateResa.toLocaleDateString('fr-FR');
  }
}
