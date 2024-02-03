import { Component, Input } from '@angular/core';
import axios from 'axios';
import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';
import { Book } from 'src/app/models/Book';
import { Loan } from 'src/app/models/Loan';

@Component({
  selector: 'app-loan-card',
  templateUrl: './loan-card.component.html',
})
export class LoanCardComponent {
  @Input() loan: Loan | null = null;
  @Input() order: number = 0;
  @Input() session: Session | null = null;

  putOnHoldPopUp = false;

  getAuthorsName() {
    return this.loan?.book.authors
      .map((auteur) => auteur.firstname + ' ' + auteur.lastname)
      .join(', ');
  }

  getLoanDatePlusThreeWeeks() {
    if (!this.loan) return;
    const date = new Date(this.loan?.loanDate);
    if (!date) return;
    console.log(date);

    date.setDate(date.getDate() + 21);
    return date;
  }

  getRemainingDays() {
    if (!this.loan) return 0;
    const date = new Date(this.loan?.loanDate);
    if (!date) return 0;
    date.setDate(date.getDate() + 21);
    const today = new Date();
    const diff = date.getTime() - today.getTime();
    return Math.ceil(diff / (1000 * 3600 * 24));
  }
}
