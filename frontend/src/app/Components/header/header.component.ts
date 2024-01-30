import { Component } from '@angular/core';
import Session from 'src/app/Middlewares/Session';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
})
export class HeaderComponent {
  showPhoneMenu = false;
  isConnected = false;

  constructor() {
    this.isConnected = Session.getInstance().isOpen;
  }
}
