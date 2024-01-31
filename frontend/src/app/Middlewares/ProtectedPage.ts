import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import Session from './Session';

@Component({
  template: '',
})
export class ProtectedPage implements OnInit {
  session: Session | null = null;

  constructor(private _router: Router) {}

  async ngOnInit(): Promise<void> {
    this.session = await Session.getInstance();
    this.createSession();
  }

  async createSession(): Promise<void> {
    if (!this.session?.isOpen) {
      this._router.navigate(['/']);
      return;
    }
  }

  logout() {
    this.session?.logout();
    this._router.navigate(['/']);
  }
}
