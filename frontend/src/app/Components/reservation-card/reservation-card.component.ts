import { Component, Input } from '@angular/core';
import axios from 'axios';
import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';
import { Book } from 'src/app/models/Book';

@Component({
  selector: 'app-reservation-card',
  templateUrl: './reservation-card.component.html',
})
export class ReservationCardComponent {
  @Input() reservation: { id: string; dateResa: string; book: Book } | null =
    null;
  @Input() order: number = 0;
  @Input() session: Session | null = null;

  putOnHoldPopUp = false;

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

  async cancelReservation() {
    //alert(this.reservation?.id);
    try {
      const res = await axios.delete(
        API_URL('/reservations/cancel/' + this.reservation?.id),
        {
          headers: { Authorization: `Bearer ${this.session?.token}` },
        }
      );

      if (res.status === 200) {
        this.putOnHoldPopUp = false;
        window.location.reload();
      }
    } catch (e) {
      console.error(e);
    }
  }
}
