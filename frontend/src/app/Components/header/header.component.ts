import { Component, OnInit } from '@angular/core';
import Session from 'src/app/Middlewares/Session';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
})
export class HeaderComponent implements OnInit {
  showPhoneMenu = false;
  isConnected = false;

  constructor() {}

  async ngOnInit(): Promise<void> {
    const session = await Session.getInstance();
    this.isConnected = session.isOpen;
  }
}
