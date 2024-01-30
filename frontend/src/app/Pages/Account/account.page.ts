import { Component } from '@angular/core';
import { Router } from '@angular/router';

import {
  faEnvelope,
  faFingerprint,
  faKey,
  faLocationDot,
  faLock,
  faPhone,
  faUser,
} from '@fortawesome/free-solid-svg-icons';
import Session from 'src/app/Middlewares/Session';

@Component({
  selector: 'app-account',
  templateUrl: './account.page.html',
  styleUrls: ['./account.page.css'],
})
export class AccountPage {
  faFingerprintIcon = faFingerprint;
  faUserIcon = faUser;
  faMailIcon = faEnvelope;
  faPhoneIcon = faPhone;
  faAdressIcon = faLocationDot;
  fakeKeyIcon = faKey;
  faLockIcon = faLock;

  constructor(private router: Router) {}

  logout() {
    Session.getInstance().logout();
    this.router.navigate(['/']);
  }
}
